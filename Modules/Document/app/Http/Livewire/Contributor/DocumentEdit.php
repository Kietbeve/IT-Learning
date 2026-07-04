<?php

namespace Modules\Document\Http\Livewire\Contributor;

use Livewire\Component;
use Livewire\WithFileUploads;
use Modules\Document\Models\Document;
use Modules\Document\Models\DocumentVersion;
use App\Models\Category;
use Modules\Payment\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Modules\Document\Jobs\ProcessWatermarkJob;
use Modules\Document\Traits\HasSubjects;

class DocumentEdit extends Component
{
    use WithFileUploads, HasSubjects;

    public $documentId;
    public $doc;

    // Form fields
    public $title = '';
    public $category_id = '';
    public $short_description = '';
    public $description = '';
    public $subject_id = '';
    public $visibility = 'public';
    public $selectedTags = [];
    public $customTagsInput = '';
    public $is_downloadable = true;

    // Existing paths (shown on the form)
    public $existingFilePath;
    public $existingThumbnailPath;

    // New file inputs
    public $newFile;
    public $newThumbnailFile;
    public $galleryFiles = [];
    public $excludedGalleryIndices = [];

    // Price details
    public $isPaid = false;
    public $price = 0;

    // Subjects list (loaded dynamically)
    public $subjects = [];

    private function getAuthorId()
    {
        return Auth::id();
    }

    public function mount($id)
    {
        $authorId = $this->getAuthorId();

        // For approved docs, editing is allowed; for pending/rejected we load the doc
        $doc = Document::where('author_id', $authorId)
            ->withTrashed()
            ->with(['product', 'tags'])
            ->find($id);

        if (!$doc) {
            abort(404, 'Tài liệu không tồn tại hoặc bạn không có quyền chỉnh sửa.');
        }

        if ($doc->trashed()) {
            session()->flash('error', 'Tài liệu đã bị xóa bởi quản trị viên. Bạn không thể chỉnh sửa tài liệu này.');
            return redirect()->route('contributor.documents.index');
        }

        $this->doc        = $doc;
        $this->documentId = $doc->id;

        // If doc is approved, check if contributor wants to edit a rejected version
        if ($doc->status === 'approved') {
            $rejectedVersion = $doc->rejectedVersion;
            if ($rejectedVersion) {
                // Load data from the rejected version so contributor can fix and resubmit
                $this->title             = $rejectedVersion->title;
                $this->category_id       = $rejectedVersion->category_id;
                $this->subject_id        = $rejectedVersion->subject_id;
                $this->short_description = $rejectedVersion->short_description;
                $this->description       = $rejectedVersion->description;
                $this->visibility        = $rejectedVersion->visibility;
                $this->is_downloadable   = (bool) $rejectedVersion->is_downloadable;
                $this->existingFilePath  = $rejectedVersion->file_original_path;
                $this->existingThumbnailPath = $rejectedVersion->thumbnail;
                if ($rejectedVersion->price > 0) {
                    $this->isPaid = true;
                    $this->price  = (int) $rejectedVersion->price;
                }
            } else {
                // No rejected version - load from the doc itself (current live version)
                $this->title             = $doc->title;
                $this->category_id       = $doc->category_id;
                $this->subject_id        = $doc->subject_id;
                $this->short_description = $doc->short_description;
                $this->description       = $doc->description;
                $this->visibility        = $doc->visibility;
                $this->is_downloadable   = (bool) $doc->is_downloadable;
                $this->existingFilePath  = $doc->file_original_path;
                $this->existingThumbnailPath = $doc->thumbnail;
                if ($doc->product) {
                    $this->isPaid = true;
                    $this->price  = (int) $doc->product->price;
                }
            }
        } else {
            // Pending / rejected doc – edit the doc directly
            $this->title             = $doc->title;
            $this->category_id       = $doc->category_id;
            $this->subject_id        = $doc->subject_id;
            $this->short_description = $doc->short_description;
            $this->description       = $doc->description;
            $this->visibility        = $doc->visibility;
            $this->is_downloadable   = (bool) $doc->is_downloadable;
            $this->existingFilePath  = $doc->file_original_path;
            $this->existingThumbnailPath = $doc->thumbnail;
            if ($doc->product) {
                $this->isPaid = true;
                $this->price  = (int) $doc->product->price;
            }
        }

        if ($doc->relationLoaded('tags') && $doc->tags->isNotEmpty()) {
            $this->selectedTags = $doc->tags->pluck('id')->toArray();
        }

        // Load subjects for selected category
        $this->subjects = $this->getSubjectsByCategory($this->category_id);
    }

