<?php

namespace Modules\Document\Http\Livewire\Contributor;

use Livewire\Component;
use Livewire\WithFileUploads;
use Modules\Document\Models\Document;
use App\Models\Category;
use Modules\Payment\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class DocumentEdit extends Component
{
    use WithFileUploads;

    public $documentId;
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

        // 1. Process new original file if uploaded
        $originalPath = $doc->file_original_path;
        $previewPath = $doc->preview_file_path;
        $watermarkPath = $doc->file_watermarked_path;
        $watermarkStatus = $doc->watermark_status;
        $fileType = $doc->file_type;
        $fileSize = $doc->file_size;

        if ($this->newFile) {
            $originalExt = $this->newFile->getClientOriginalExtension();
            $originalName = 'doc_' . uniqid() . '.' . $originalExt;
            $originalPath = $this->newFile->storeAs('documents', $originalName, 'public');
            
            $fileType = strtolower($originalExt);
            $fileSize = $this->newFile->getSize();

            // Mock watermark for PDFs
            if ($fileType === 'pdf') {
                $previewPath = $originalPath;
                $watermarkPath = $originalPath;
                $watermarkStatus = 'success';
            } else {
                $previewPath = null;
                $watermarkPath = null;
                $watermarkStatus = 'pending';
            }
        }

        // 2. Process new thumbnail if uploaded
        $thumbnailPath = $doc->thumbnail;
        if ($this->newThumbnailFile) {
            $thumbnailName = 'thumb_' . uniqid() . '.' . $this->newThumbnailFile->getClientOriginalExtension();
            $thumbnailPath = $this->newThumbnailFile->storeAs('documents', $thumbnailName, 'public');
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

        // 4. Update Document
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
            'status' => 'pending', // Send back to pending queue when edited
        ]);

        // 5. Update or Create/Delete Product Mapping
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
            // Delete product if toggled back to free
            Product::where('document_id', $doc->id)->delete();
        }

        session()->flash('success', 'Cập nhật tài liệu thành công! Tài liệu đang chờ duyệt lại.');
        return redirect()->route('contributor.documents.index');
    }

    public function render()
    {
        $categories = Category::where('is_active', true)->get();
        return view('document::livewire.contributor.document-edit', [
            'categories' => $categories,
        ])->layout('layouts.contributor', [
            'pageTitle' => 'Chỉnh sửa tài liệu',
            'breadcrumb' => new \Illuminate\Support\HtmlString('<span class="mx-2">/</span> Contributor <span class="mx-2">/</span> Tài liệu <span class="mx-2">/</span> Chỉnh sửa')
        ]);
    }
}
