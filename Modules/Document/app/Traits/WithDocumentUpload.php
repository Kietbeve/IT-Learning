<?php

namespace Modules\Document\Traits;

use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Modules\Document\Jobs\ProcessWatermarkJob;
use Modules\Document\Models\Document;
use Modules\Document\Models\DocumentVersion;
use Modules\Payment\Models\Product;

trait WithDocumentUpload
{
    use HasSubjects, WithFileUploads;

    public $title = '';
    public $category_id = '';
    public $subject_id = '';
    public $short_description = '';
    public $description = '';
    public $visibility = 'public';
    public $is_downloadable = true;
    public $originalFile;
    public $thumbnailFile;
    public $galleryFiles = [];
    public $excludedGalleryIndices = [];
    public $isPaid = false;
    public $price = 0;
    public $sale_price = null;
    public $selectedTags = [];
    public $customTagsInput = '';
    public $subjects = [];

    protected function rules()
    {
        $rules = [
            'title' => 'required|string|min:5|max:255',
            'category_id' => 'required|exists:categories,id',
            'subject_id' => 'required|exists:subjects,id',
            'short_description' => 'nullable|string|min:10|max:500',
            'description' => 'required|string|min:10|max:50000',
            'visibility' => 'required|in:public,private,unlisted',
            'is_downloadable' => 'required|boolean',
            'originalFile' => 'required|file|max:51200|mimes:pdf,doc,docx,zip',
            'thumbnailFile' => 'required|image|max:2048',
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
        'is_downloadable.required' => 'Vui lòng chọn quyền tải xuống.',
        'is_downloadable.boolean' => 'Giá trị quyền tải xuống không hợp lệ.',
        'originalFile.required' => 'Vui lòng chọn tệp tài liệu đăng tải.',
        'originalFile.mimes' => 'Tệp tải lên phải thuộc định dạng: PDF, DOC, DOCX hoặc ZIP.',
        'originalFile.max' => 'Dung lượng tệp tối đa là 50MB.',
        'thumbnailFile.required' => 'Vui lòng tải lên ảnh bìa tài liệu.',
        'thumbnailFile.image' => 'Ảnh bìa tài liệu phải là định dạng hình ảnh.',
        'thumbnailFile.max' => 'Dung lượng ảnh bìa tối đa là 2MB.',
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
        $this->originalFile = null;
        $this->resetValidation('originalFile');
    }

    public function removeSelectedThumbnail()
    {
        $this->thumbnailFile = null;
        $this->resetValidation('thumbnailFile');
    }

    public function removeGalleryImage($index)
    {
        $this->excludedGalleryIndices[] = $index;
    }

    public function setTags($tags)
    {
        $this->selectedTags = is_array($tags) ? $tags : [];
    }

    /**
     * Process the upload logic and return the created document ID.
     */
    protected function processUpload(string $status, string $successMessage, string $redirectRoute)
    {
        $this->validate();

        $uploadService = app(\Modules\Document\Services\FileUploadService::class);

        $originalR2Path = $uploadService->uploadOriginalDocument($this->originalFile);
        $originalExt = strtolower($this->originalFile->getClientOriginalExtension());

        $thumbnailR2Path = null;
        if ($this->thumbnailFile) {
            $thumbnailR2Path = $uploadService->uploadThumbnail($this->thumbnailFile);
        }

        // Upload gallery images
        $galleryImagesData = [];
        if (! empty($this->galleryFiles)) {
            $galleryImagesData = $uploadService->uploadGalleryImages($this->galleryFiles, $this->excludedGalleryIndices);
        }

        $slug = Document::generateUniqueSlug($this->title);
        $authorId = Auth::id();

        // Create Document record (identity fields only)
        $document = Document::create([
            'public_id' => 'doc_'.Str::random(12),
            'author_id' => $authorId,
            'slug' => $slug,
            'status' => $status,
            'current_version_id' => null,
        ]);

        // Create DocumentVersion record
        $version = DocumentVersion::create([
            'document_id' => $document->id,
            'version_number' => 1,
            'title' => $this->title,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'category_id' => $this->category_id,
            'subject_id' => $this->subject_id,
            'thumbnail' => $thumbnailR2Path,
            'gallery_images' => $galleryImagesData,
            'file_original_path' => $originalR2Path,
            'file_watermarked_path' => null,
            'preview_file_path' => null,
            'file_type' => $originalExt,
            'file_size' => $this->originalFile->getSize(),
            'visibility' => $this->visibility,
            'is_downloadable' => $this->is_downloadable,
            'watermark_status' => 'pending',
            'price' => ($this->isPaid && $this->price > 0) ? $this->price : 0,
            'sale_price' => ($this->isPaid && $this->price > 0 && $this->sale_price > 0) ? $this->sale_price : null,
            'status' => $status,
            'submitted_by' => $authorId,
            'submitted_at' => now(),
            'reviewed_by' => $status === 'approved' ? $authorId : null,
            'reviewed_at' => $status === 'approved' ? now() : null,
        ]);

        // Link current version to document if approved
        if ($status === 'approved') {
            $document->update([
                'current_version_id' => $version->id,
            ]);
        }

        // Handle watermark job dispatch or instant ZIP success
        if (in_array($originalExt, ['pdf', 'docx'])) {
            ProcessWatermarkJob::dispatch($document->id);
        } else {
            // ZIP: instant success
            $version->update([
                'watermark_status' => 'success',
                'file_watermarked_path' => $originalR2Path,
            ]);
        }

        // Create Product if paid
        if ($this->isPaid && $this->price > 0) {
            Product::create([
                'document_id' => $document->id,
                'name' => $this->title,
                'price' => $this->price,
                'sale_price' => $this->sale_price ?: null,
                'is_active' => true,
            ]);
        }

        $tagService = app(\Modules\Document\Services\TagService::class);
        $tagService->syncTags($document, $this->selectedTags ?? [], $this->customTagsInput);

        session()->flash('success', $successMessage);

        return redirect()->route($redirectRoute);
    }
}