    protected function rules()
    {
        $rules = [
            'title'             => 'required|string|min:5|max:255',
            'category_id'       => 'required|exists:categories,id',
            'subject_id'        => 'required|exists:subjects,id',
            'short_description' => 'nullable|string|min:10|max:500',
            'description'       => 'required|string|min:10|max:50000',
            'visibility'        => 'required|in:public,private,unlisted',
            'is_downloadable'   => 'required|boolean',
            'newFile'           => 'nullable|file|max:51200|mimes:pdf,doc,docx,zip',
            'newThumbnailFile'  => 'nullable|image|max:2048',
            'galleryFiles'      => 'nullable|array|max:10',
            'galleryFiles.*'    => 'image|max:5120',
            'selectedTags'      => 'nullable|array',
            'selectedTags.*'    => 'exists:tags,id',
            'customTagsInput'   => 'nullable|string|max:500',
        ];

        if ($this->isPaid) {
            $rules['price'] = 'required|numeric|min:1000|max:100000000';
        }

        return $rules;
    }

    protected $messages = [
        'title.required'             => 'Vui lòng nhập tiêu đề tài liệu.',
        'title.min'                  => 'Tiêu đề tài liệu phải có ít nhất 5 ký tự.',
        'title.max'                  => 'Tiêu đề tài liệu không được vượt quá 255 ký tự.',
        'category_id.required'       => 'Vui lòng chọn danh mục tài liệu.',
        'category_id.exists'         => 'Danh mục đã chọn không hợp lệ.',
        'subject_id.required'        => 'Vui lòng chọn môn học.',
        'subject_id.exists'          => 'Môn học đã chọn không hợp lệ.',
        'short_description.min'      => 'Mô tả ngắn phải có ít nhất 10 ký tự.',
        'short_description.max'      => 'Mô tả ngắn không được vượt quá 500 ký tự.',
        'description.required'       => 'Vui lòng nhập mô tả chi tiết.',
        'description.min'            => 'Mô tả chi tiết phải có ít nhất 10 ký tự.',
        'description.max'            => 'Mô tả chi tiết không được vượt quá 50.000 ký tự.',
        'visibility.required'        => 'Vui lòng chọn chế độ hiển thị.',
        'visibility.in'              => 'Chế độ hiển thị không hợp lệ.',
        'is_downloadable.required'   => 'Vui lòng chọn quyền tải xuống.',
        'is_downloadable.boolean'    => 'Giá trị quyền tải xuống không hợp lệ.',
        'newFile.mimes'              => 'Tệp tải lên phải thuộc định dạng: PDF, DOC, DOCX hoặc ZIP.',
        'newFile.max'                => 'Dung lượng tệp tối đa là 50MB.',
        'newThumbnailFile.image'     => 'Ảnh bìa tài liệu phải là định dạng hình ảnh.',
        'newThumbnailFile.max'       => 'Dung lượng ảnh bìa tối đa là 2MB.',
        'galleryFiles.max'           => 'Tối đa 10 ảnh gallery.',
        'galleryFiles.*.image'       => 'Gallery chỉ chấp nhận file ảnh.',
        'galleryFiles.*.max'         => 'Mỗi ảnh gallery tối đa 5MB.',
        'price.required'             => 'Vui lòng nhập giá bán cho tài liệu.',
        'price.numeric'              => 'Giá bán phải là số.',
        'price.min'                  => 'Mức giá bán tối thiểu là 1.000đ.',
        'price.max'                  => 'Mức giá bán tối đa là 100.000.000đ.',
        'customTagsInput.max'        => 'Tags tùy chỉnh không được vượt quá 500 ký tự.',
    ];

    public function updated($propertyName)
    {
        if ($propertyName === 'category_id') {
            $this->subject_id = null;
            $this->subjects = $this->getSubjectsByCategory($this->category_id);
        }

        if ($propertyName === 'isPaid' && !$this->isPaid) {
            $this->price = 0;
            $this->resetValidation('price');
        }
    }

    public function removeSelectedFile()
    {
        $this->newFile = null;
        $this->resetValidation('newFile');
    }

    public function removeSelectedThumbnail()
    {
        $this->newThumbnailFile = null;
        $this->resetValidation('newThumbnailFile');
    }

    public function removeGalleryImage($index)
    {
        $this->excludedGalleryIndices[] = $index;
    }
    
    public function setTags($tags)
    {
        $this->selectedTags = is_array($tags) ? $tags : [];
    }

