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

class DocumentUpload extends Component
{
    use WithFileUploads;

    public $title = '';
    public $category_id = '';
    public $short_description = '';
    public $description = '';
    public $visibility = 'public';
    public $is_downloadable = true;
    
    // File inputs
    public $originalFile;
    public $thumbnailFile;

    // Price details
    public $isPaid = false;
    public $price = 0;

    protected function rules()
    {
        $rules = [
            'title' => 'required|string|min:5|max:255',
            'category_id' => 'required|exists:categories,id',
            'short_description' => 'nullable|string|max:500',
            'description' => 'required|string|min:10',
            'visibility' => 'required|in:public,private',
            'is_downloadable' => 'required|boolean',
            'originalFile' => 'required|file|max:51200|mimes:pdf,docx,zip', // Max 50MB
            'thumbnailFile' => 'nullable|image|max:2048', // Max 2MB
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
        'originalFile.required' => 'Vui lòng chọn tệp tài liệu đăng tải.',
        'originalFile.mimes' => 'Tệp tải lên phải thuộc định dạng: PDF, DOCX hoặc ZIP.',
        'originalFile.max' => 'Dung lượng tệp tối đa là 50MB.',
        'thumbnailFile.image' => 'Ảnh bìa tài liệu phải là định dạng hình ảnh.',
        'thumbnailFile.max' => 'Dung lượng ảnh bìa tối đa là 2MB.',
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

        $year = now()->format('Y');
        $month = now()->format('m');

        // 1. Store the original file to R2 originals/
        $originalExt = strtolower($this->originalFile->getClientOriginalExtension());
        $originalUuid = Str::uuid();
        $originalR2Path = "originals/resources/{$year}/{$month}/{$originalUuid}.{$originalExt}";
        Storage::disk('r2')->put($originalR2Path, file_get_contents($this->originalFile->getRealPath()));

        // 2. Watermark will be processed asynchronously via Queue
        $previewPath = null;
        $watermarkPath = null;
        $watermarkStatus = 'pending';

        // 3. Store thumbnail to R2 thumbnails/ if uploaded
        $thumbnailR2Path = null;
        if ($this->thumbnailFile) {
            $thumbExt = strtolower($this->thumbnailFile->getClientOriginalExtension());
            $thumbUuid = Str::uuid();
            $thumbnailR2Path = "thumbnails/resources/{$year}/{$month}/{$thumbUuid}.{$thumbExt}";
            Storage::disk('r2')->put($thumbnailR2Path, file_get_contents($this->thumbnailFile->getRealPath()));
        }

        // 4. Generate unique slug
        $baseSlug = Str::slug($this->title);
        $slug = $baseSlug;
        $count = 1;
        while (Document::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $count;
            $count++;
        }

        // 5. Create Document record
        $document = Document::create([
            'public_id' => 'doc_' . Str::random(12),
            'author_id' => Auth::id() ?? (\Modules\Auth\Models\User::whereHas('roles', function($q) {
                $q->where('name', 'contributor');
            })->first() ?? \Modules\Auth\Models\User::first())?->id,
            'category_id' => $this->category_id,
            'title' => $this->title,
            'slug' => $slug,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'thumbnail' => $thumbnailR2Path,
            'preview_file_path' => $previewPath,
            'file_original_path' => $originalR2Path,
            'file_watermarked_path' => $watermarkPath,
            'file_type' => $originalExt,
            'file_size' => $this->originalFile->getSize(),
            'visibility' => $this->visibility,
            'is_downloadable' => $this->is_downloadable,
            'watermark_status' => $watermarkStatus,
            'status' => 'pending',
        ]);

        // 5.1. Dispatch async job to process watermark
        ProcessWatermarkJob::dispatch($document->id);

        // 6. Create Product mapping if paid
        if ($this->isPaid && $this->price > 0) {
            Product::create([
                'document_id' => $document->id,
                'name' => $document->title,
                'price' => $this->price,
                'is_active' => true,
            ]);
        }

        session()->flash('success', 'Đăng tải tài liệu thành công! Tài liệu đang chờ Admin kiểm duyệt.');
        return redirect()->route('contributor.documents.index');
    }

    public function render()
    {
        $categories = Category::where('is_active', true)->get();
        return view('document::livewire.contributor.document-upload', [
            'categories' => $categories,
        ])->layout('layouts.contributor', [
            'pageTitle' => 'Đăng tải tài liệu mới',
            'breadcrumb' => new \Illuminate\Support\HtmlString('<span class="mx-2">/</span> Contributor <span class="mx-2">/</span> Tài liệu <span class="mx-2">/</span> Tải lên')
        ]);
    }
}
