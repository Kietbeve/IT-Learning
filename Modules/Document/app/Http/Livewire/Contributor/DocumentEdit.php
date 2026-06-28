<?php

namespace Modules\Document\Http\Livewire\Contributor;

use Livewire\Component;
use Livewire\WithFileUploads;
use Modules\Document\Models\Document;
use Modules\Document\Models\DocumentRelationship;
use App\Models\Category;
use Modules\Payment\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Modules\Document\Jobs\ProcessWatermarkJob;

class DocumentEdit extends Component
{
    use WithFileUploads;

    public $documentId;
    public $doc;
    public $title = '';
    public $category_id = '';
    public $short_description = '';
    public $description = '';
    public $visibility = 'public';
    public $is_downloadable = true;
    
    // Existing paths
    public $existingFilePath;
    public $existingThumbnailPath;

    // File inputs
    public $newFile;
    public $newThumbnailFile;

    // Price details
    public $isPaid = false;
    public $price = 0;

    private function getAuthorId()
    {
        $user = Auth::user() ?? \Modules\Auth\Models\User::whereHas('roles', function($q) {
            $q->where('name', 'contributor');
        })->first() ?? \Modules\Auth\Models\User::first();
        return $user?->id;
    }

    public function mount($id)
    {
        $doc = Document::where('author_id', $this->getAuthorId())->with('product')->find($id);
        if (!$doc) {
            abort(404, 'Tài liệu không tồn tại hoặc bạn không có quyền chỉnh sửa.');
        }

        $this->doc = $doc;
        $this->documentId = $doc->id;
        $this->title = $doc->title;
        $this->category_id = $doc->category_id;
        $this->short_description = $doc->short_description;
        $this->description = $doc->description;
        $this->visibility = $doc->visibility;
        $this->is_downloadable = (bool) $doc->is_downloadable;
        $this->existingFilePath = $doc->file_original_path;
        $this->existingThumbnailPath = $doc->thumbnail;

        if ($doc->product) {
            $this->isPaid = true;
            $this->price = (int) $doc->product->price;
        }
    }

    protected function rules()
    {
        $rules = [
            'title' => 'required|string|min:5|max:255',
            'category_id' => 'required|exists:categories,id',
            'short_description' => 'nullable|string|max:500',
            'description' => 'required|string|min:10',
            'visibility' => 'required|in:public,private',
            'is_downloadable' => 'required|boolean',
            'newFile' => 'nullable|file|max:51200|mimes:pdf,docx,zip', // Max 50MB
            'newThumbnailFile' => 'nullable|image|max:2048', // Max 2MB
        ];

        if ($this->isPaid) {
            $rules['price'] = 'required|numeric|min:1000';
        }

        return $rules;
    }

    protected $messages = [
        'title.required' => 'Vui lòng nhập tiêu đề tài liệu.',
        'title.min' => 'Tiêu đề tài liệu phải có ít nhất 5 ký tự.',
        'category_id.required' => 'Vui lòng chọn danh mục tài liệu.',
        'description.required' => 'Vui lòng nhập mô tả chi tiết.',
        'description.min' => 'Mô tả chi tiết phải có ít nhất 10 ký tự.',
        'newFile.mimes' => 'Tệp tải lên phải thuộc định dạng: PDF, DOCX hoặc ZIP.',
        'newFile.max' => 'Dung lượng tệp tối đa là 50MB.',
        'newThumbnailFile.image' => 'Ảnh bìa tài liệu phải là định dạng hình ảnh.',
        'newThumbnailFile.max' => 'Dung lượng ảnh bìa tối đa là 2MB.',
        'price.required' => 'Vui lòng nhập giá bán cho tài liệu.',
        'price.min' => 'Mức giá bán tối thiểu là 1.000đ.',
    ];

    public function updatedIsPaid($value)
    {
        if (!$value) {
            $this->price = 0;
        }
    }