    public function save()
    {
        $this->validate();

        $doc = Document::where('author_id', $this->getAuthorId())
            ->withTrashed()
            ->find($this->documentId);

        if (!$doc) {
            session()->flash('error', 'Tài liệu không tồn tại. Vui lòng kiểm tra lại.');
            return redirect()->route('contributor.documents.index');
        }

        if ($doc->trashed()) {
            session()->flash('error', 'Tài liệu đã bị xóa bởi quản trị viên trong khi bạn đang chỉnh sửa. Thay đổi của bạn không được lưu.');
            return redirect()->route('contributor.documents.index');
        }

        // ── Handle file upload ──
        $year  = now()->format('Y');
        $month = now()->format('m');

        $originalPath    = null;
        $thumbnailPath   = null;
        $fileType        = null;
        $fileSize        = null;
        $watermarkStatus = 'pending';

        if ($this->newFile) {
            $ext  = strtolower($this->newFile->getClientOriginalExtension());
            $uuid = Str::uuid();
            $originalPath = "originals/resources/{$year}/{$month}/{$uuid}.{$ext}";
            Storage::disk('r2')->put($originalPath, file_get_contents($this->newFile->getRealPath()));
            $fileType = $ext;
            $fileSize = $this->newFile->getSize();
        }

        if ($this->newThumbnailFile) {
            $tExt  = strtolower($this->newThumbnailFile->getClientOriginalExtension());
            $tUuid = Str::uuid();
            $thumbnailPath = "thumbnails/resources/{$year}/{$month}/{$tUuid}.{$tExt}";
            Storage::disk('r2')->put($thumbnailPath, file_get_contents($this->newThumbnailFile->getRealPath()));
        }

        // Upload gallery images
        $galleryImagesData = [];
        if (!empty($this->galleryFiles)) {
            foreach ($this->galleryFiles as $index => $galleryFile) {
                if ($galleryFile && !in_array($index, $this->excludedGalleryIndices)) {
                    $galleryExt = strtolower($galleryFile->getClientOriginalExtension());
                    $galleryUuid = Str::uuid();
                    $galleryR2Path = "thumbnails/gallery/{$year}/{$month}/{$galleryUuid}.{$galleryExt}";
                    Storage::disk('r2')->put($galleryR2Path, file_get_contents($galleryFile->getRealPath()));
                    
                    $galleryImagesData[] = [
                        'path'    => $galleryR2Path,
                        'order'   => $index + 1,
                        'caption' => '',
                    ];
                }
            }
        }

        // ── Detect instant visibility toggle on approved doc ──
        $latestVersion = $doc->versions()->orderBy('version_number', 'desc')->first();

        if ($doc->status === 'approved') {
            $cv = $doc->currentVersion;
            $rejectedVersion = $doc->rejectedVersion;

            $onlyVisibilityChanged = (
                !$this->newFile &&
                !$this->newThumbnailFile &&
                $cv?->title             === $this->title &&
                $cv?->category_id       == $this->category_id &&
                $cv?->subject_id        == $this->subject_id &&
                $cv?->short_description === $this->short_description &&
                $cv?->description       === $this->description &&
                $cv?->is_downloadable   == $this->is_downloadable &&
                $cv?->visibility        !== $this->visibility &&
                (!$latestVersion || $latestVersion->version_number == $cv->version_number)
            );

            if ($onlyVisibilityChanged && $cv?->visibility === 'public' && $this->visibility === 'private') {
                if ($cv) {
                    $cv->update(['visibility' => 'private']);
                }
                session()->flash('success', 'Đã chuyển tài liệu sang chế độ riêng tư.');
                return redirect()->route('contributor.documents.index');
            }
        }

        // Determine if we should create a brand new version or edit in-place
        $shouldCreateNewVersion = true;
        if ($latestVersion && $latestVersion->status === 'pending') {
            $shouldCreateNewVersion = false;
        }

        $priceVal = ($this->isPaid && $this->price > 0) ? $this->price : 0;

        if ($shouldCreateNewVersion) {
            // Create a brand new version (version_number = max + 1)
            $nextVersionNumber = ($latestVersion ? $latestVersion->version_number : 0) + 1;

            // Inherit file info from the latest version if no new file is uploaded
            $finalOriginalPath = $originalPath ?? $latestVersion?->file_original_path;
            $finalFileType     = $fileType     ?? $latestVersion?->file_type;
            $finalFileSize     = $fileSize     ?? $latestVersion?->file_size;
            $finalThumbnail    = $thumbnailPath ?? $latestVersion?->thumbnail;

            $newVersion = DocumentVersion::create([
                'document_id'           => $doc->id,
                'version_number'        => $nextVersionNumber,
                'title'                 => $this->title,
                'short_description'     => $this->short_description,
                'description'           => $this->description,
                'category_id'           => $this->category_id,
                'subject_id'            => $this->subject_id,
                'thumbnail'             => $finalThumbnail,
                'gallery_images'        => $galleryImagesData ?: $latestVersion?->gallery_images,
                'file_original_path'    => $finalOriginalPath,
                'file_watermarked_path' => $originalPath ? null : $latestVersion?->file_watermarked_path,
                'preview_file_path'     => $originalPath ? null : $latestVersion?->preview_file_path,
                'file_type'             => $finalFileType,
                'file_size'             => $finalFileSize,
                'visibility'            => $this->visibility,
                'is_downloadable'       => $this->is_downloadable,
                'watermark_status'      => $originalPath ? 'pending' : ($latestVersion ? $latestVersion->watermark_status : 'success'),
                'price'                 => $priceVal,
                'status'                => 'pending',
                'submitted_by'          => Auth::id(),
                'submitted_at'          => now(),
            ]);

            // Dispatch watermark job if new file uploaded
            if ($originalPath && in_array($fileType, ['pdf', 'docx'])) {
                ProcessWatermarkJob::dispatch($doc->id);
            } elseif ($originalPath && $fileType === 'zip') {
                $newVersion->update(['watermark_status' => 'success', 'file_watermarked_path' => $originalPath]);
            }
        } else {
            // Update in-place the existing pending version (which is $latestVersion)
            $latestVersion->update([
                'title'                 => $this->title,
                'short_description'     => $this->short_description,
                'description'           => $this->description,
                'category_id'           => $this->category_id,
                'subject_id'            => $this->subject_id,
                'thumbnail'             => $thumbnailPath         ?? $latestVersion->thumbnail,
                'gallery_images'        => $galleryImagesData ?: $latestVersion->gallery_images,
                'file_original_path'    => $originalPath          ?? $latestVersion->file_original_path,
                'file_watermarked_path' => $originalPath ? null    : $latestVersion->file_watermarked_path,
                'preview_file_path'     => $originalPath ? null    : $latestVersion->preview_file_path,
                'file_type'             => $fileType              ?? $latestVersion->file_type,
                'file_size'             => $fileSize              ?? $latestVersion->file_size,
                'visibility'            => $this->visibility,
                'is_downloadable'       => $this->is_downloadable,
                'watermark_status'      => $originalPath ? 'pending' : $latestVersion->watermark_status,
                'price'                 => $priceVal,
                'status'                => 'pending',
                'rejected_reason'       => null,
                'submitted_by'          => Auth::id(),
                'submitted_at'          => now(),
                'reviewed_by'           => null,
                'reviewed_at'           => null,
            ]);

            // Dispatch watermark job if new file uploaded
            if ($originalPath && in_array($fileType, ['pdf', 'docx'])) {
                ProcessWatermarkJob::dispatch($doc->id);
            } elseif ($originalPath && $fileType === 'zip') {
                $latestVersion->update(['watermark_status' => 'success', 'file_watermarked_path' => $originalPath]);
            }
        }

        // Update Document status if it's not approved yet
        if ($doc->status !== 'approved') {
            $doc->update([
                'status' => 'pending',
            ]);
        }

        // Handle product
        if ($this->isPaid && $this->price > 0) {
            Product::updateOrCreate(
                ['document_id' => $doc->id],
                ['name' => $doc->title, 'price' => $this->price, 'is_active' => true]
            );
        } else {
            Product::where('document_id', $doc->id)->delete();
        }

        $this->syncTags($doc);

        session()->flash('success', $doc->status === 'approved' ? 'Đã tạo bản cập nhật mới! Admin sẽ kiểm duyệt.' : 'Cập nhật tài liệu thành công! Tài liệu đang chờ duyệt lại.');
        return redirect()->route('contributor.documents.index');
    }

    private function syncTags(Document $doc): void
    {
        $tagIds = $this->selectedTags ?? [];
        if (!empty($this->customTagsInput)) {
            $customTagNames = array_map('trim', explode(',', $this->customTagsInput));
            foreach ($customTagNames as $tagName) {
                if (empty($tagName)) continue;
                $tag = \App\Models\Tag::firstOrCreate(
                    ['slug' => Str::slug($tagName)],
                    ['name' => $tagName]
                );
                $tagIds[] = $tag->id;
            }
        }
        $doc->tags()->sync($tagIds);
    }

    public function render()
    {
        $categories = Category::where('is_active', true)->get();
        $allTags    = \App\Models\Tag::orderBy('name')->get();

        return view('document::livewire.contributor.document-edit', [
            'categories' => $categories,
            'subjects'   => $this->subjects,
            'allTags'    => $allTags,
        ])->layout('layouts.contributor', [
            'pageTitle'  => '',
            'breadcrumb' => new \Illuminate\Support\HtmlString('<span class="mx-2">/</span> Contributor <span class="mx-2">/</span> Tài liệu <span class="mx-2">/</span> Chỉnh sửa'),
        ]);
    }
}
