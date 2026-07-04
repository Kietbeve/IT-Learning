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
use Modules\Document\Jobs\ProcessWatermarkJob;
use Modules\Document\Traits\HasSubjects;

class DocumentUpload extends Component
{
    use WithFileUploads, HasSubjects;

    public $title = '';
    public $category_id = '';
    public $subject_id = '';
    public $short_description = '';
    public $description = '';
    public $visibility = 'public';
    public $is_downloadable = true;
    
    // File inputs
    public $originalFile;
    public $thumbnailFile;
    public $galleryFiles = [];
    public $excludedGalleryIndices = [];

    // Price details
    public $isPaid = false;
    public $price = 0;

    // Tags & subjects
    public $selectedTags = [];
    public $customTagsInput = '';
    public $subjects = [];

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
            'originalFile'      => 'required|file|max:51200|mimes:pdf,doc,docx,zip',
            'thumbnailFile'     => 'required|image|max:2048',
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
        'short_description.min'      => 'Mô tả ngắn phải có ít nhất 10 ký tự.',
        'short_description.max'      => 'Mô tả ngắn không được vượt quá 500 ký tự.',
        'description.required'       => 'Vui lòng nhập mô tả chi tiết.',
        'description.min'            => 'Mô tả chi tiết phải có ít nhất 10 ký tự.',
        'description.max'            => 'Mô tả chi tiết không được vượt quá 50.000 ký tự.',
        'visibility.required'        => 'Vui lòng chọn chế độ hiển thị.',
        'visibility.in'              => 'Chế độ hiển thị không hợp lệ.',
        'is_downloadable.required'   => 'Vui lòng chọn quyền tải xuống.',
        'is_downloadable.boolean'    => 'Giá trị quyền tải xuống không hợp lệ.',
        'originalFile.required'      => 'Vui lòng chọn tệp tài liệu đăng tải.',
        'originalFile.mimes'         => 'Tài liệu chỉ hỗ trợ định dạng PDF, DOC, DOCX, ZIP.',
        'originalFile.max'           => 'Dung lượng tệp tối đa là 50MB.',
        'thumbnailFile.required'     => 'Vui lòng tải lên ảnh bìa cho tài liệu.',
        'thumbnailFile.image'        => 'Ảnh bìa tài liệu phải là định dạng hình ảnh.',
        'thumbnailFile.max'          => 'Dung lượng ảnh bìa tối đa là 2MB.',
        'galleryFiles.max'           => 'Tối đa 10 ảnh gallery.',
        'galleryFiles.*.image'       => 'Gallery chỉ chấp nhận file ảnh.',
        'galleryFiles.*.max'         => 'Mỗi ảnh gallery tối đa 5MB.',
        'price.required'             => 'Vui lòng nhập giá bán cho tài liệu.',
        'price.numeric'              => 'Giá bán phải là số.',
        'price.min'                  => 'Mức giá bán tối thiểu là 1.000đ.',
        'price.max'                  => 'Mức giá bán tối đa là 100.000.000đ.',
        'subject_id.required'        => 'Vui lòng chọn môn học.',
        'subject_id.exists'          => 'Môn học đã chọn không hợp lệ.',
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

    public function save()
    {
        $this->validate();

        $year = now()->format('Y');
        $month = now()->format('m');

        // 1. Store the original file to R2 originals/
        $originalExt = strtolower($this->originalFile->getClientOriginalExtension());
        $originalUuid = Str::uuid();
        $originalR2Path = "originals/resources/{$year}/{$month}/{$originalUuid}.{$originalExt}";
        Storage::disk('r2')->put($originalR2Path, file_get_contents($this->originalFile->getRealPath()));

        // 2. Store thumbnail to R2 thumbnails/ if uploaded
        $thumbnailR2Path = null;
        if ($this->thumbnailFile) {
            $thumbExt = strtolower($this->thumbnailFile->getClientOriginalExtension());
            $thumbUuid = Str::uuid();
            $thumbnailR2Path = "thumbnails/resources/{$year}/{$month}/{$thumbUuid}.{$thumbExt}";
            Storage::disk('r2')->put($thumbnailR2Path, file_get_contents($this->thumbnailFile->getRealPath()));
        }

        // 3. Upload gallery images
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

        // 4. Generate unique slug
        $baseSlug = Str::slug($this->title);
        $slug = $baseSlug;
        $count = 1;
        while (Document::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $count;
            $count++;
        }

        $authorId = Auth::id();

        // 4. Create Document record
        $document = Document::create([
            'public_id'          => 'doc_' . Str::random(12),
            'author_id'          => $authorId,
            'slug'               => $slug,
            'status'             => 'pending',
            'current_version_id' => null,
        ]);

        // 5. Create DocumentVersion record (status pending)
        $version = \Modules\Document\Models\DocumentVersion::create([
            'document_id'           => $document->id,
            'version_number'        => 1,
            'title'                 => $this->title,
            'short_description'     => $this->short_description,
            'description'           => $this->description,
            'category_id'           => $this->category_id,
            'subject_id'            => $this->subject_id,
            'thumbnail'             => $thumbnailR2Path,
            'gallery_images'        => $galleryImagesData,
            'file_original_path'    => $originalR2Path,
            'file_watermarked_path' => null,
            'preview_file_path'     => null,
            'file_type'             => $originalExt,
            'file_size'             => $this->originalFile->getSize(),
            'visibility'            => $this->visibility,
            'is_downloadable'       => $this->is_downloadable,
            'watermark_status'      => 'pending',
            'price'                 => ($this->isPaid && $this->price > 0) ? $this->price : 0,
            'status'                => 'pending',
            'submitted_by'          => $authorId,
            'submitted_at'          => now(),
        ]);

        if (strtolower($originalExt) === 'zip') {
            // ZIP: instant success
            $version->update([
                'watermark_status'      => 'success',
                'file_watermarked_path' => $originalR2Path,
            ]);
        }

        // 6. Dispatch async job to process watermark
        ProcessWatermarkJob::dispatch($document->id);

        // 8. Create Product mapping if paid
        if ($this->isPaid && $this->price > 0) {
            Product::create([
                'document_id' => $document->id,
                'name'        => $this->title,
                'price'       => $this->price,
                'is_active'   => true,
            ]);
        }

        // 9. Sync tags
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
        $document->tags()->sync($tagIds);

        session()->flash('success', 'Đăng tải tài liệu thành công! Tài liệu đang chờ Admin kiểm duyệt.');
        return redirect()->route('contributor.documents.index');
    }

    public function render()
    {
        $categories = Category::where('is_active', true)->get();
        $allTags = \App\Models\Tag::orderBy('name')->get();
        return view('document::livewire.contributor.document-upload', [
            'categories' => $categories,
            'subjects'   => $this->subjects,
            'allTags'    => $allTags,
        ])->layout('layouts.contributor', [
            'pageTitle' => 'Đăng tải tài liệu mới',
            'breadcrumb' => new \Illuminate\Support\HtmlString('<span class="mx-2">/</span> Contributor <span class="mx-2">/</span> Tài liệu <span class="mx-2">/</span> Tải lên')
        ]);
    }
}
