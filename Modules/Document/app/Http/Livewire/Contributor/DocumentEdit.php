<?php

namespace Modules\Document\Http\Livewire\Contributor;

use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Modules\Document\Jobs\ProcessWatermarkJob;
use Modules\Document\Models\Document;
use Modules\Document\Models\DocumentVersion;
use Modules\Document\Services\FileUploadService;
use Modules\Document\Services\TagService;
use Modules\Document\Traits\HasSubjects;
use Modules\Payment\Models\Product;

class DocumentEdit extends Component
{
    use HasSubjects, WithFileUploads;

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



    // Existing paths (shown on the form)
    public $existingFilePath;

    public $existingThumbnailPath;

    public $existingGalleryImages = [];

    // New file inputs
    public $newFile;

    public $newThumbnailFile;

    public $galleryFiles = [];

    public $excludedGalleryIndices = [];

    // Price details
    public $isPaid = false;

    public $price = 0;
    public $sale_price = null;

    // Subjects list (loaded dynamically)
    public $subjects = [];

    private function getAuthorId()
    {
        return Auth::id();
    }

    public function mount($id)
    {
        if (\App\Services\SettingService::get('allow_contributor_upload', '1') === '0') {
            session()->flash('error', 'Hệ thống đang tạm khóa chức năng chỉnh sửa tài liệu của Contributor.');
            return $this->redirectRoute('contributor.documents.index', navigate: true);
        }

        $authorId = $this->getAuthorId();

        // For approved docs, editing is allowed; for pending/rejected we load the doc
        $doc = Document::where('author_id', $authorId)
            ->withTrashed()
            ->with(['product', 'tags'])
            ->find($id);

        if (! $doc) {
            abort(404, 'Tài liệu không tồn tại hoặc bạn không có quyền chỉnh sửa.');
        }

        if ($doc->trashed()) {
            session()->flash('error', 'Tài liệu đã bị xóa bởi quản trị viên. Bạn không thể chỉnh sửa tài liệu này.');

            return redirect()->route('contributor.documents.index');
        }

        $this->doc = $doc;
        $this->documentId = $doc->id;

        // If doc is approved, check if contributor wants to edit a rejected or pending version
        if ($doc->status === 'approved') {
            $rejectedVersion = $doc->rejectedVersion;
            $pendingVersion = $doc->pendingVersion;

            if ($rejectedVersion) {
                // Load data from the rejected version so contributor can fix and resubmit
                $this->title = $rejectedVersion->title;
                $this->category_id = $rejectedVersion->category_id;
                $this->subject_id = $rejectedVersion->subject_id;
                $this->short_description = $rejectedVersion->short_description;
                $this->description = $rejectedVersion->description;
                $this->visibility = $rejectedVersion->visibility;

                $this->existingFilePath = $rejectedVersion->file_original_path;
                $this->existingThumbnailPath = $rejectedVersion->thumbnail;
                $this->existingGalleryImages = $rejectedVersion->gallery_images ?? [];
                if ($rejectedVersion->price > 0) {
                    $this->isPaid = true;
                    $this->price = (int) $rejectedVersion->price;
                    $this->sale_price = $rejectedVersion->sale_price ? (int) $rejectedVersion->sale_price : null;
                }
            } elseif ($pendingVersion) {
                // Load data from the pending version so contributor can view/edit it
                $this->title = $pendingVersion->title;
                $this->category_id = $pendingVersion->category_id;
                $this->subject_id = $pendingVersion->subject_id;
                $this->short_description = $pendingVersion->short_description;
                $this->description = $pendingVersion->description;
                $this->visibility = $pendingVersion->visibility;

                $this->existingFilePath = $pendingVersion->file_original_path;
                $this->existingThumbnailPath = $pendingVersion->thumbnail;
                $this->existingGalleryImages = $pendingVersion->gallery_images ?? [];
                if ($pendingVersion->price > 0) {
                    $this->isPaid = true;
                    $this->price = (int) $pendingVersion->price;
                    $this->sale_price = $pendingVersion->sale_price ? (int) $pendingVersion->sale_price : null;
                }
            } else {
                // No rejected or pending version - load from the doc itself (current live version)
                $this->title = $doc->title;
                $this->category_id = $doc->category_id;
                $this->subject_id = $doc->subject_id;
                $this->short_description = $doc->short_description;
                $this->description = $doc->description;
                $this->visibility = $doc->visibility;

                $this->existingFilePath = $doc->file_original_path;
                $this->existingThumbnailPath = $doc->thumbnail;
                $this->existingGalleryImages = $doc->gallery_images ?? [];
                if ($doc->product && $doc->product->price > 0) {
                    $this->isPaid = true;
                    $this->price = (int) $doc->product->price;
                    $this->sale_price = $doc->product->sale_price ? (int) $doc->product->sale_price : null;
                }
            }
        } else {
            // Pending / rejected doc – edit the doc directly
            $this->title = $doc->title;
            $this->category_id = $doc->category_id;
            $this->subject_id = $doc->subject_id;
            $this->short_description = $doc->short_description;
            $this->description = $doc->description;
            $this->visibility = $doc->visibility;

            $this->existingFilePath = $doc->file_original_path;
            $this->existingThumbnailPath = $doc->thumbnail;
            $this->existingGalleryImages = $doc->gallery_images ?? [];
            if ($doc->product && $doc->product->price > 0) {
                $this->isPaid = true;
                $this->price = (int) $doc->product->price;
                $this->sale_price = $doc->product->sale_price ? (int) $doc->product->sale_price : null;
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
            'title' => 'required|string|min:5|max:255',
            'category_id' => 'required|exists:categories,id',
            'subject_id' => 'required|exists:subjects,id',
            'short_description' => 'nullable|string|min:10|max:500',
            'description' => 'required|string|min:10|max:50000',
            'visibility' => 'required|in:public,private,unlisted',

            'newFile' => 'nullable|file|max:51200|mimes:pdf,doc,docx,zip',
            'newThumbnailFile' => 'nullable|image|max:2048',
            'galleryFiles' => 'nullable|array|max:10',
            'galleryFiles.*' => 'image|max:5120',
            'selectedTags' => 'nullable|array',
            'selectedTags.*' => 'exists:tags,id',
            'customTagsInput' => 'nullable|string|max:500',
        ];

        if ($this->isPaid) {
            $rules['price'] = 'required|numeric|min:1000|max:100000000';
            $rules['sale_price'] = 'nullable|numeric|min:1000|max:100000000|lt:price';
        }

        return $rules;
    }

    protected $messages = [
        'title.required' => 'Vui lòng nhập tiêu đề tài liệu.',
        'title.min' => 'Tiêu đề tài liệu phải có ít nhất 5 ký tự.',
        'title.max' => 'Tiêu đề tài liệu không được vượt quá 255 ký tự.',
        'category_id.required' => 'Vui lòng chọn danh mục tài liệu.',
        'category_id.exists' => 'Danh mục đã chọn không hợp lệ.',
        'subject_id.required' => 'Vui lòng chọn môn học.',
        'subject_id.exists' => 'Môn học đã chọn không hợp lệ.',
        'short_description.min' => 'Mô tả ngắn phải có ít nhất 10 ký tự.',
        'short_description.max' => 'Mô tả ngắn không được vượt quá 500 ký tự.',
        'description.required' => 'Vui lòng nhập mô tả chi tiết.',
        'description.min' => 'Mô tả chi tiết phải có ít nhất 10 ký tự.',
        'description.max' => 'Mô tả chi tiết không được vượt quá 50.000 ký tự.',
        'visibility.required' => 'Vui lòng chọn chế độ hiển thị.',
        'visibility.in' => 'Chế độ hiển thị không hợp lệ.',

        'newFile.mimes' => 'Tệp tải lên phải thuộc định dạng: PDF, DOC, DOCX hoặc ZIP.',
        'newFile.max' => 'Dung lượng tệp tối đa là 50MB.',
        'newThumbnailFile.image' => 'Ảnh bìa tài liệu phải là định dạng hình ảnh.',
        'newThumbnailFile.max' => 'Dung lượng ảnh bìa tối đa là 2MB.',
        'galleryFiles.max' => 'Tối đa 10 ảnh gallery.',
        'galleryFiles.*.image' => 'Gallery chỉ chấp nhận file ảnh.',
        'galleryFiles.*.max' => 'Mỗi ảnh gallery tối đa 5MB.',
        'price.required' => 'Vui lòng nhập giá bán cho tài liệu.',
        'price.numeric' => 'Giá bán phải là số.',
        'price.min' => 'Mức giá bán tối thiểu là 1.000đ.',
        'price.max' => 'Mức giá bán tối đa là 100.000.000đ.',
        'sale_price.numeric' => 'Giá khuyến mãi phải là số.',
        'sale_price.min' => 'Giá khuyến mãi tối thiểu là 1.000đ.',
        'sale_price.max' => 'Giá khuyến mãi tối đa là 100.000.000đ.',
        'sale_price.lt' => 'Giá khuyến mãi phải nhỏ hơn giá gốc.',
        'customTagsInput.max' => 'Tags tùy chỉnh không được vượt quá 500 ký tự.',
    ];

    public function updated($propertyName)
    {
        if ($propertyName === 'category_id') {
            $this->subject_id = null;
            $this->subjects = $this->getSubjectsByCategory($this->category_id);
        }

        if ($propertyName === 'isPaid' && ! $this->isPaid) {
            $this->price = 0;
            $this->sale_price = null;
            $this->resetValidation('price');
            $this->resetValidation('sale_price');
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

        if (! $doc) {
            session()->flash('error', 'Tài liệu không tồn tại. Vui lòng kiểm tra lại.');

            return redirect()->route('contributor.documents.index');
        }

        if ($doc->trashed()) {
            session()->flash('error', 'Tài liệu đã bị xóa bởi quản trị viên trong khi bạn đang chỉnh sửa. Thay đổi của bạn không được lưu.');

            return redirect()->route('contributor.documents.index');
        }

        // ── Handle file upload ──
        $year = now()->format('Y');
        $month = now()->format('m');

        $originalPath = null;
        $thumbnailPath = null;
        $fileType = null;
        $fileSize = null;
        $watermarkStatus = 'pending';

        $uploadService = app(\Modules\Document\Services\FileUploadService::class);

        if ($this->newFile) {
            $originalPath = $uploadService->uploadOriginalDocument($this->newFile);
            $fileType = strtolower($this->newFile->getClientOriginalExtension());
            $fileSize = $this->newFile->getSize();
        }

        if ($this->newThumbnailFile) {
            $thumbnailPath = $uploadService->uploadThumbnail($this->newThumbnailFile);
        }

        // Upload gallery images
        $galleryImagesData = [];
        if (! empty($this->galleryFiles)) {
            $galleryImagesData = $uploadService->uploadGalleryImages($this->galleryFiles, $this->excludedGalleryIndices);
        }

        // ── Detect instant visibility toggle on approved doc ──
        $latestVersion = $doc->versions()->reorder('version_number', 'desc')->first();

        if ($doc->status === 'approved') {
            $cv = $doc->currentVersion;
            $rejectedVersion = $doc->rejectedVersion;

            $onlyVisibilityChanged = (
                ! $this->newFile &&
                ! $this->newThumbnailFile &&
                $cv?->title === $this->title &&
                $cv?->category_id == $this->category_id &&
                $cv?->subject_id == $this->subject_id &&
                $cv?->short_description === $this->short_description &&
                $cv?->description === $this->description &&

                $cv?->visibility !== $this->visibility &&
                (! $latestVersion || $latestVersion->version_number == $cv->version_number)
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
        $salePriceVal = ($this->isPaid && $this->price > 0 && $this->sale_price > 0) ? $this->sale_price : null;

        // Check if anything has changed
        $currentTagIds = $doc->tags->pluck('id')->toArray();
        sort($currentTagIds);
        $selectedTagIds = is_array($this->selectedTags) ? array_map('intval', $this->selectedTags) : [];
        sort($selectedTagIds);
        $tagsChanged = ($currentTagIds !== $selectedTagIds);

        $hasChanges = (
            $this->newFile ||
            $this->newThumbnailFile ||
            !empty($this->galleryFiles) ||
            !empty($this->excludedGalleryIndices) ||
            !empty($this->customTagsInput) ||
            $tagsChanged ||
            $latestVersion?->title !== $this->title ||
            $latestVersion?->category_id != $this->category_id ||
            $latestVersion?->subject_id != $this->subject_id ||
            $latestVersion?->short_description !== $this->short_description ||
            $latestVersion?->description !== $this->description ||

            $latestVersion?->visibility !== $this->visibility ||
            $latestVersion?->price != $priceVal ||
            $latestVersion?->sale_price != $salePriceVal
        );

        if (!$hasChanges) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Không có sự thay đổi nào được thực hiện.']);
            session()->flash('error', 'Không có sự thay đổi nào được thực hiện.');
            return;
        }

        if ($shouldCreateNewVersion) {
            // Create a brand new version (version_number = max + 1)
            $nextVersionNumber = ($latestVersion ? $latestVersion->version_number : 0) + 1;

            // Inherit file info from the latest version if no new file is uploaded
            $finalOriginalPath = $originalPath ?? $latestVersion?->file_original_path;
            $finalFileType = $fileType ?? $latestVersion?->file_type;
            $finalFileSize = $fileSize ?? $latestVersion?->file_size;
            $finalThumbnail = $thumbnailPath ?? $latestVersion?->thumbnail;

            $newVersion = DocumentVersion::create([
                'document_id' => $doc->id,
                'version_number' => $nextVersionNumber,
                'title' => $this->title,
                'short_description' => $this->short_description,
                'description' => $this->description,
                'category_id' => $this->category_id,
                'subject_id' => $this->subject_id,
                'thumbnail' => $finalThumbnail,
                'gallery_images' => $galleryImagesData ?: $latestVersion?->gallery_images,
                'file_original_path' => $finalOriginalPath,
                'file_watermarked_path' => $originalPath ? null : $latestVersion?->file_watermarked_path,
                'preview_file_path' => $originalPath ? null : $latestVersion?->preview_file_path,
                'file_type' => $finalFileType,
                'file_size' => $finalFileSize,
                'visibility' => $this->visibility,

                'watermark_status' => $originalPath ? 'pending' : ($latestVersion ? $latestVersion->watermark_status : 'success'),
                'price' => $priceVal,
                'sale_price' => $salePriceVal,
                'status' => 'pending',
                'submitted_by' => Auth::id(),
                'submitted_at' => now(),
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
                'title' => $this->title,
                'short_description' => $this->short_description,
                'description' => $this->description,
                'category_id' => $this->category_id,
                'subject_id' => $this->subject_id,
                'thumbnail' => $thumbnailPath ?? $latestVersion->thumbnail,
                'gallery_images' => $galleryImagesData ?: $latestVersion->gallery_images,
                'file_original_path' => $originalPath ?? $latestVersion->file_original_path,
                'file_watermarked_path' => $originalPath ? null : $latestVersion->file_watermarked_path,
                'preview_file_path' => $originalPath ? null : $latestVersion->preview_file_path,
                'file_type' => $fileType ?? $latestVersion->file_type,
                'file_size' => $fileSize ?? $latestVersion->file_size,
                'visibility' => $this->visibility,

                'watermark_status' => $originalPath ? 'pending' : $latestVersion->watermark_status,
                'price' => $priceVal,
                'sale_price' => $salePriceVal,
                'status' => 'pending',
                'rejected_reason' => null,
                'submitted_by' => Auth::id(),
                'submitted_at' => now(),
                'reviewed_by' => null,
                'reviewed_at' => null,
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

        // Handle product directly only if document is not approved yet. 
        // For approved documents, the Product will be updated upon Admin approval of the pending version.
        if ($doc->status !== 'approved') {
            if ($this->isPaid && $this->price > 0) {
                Product::updateOrCreate(
                    ['document_id' => $doc->id],
                    ['name' => $doc->title, 'price' => $this->price, 'sale_price' => $salePriceVal, 'is_active' => true]
                );
            } else {
                Product::where('document_id', $doc->id)->update([
                    'price' => 0,
                    'sale_price' => null,
                    'is_active' => false
                ]);
            }
        }

        $tagService = app(\Modules\Document\Services\TagService::class);
        $tagService->syncTags($doc, $this->selectedTags ?? [], $this->customTagsInput);

        session()->flash('success', $doc->status === 'approved' ? 'Đã tạo bản cập nhật mới! Admin sẽ kiểm duyệt.' : 'Cập nhật tài liệu thành công! Tài liệu đang chờ duyệt lại.');

        return redirect()->route('contributor.documents.index');
    }

    public function render()
    {
        $categories = Category::where('is_active', true)->get();
        $allTags = Tag::orderBy('name')->get();

        return view('document::livewire.contributor.document-edit', [
            'categories' => $categories,
            'subjects' => $this->subjects,
            'allTags' => $allTags,
        ])->layout('layouts.contributor', [
            'pageTitle' => '',
            'breadcrumb' => new HtmlString('<span class="mx-2">/</span> Contributor <span class="mx-2">/</span> Tài liệu <span class="mx-2">/</span> Chỉnh sửa'),
        ]);
    }
}