    public function save()
    {
        $this->validate();

        $doc = Document::where('author_id', $this->getAuthorId())->find($this->documentId);
        if (!$doc) {
            abort(403);
        }

        // Check if anything actually changed
        $hasFileChange = $this->newFile !== null;
        $hasThumbnailChange = $this->newThumbnailFile !== null;

        $hasTextChange = (
            $doc->title !== $this->title ||
            $doc->category_id != $this->category_id ||
            $doc->short_description !== $this->short_description ||
            $doc->description !== $this->description ||
            $doc->visibility !== $this->visibility ||
            $doc->is_downloadable != $this->is_downloadable
        );

        $hasPriceChange = false;
        if ($doc->product) {
            $hasPriceChange = ($this->isPaid && $this->price != $doc->product->price) || !$this->isPaid;
        } else {
            $hasPriceChange = $this->isPaid && $this->price > 0;
        }

        if (!$hasFileChange && !$hasThumbnailChange && !$hasTextChange && !$hasPriceChange) {
            session()->flash('error', 'Bạn chưa thay đổi thông tin nào. Vui lòng chỉnh sửa trước khi lưu.');
            return redirect()->back();
        }

        // Check if ONLY visibility changed on APPROVED document
        if ($doc->status === 'approved') {
            $onlyVisibilityChanged = (
                $doc->visibility !== $this->visibility &&
                $doc->title === $this->title &&
                $doc->category_id == $this->category_id &&
                $doc->short_description === $this->short_description &&
                $doc->description === $this->description &&
                $doc->is_downloadable == $this->is_downloadable &&
                !$hasFileChange &&
                !$hasThumbnailChange &&
                !$hasPriceChange
            );

            if ($onlyVisibilityChanged) {
                // Public → Private: INSTANT (no approval needed)
                if ($doc->visibility === 'public' && $this->visibility === 'private') {
                    $doc->update(['visibility' => 'private']);
                    session()->flash('success', 'Đã chuyển tài liệu sang chế độ riêng tư.');
                    return redirect()->route('contributor.documents.index');
                }
                
                // Private → Public: CREATE DRAFT (needs approval)
                // Fall through to draft creation logic below
            }
        }

        // 1. Process new original file if uploaded
        $originalPath = $doc->file_original_path;
        $previewPath = $doc->preview_file_path;
        $watermarkPath = $doc->file_watermarked_path;
        $watermarkStatus = $doc->watermark_status;
        $fileType = $doc->file_type;
        $fileSize = $doc->file_size;

        if ($this->newFile) {
            $year = now()->format('Y');
            $month = now()->format('m');
            $originalExt = strtolower($this->newFile->getClientOriginalExtension());
            $originalUuid = Str::uuid();
            $originalPath = "originals/resources/{$year}/{$month}/{$originalUuid}.{$originalExt}";
            Storage::disk('r2')->put($originalPath, file_get_contents($this->newFile->getRealPath()));

            $fileType = $originalExt;
            $fileSize = $this->newFile->getSize();

            // Re-dispatch watermark job for new file
            $previewPath = null;
            $watermarkPath = null;
            $watermarkStatus = 'pending';
        }

        // 2. Process new thumbnail if uploaded
        $thumbnailPath = $doc->thumbnail;
        if ($this->newThumbnailFile) {
            $year = now()->format('Y');
            $month = now()->format('m');
            $thumbExt = strtolower($this->newThumbnailFile->getClientOriginalExtension());
            $thumbUuid = Str::uuid();
            $thumbnailPath = "thumbnails/resources/{$year}/{$month}/{$thumbUuid}.{$thumbExt}";
            Storage::disk('r2')->put($thumbnailPath, file_get_contents($this->newThumbnailFile->getRealPath()));
        }

        // 3. Generate unique slug if title has changed
        $slug = $doc->slug;
        if ($doc->title !== $this->title) {
            $baseSlug = Str::slug($this->title);
            $slug = $baseSlug;
            $count = 1;
            while (Document::where('slug', $slug)->where('id', '!=', $doc->id)->exists()) {
                $slug = $baseSlug . '-' . $count;
                $count++;
            }
        }

        // 4. Update Document or Create Draft Copy
        if ($doc->status === 'approved' && $doc->parent_document_id === null) {
            // Only create draft when editing ORIGINAL approved document (not drafts)
            $draft = Document::create([
                'public_id' => 'doc_' . Str::random(12),
                'author_id' => $doc->author_id,
                'category_id' => $this->category_id,
                'title' => $this->title,
                'slug' => $slug . '-draft-' . $doc->id, // Unique slug for draft
                'short_description' => $this->short_description,
                'description' => $this->description,
                'thumbnail' => $thumbnailPath,
                'preview_file_path' => $previewPath,
                'file_original_path' => $originalPath,
                'file_watermarked_path' => $watermarkPath,
                'file_type' => $fileType,
                'file_size' => $fileSize,
                'visibility' => $this->visibility,
                'is_downloadable' => $this->is_downloadable,
                'watermark_status' => $watermarkStatus,
                'status' => 'pending',
                'parent_document_id' => $doc->id, // Link to original
            ]);

            if ($this->newFile && in_array($fileType, ['pdf', 'docx'])) {
                ProcessWatermarkJob::dispatch($draft->id);
            }

            // Handle product for draft
            if ($this->isPaid && $this->price > 0) {
                Product::updateOrCreate(
                    ['document_id' => $draft->id],
                    [
                        'name' => $draft->title,
                        'price' => $this->price,
                        'is_active' => true,
                    ]
                );
            }

            // Create DocumentRelationship for edit submission
            DocumentRelationship::create([
                'parent_document_id' => $doc->id,
                'draft_document_id' => $draft->id,
                'relationship_type' => 'edit_submission',
                'status' => 'pending',
                'submitted_by' => Auth::id(),
                'submitted_at' => now(),
            ]);

            session()->flash('success', 'Đã tạo bản chỉnh sửa! Admin sẽ duyệt bản cập nhật. Tài liệu gốc vẫn đang live.');
        } else {
            // Document not approved yet → Edit in-place (existing behavior)
            $doc->update([
                'category_id' => $this->category_id,
                'title' => $this->title,
                'slug' => $slug,
                'short_description' => $this->short_description,
                'description' => $this->description,
                'thumbnail' => $thumbnailPath,
                'preview_file_path' => $previewPath,
                'file_original_path' => $originalPath,
                'file_watermarked_path' => $watermarkPath,
                'file_type' => $fileType,
                'file_size' => $fileSize,
                'visibility' => $this->visibility,
                'is_downloadable' => $this->is_downloadable,
                'watermark_status' => $watermarkStatus,
                'status' => 'pending',
            ]);

            if ($this->newFile && in_array($fileType, ['pdf', 'docx'])) {
                ProcessWatermarkJob::dispatch($doc->id);
            }

            // Handle product for in-place edit
            if ($this->isPaid && $this->price > 0) {
                Product::updateOrCreate(
                    ['document_id' => $doc->id],
                    [
                        'name' => $doc->title,
                        'price' => $this->price,
                        'is_active' => true,
                    ]
                );
            } else {
                Product::where('document_id', $doc->id)->delete();
            }

            // Create DocumentRelationship for new submission (resubmit)
            DocumentRelationship::create([
                'parent_document_id' => null,
                'draft_document_id' => $doc->id,
                'relationship_type' => 'new_submission',
                'status' => 'pending',
                'submitted_by' => Auth::id(),
                'submitted_at' => now(),
            ]);

            session()->flash('success', 'Cập nhật tài liệu thành công! Tài liệu đang chờ duyệt lại.');
        }
        return redirect()->route('contributor.documents.index');
    }

    public function render()
    {
        $categories = Category::where('is_active', true)->get();
        return view('document::livewire.contributor.document-edit', [
            'categories' => $categories,
        ])->layout('layouts.contributor', [
            'pageTitle' => '',
            'breadcrumb' => new \Illuminate\Support\HtmlString('<span class="mx-2">/</span> Contributor <span class="mx-2">/</span> Tài liệu <span class="mx-2">/</span> Chỉnh sửa')
        ]);
    }
}
