<?php

namespace Modules\Document\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use Modules\Document\Models\Document;
use Modules\Document\Models\DocumentVersion;
use Modules\Document\Models\DocumentDownload;
use Modules\Document\Models\DocumentComment;
use Modules\Document\Models\DocumentReview;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Modules\Document\Traits\HasSubjects;

class DocumentDetail extends Component
{
    use WithFileUploads, HasSubjects;

    public $documentId;
    public $rejectionReason = '';
    public $from = 'moderation';
    
    public $showHistoryModal = false;
    public $submissionHistory = [];
    
    public $editMode = false;
    public $editTitle;
    public $editCategoryId;
    public $editSubjectId;
    public $editShortDescription;
    public $editDescription;
    public $editVisibility;
    public $editIsDownloadable;
    public $editPrice;
    public $editSelectedTags = [];
    public $editCustomTagsInput = '';
    public $editSubjects = [];
    public $editFile;
    public $editThumbnail;
    public $editGalleryFiles = [];
    public $excludedEditGalleryIndices = [];
    protected $zipFiles = [];

    protected function rules()
    {
        $rules = [
            'editTitle'            => 'required|string|min:5|max:255',
            'editCategoryId'       => 'required|exists:categories,id',
            'editSubjectId'        => 'required|exists:subjects,id',
            'editShortDescription' => 'nullable|string|min:10|max:500',
            'editDescription'      => 'required|string|min:10|max:50000',
            'editVisibility'       => 'required|in:public,private,unlisted',
            'editIsDownloadable'   => 'required|boolean',
            'editSelectedTags'     => 'nullable|array',
            'editSelectedTags.*'   => 'exists:tags,id',
            'editCustomTagsInput'  => 'nullable|string|max:500',
            'editFile'             => 'nullable|file|mimes:pdf,doc,docx,zip|max:51200',
            'editThumbnail'        => 'nullable|image|max:2048',
            'editGalleryFiles'     => 'nullable|array|max:10',
            'editGalleryFiles.*'   => 'image|max:5120',
        ];

        if ($this->editPrice > 0) {
            $rules['editPrice'] = 'required|numeric|min:1000|max:100000000';
        } else {
            $rules['editPrice'] = 'nullable|numeric|min:0|max:100000000';
        }

        return $rules;
    }

    protected $messages = [
        'editTitle.required'          => 'Vui lòng nhập tiêu đề tài liệu.',
        'editTitle.min'               => 'Tiêu đề tài liệu phải có ít nhất 5 ký tự.',
        'editTitle.max'               => 'Tiêu đề tài liệu không được vượt quá 255 ký tự.',
        'editCategoryId.required'     => 'Vui lòng chọn danh mục tài liệu.',
        'editCategoryId.exists'       => 'Danh mục đã chọn không hợp lệ.',
        'editSubjectId.required'      => 'Vui lòng chọn môn học.',
        'editSubjectId.exists'        => 'Môn học đã chọn không hợp lệ.',
        'editShortDescription.min'    => 'Mô tả ngắn phải có ít nhất 10 ký tự.',
        'editShortDescription.max'    => 'Mô tả ngắn không được vượt quá 500 ký tự.',
        'editDescription.required'     => 'Vui lòng nhập mô tả chi tiết.',
        'editDescription.min'          => 'Mô tả chi tiết phải có ít nhất 10 ký tự.',
        'editDescription.max'          => 'Mô tả chi tiết không được vượt quá 50.000 ký tự.',
        'editVisibility.required'     => 'Vui lòng chọn chế độ hiển thị.',
        'editVisibility.in'           => 'Chế độ hiển thị không hợp lệ.',
        'editIsDownloadable.required' => 'Vui lòng chọn quyền tải xuống.',
        'editIsDownloadable.boolean'  => 'Quyền tải xuống không hợp lệ.',
        'editPrice.numeric'           => 'Giá bán phải là số.',
        'editPrice.min'               => 'Mức giá bán tối thiểu là 1.000đ khi bán có phí hoặc 0đ nếu miễn phí.',
        'editPrice.max'               => 'Mức giá bán tối đa là 100.000.000đ.',
        'editFile.mimes'              => 'Tệp tải lên phải thuộc định dạng: PDF, DOC, DOCX hoặc ZIP.',
        'editFile.max'                => 'Dung lượng tệp tối đa là 50MB.',
        'editThumbnail.image'         => 'Ảnh bìa tài liệu phải là định dạng hình ảnh.',
        'editThumbnail.max'           => 'Dung lượng ảnh bìa tối đa là 2MB.',
        'editGalleryFiles.max'        => 'Tối đa 10 ảnh gallery.',
        'editGalleryFiles.*.image'    => 'Gallery chỉ chấp nhận file ảnh.',
        'editGalleryFiles.*.max'      => 'Mỗi ảnh gallery tối đa 5MB.',
    ];

    public function mount($id)
    {
        $this->documentId = $id;
        $this->from = request()->query('from', 'moderation');
        $doc = Document::with(['tags', 'currentVersion', 'latestVersion', 'pendingVersion', 'product'])->find($id);
        if (!$doc) {
            abort(404);
        }
        
        // Cập nhật lấy data từ pendingVersion nếu có (để Admin duyệt bản sửa), nếu không thì lấy currentVersion
        $targetVersion = $doc->pendingVersion ?? $doc->currentVersion ?? $doc->latestVersion;
        
        $this->editTitle = $targetVersion?->title;
        $this->editCategoryId = $targetVersion?->category_id;
        $this->editSubjectId = $targetVersion?->subject_id;
        $this->editShortDescription = $targetVersion?->short_description;
        $this->editDescription = $targetVersion?->description;
        $this->editVisibility = $targetVersion?->visibility ?? 'public';
        $this->editIsDownloadable = $targetVersion?->is_downloadable ?? true;
        
        // Lấy giá từ version thay vì từ product, vì product chỉ ứng với bản đã duyệt
        $this->editPrice = $targetVersion?->price ?? ($doc->product?->price ?? 0);
        
        $this->loadEditSubjects();
        
        if ($doc->relationLoaded('tags') && $doc->tags->isNotEmpty()) {
            $this->editSelectedTags = $doc->tags->pluck('id')->toArray();
        }
    }


    
    public function setEditTags($tags)
    {
        $this->editSelectedTags = is_array($tags) ? $tags : [];
    }

    public function loadEditSubjects()
    {
        $this->editSubjects = $this->getSubjectsByCategory($this->editCategoryId);
    }

    public function approve()
    {
        $doc = Document::with(['pendingVersion', 'product'])->find($this->documentId);
        if (!$doc) return;

        $pendingVersion = $doc->pendingVersion;

        if (!$pendingVersion) {
            session()->flash('error', 'Không tìm thấy phiên bản đang chờ duyệt!');
            return redirect()->route('admin.documents.index');
        }

        // Mark this version as approved
        $pendingVersion->update([
            'status'      => 'approved',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        // Merge version data into the main document record (identity fields only)
        $doc->update([
            'status'             => 'approved',
            'current_version_id' => $pendingVersion->id,
        ]);

        // Handle product based on version price
        if ($pendingVersion->price > 0) {
            \Modules\Payment\Models\Product::updateOrCreate(
                ['document_id' => $doc->id],
                ['name' => $pendingVersion->title, 'price' => $pendingVersion->price, 'is_active' => true]
            );
        } else {
            \Modules\Payment\Models\Product::where('document_id', $doc->id)->delete();
        }

        session()->flash('success', 'Phê duyệt tài liệu thành công.');

        return redirect()->route('admin.documents.index');
    }

    public function openRejectionModal()
    {
        $this->rejectionReason = '';
        $this->dispatch('open-modal', 'reject-detail-modal');
    }

    public function confirmRejection()
    {
        $this->validate([
            'rejectionReason' => 'required|string|min:10|max:500',
        ], [
            'rejectionReason.min' => 'Lý do từ chối phải có tối thiểu 10 ký tự.',
        ]);

        $doc = Document::with(['pendingVersion'])->find($this->documentId);
        if (!$doc) return;

        $pendingVersion = $doc->pendingVersion;

        if ($pendingVersion) {
            // Reject the pending version
            $pendingVersion->update([
                'status'          => 'rejected',
                'rejected_reason' => $this->rejectionReason,
                'reviewed_by'     => Auth::id(),
                'reviewed_at'     => now(),
            ]);

            // If this is version 1 (first submission), also reject the document itself
            if ($pendingVersion->version_number === 1 && $doc->status === 'pending') {
                $doc->update(['status' => 'rejected']);
                $this->dispatch('close-modal', 'reject-detail-modal');
                session()->flash('success', 'Từ chối phê duyệt tài liệu thành công.');
            } else {
                // Edit version rejected – original stays approved
                $this->dispatch('close-modal', 'reject-detail-modal');
                session()->flash('success', 'Đã từ chối bản chỉnh sửa. Tài liệu gốc vẫn còn.');
            }
        } else {
            // No pending version found - reject doc directly
            $doc->update(['status' => 'rejected']);
            $this->dispatch('close-modal', 'reject-detail-modal');
            session()->flash('success', 'Từ chối phê duyệt tài liệu thành công.');
        }

        return redirect()->route('admin.documents.index');
    }

    public function downloadOriginal()
    {
        $doc = Document::find($this->documentId);
        if (!$doc) return;

        $filePath = $doc->file_original_path;
        
        // Get actual extension from original file path (not from file_type field)
        $originalExt = pathinfo($filePath, PATHINFO_EXTENSION);
        $fileName = $doc->slug . '.' . ($originalExt ?: 'pdf');

        // R2 path — download from R2 and stream with correct filename
        if ($filePath && !str_starts_with($filePath, 'documents/') && !str_starts_with($filePath, 'http')) {
            try {
                $tempPath = storage_path('app/temp/' . uniqid('download_') . '.' . ($doc->file_type ?? 'pdf'));
                $dir = dirname($tempPath);
                if (!is_dir($dir)) mkdir($dir, 0755, true);
                
                $contents = Storage::disk('r2')->get($filePath);
                file_put_contents($tempPath, $contents);
                
                return response()->download($tempPath, $fileName)->deleteFileAfterSend(true);
            } catch (\Exception $e) {
                session()->flash('error', 'Không thể tải file: ' . $e->getMessage());
                return redirect()->back();
            }
        }

        // Local path (legacy)
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            return Storage::disk('public')->download($filePath, $fileName);
        }

        // Full URL
        if ($filePath && str_starts_with($filePath, 'http')) {
            return redirect()->away($filePath);
        }

        return response()->streamDownload(function () use ($doc) {
            echo "Nội dung gốc tài liệu kiểm duyệt: " . $doc->title . "\n";
        }, $fileName);
    }
    
    public function showHistory()
    {
        $doc = Document::find($this->documentId);
        if (!$doc) {
            $this->submissionHistory = collect();
            $this->showHistoryModal  = true;
            return;
        }

        // Load all versions with their submitter and reviewer
        $versions = DocumentVersion::where('document_id', $doc->id)
            ->with(['submittedByUser', 'reviewedByUser'])
            ->orderBy('version_number', 'asc')
            ->get();

        // Build a timeline from the versions
        $timeline = collect();
        foreach ($versions as $ver) {
            $timeline->push((object)[
                'type'             => 'submission',
                'version_number'   => $ver->version_number,
                'timestamp'        => $ver->submitted_at,
                'user'             => $ver->submittedByUser,
                'status'           => null,
                'details'          => null,
            ]);

            if ($ver->reviewed_at) {
                $timeline->push((object)[
                    'type'           => 'review',
                    'version_number' => $ver->version_number,
                    'timestamp'      => $ver->reviewed_at,
                    'user'           => $ver->reviewedByUser,
                    'status'         => $ver->status,
                    'details'        => $ver->rejected_reason,
                ]);
            }
        }

        $this->submissionHistory = $timeline->sortBy('timestamp')->values();
        $this->showHistoryModal  = true;
    }
    
    public function closeHistoryModal()
    {
        $this->showHistoryModal = false;
        $this->submissionHistory = [];
    }
    
    public function editDocument()
    {
        $this->editMode = true;
    }
    
    public function saveChanges()
    {
        $this->validate();

        $doc = Document::with(['currentVersion'])->find($this->documentId);
        if (!$doc) return;

        $currentVersion = $doc->currentVersion;

        $year  = now()->format('Y');
        $month = now()->format('m');

        // Handle editFile upload if provided
        $originalR2Path = null;
        $originalExt = null;
        if ($this->editFile) {
            $originalExt  = strtolower($this->editFile->getClientOriginalExtension());
            $originalUuid = \Illuminate\Support\Str::uuid();
            $originalR2Path = "originals/resources/{$year}/{$month}/{$originalUuid}.{$originalExt}";
            Storage::disk('r2')->put($originalR2Path, file_get_contents($this->editFile->getRealPath()));
        }

        // Handle editThumbnail upload if provided
        $thumbnailR2Path = null;
        if ($this->editThumbnail) {
            $thumbExt  = strtolower($this->editThumbnail->getClientOriginalExtension());
            $thumbUuid = \Illuminate\Support\Str::uuid();
            $thumbnailR2Path = "thumbnails/resources/{$year}/{$month}/{$thumbUuid}.{$thumbExt}";
                Storage::disk('r2')->put($thumbnailR2Path, file_get_contents($this->editThumbnail->getRealPath()));
            }

            // Handle editGalleryFiles upload if provided
            $galleryImagesData = [];
            if (!empty($this->editGalleryFiles)) {
                foreach ($this->editGalleryFiles as $index => $galleryFile) {
                    if ($galleryFile && !in_array($index, $this->excludedEditGalleryIndices)) {
                        $galleryExt  = strtolower($galleryFile->getClientOriginalExtension());
                        $galleryUuid = \Illuminate\Support\Str::uuid();
                        $galleryR2Path = "thumbnails/gallery/{$year}/{$month}/{$galleryUuid}.{$galleryExt}";
                        Storage::disk('r2')->put($galleryR2Path, file_get_contents($galleryFile->getRealPath()));

                        $galleryImagesData[] = [
                            'path'    => $galleryR2Path,
                            'order'   => $index + 1,
                            'caption' => '',
                        ];
                    }
                }
            } else {
                $galleryImagesData = $currentVersion?->gallery_images ?? [];
            }

            // Create a new version instead of overwriting
        $nextVersionNumber = ($doc->versions()->max('version_number') ?? 0) + 1;

        $finalOriginalPath = $this->editFile ? $originalR2Path : $currentVersion?->file_original_path;
        $finalFileType     = $this->editFile ? $originalExt : $currentVersion?->file_type;
        $finalFileSize     = $this->editFile ? $this->editFile->getSize() : $currentVersion?->file_size;
        $finalThumbnail    = $this->editThumbnail ? $thumbnailR2Path : $currentVersion?->thumbnail;

        $adminId = Auth::id()
            ?? \Modules\Auth\Models\User::whereHas('roles', fn($q) => $q->where('name', 'admin'))->first()?->id;

        $newVersion = \Modules\Document\Models\DocumentVersion::create([
            'document_id'           => $doc->id,
            'version_number'        => $nextVersionNumber,
            'title'                 => $this->editTitle,
            'short_description'     => $this->editShortDescription,
            'description'           => $this->editDescription,
            'category_id'           => $this->editCategoryId,
            'subject_id'            => $this->editSubjectId,
            'thumbnail'             => $finalThumbnail,
            'gallery_images'        => $galleryImagesData,
            'file_original_path'    => $finalOriginalPath,
            'file_watermarked_path' => $this->editFile ? null : $currentVersion?->file_watermarked_path,
            'preview_file_path'     => $this->editFile ? null : $currentVersion?->preview_file_path,
            'file_type'             => $finalFileType,
            'file_size'             => $finalFileSize,
            'visibility'            => $this->editVisibility,
            'is_downloadable'       => $this->editIsDownloadable,
            'watermark_status'      => $this->editFile ? (in_array($originalExt, ['pdf', 'docx']) ? 'pending' : 'success') : $currentVersion?->watermark_status,
            'price'                 => (int)$this->editPrice,
            'status'                => 'approved', // Admin edits are auto-approved
            'submitted_by'          => $adminId,
            'submitted_at'          => now(),
            'reviewed_by'           => $adminId,
            'reviewed_at'           => now(),
        ]);

        // Link current version to document & ensure approved status
        $doc->update([
            'current_version_id' => $newVersion->id,
            'status'             => 'approved',
        ]);

        // Clear size cache if new file uploaded
        if ($this->editFile) {
            \Illuminate\Support\Facades\Cache::forget('file_size_' . md5($originalR2Path));

            // Dispatch watermark job if PDF/Docx
            if (in_array($originalExt, ['pdf', 'docx'])) {
                \Modules\Document\Jobs\ProcessWatermarkJob::dispatch($doc->id);
            } else {
                $newVersion->update([
                    'watermark_status'      => 'success',
                    'file_watermarked_path' => $originalR2Path,
                ]);
            }
        }

        // Optionally regenerate slug on documents table
        if ($doc->title !== $this->editTitle) {
            $baseSlug = \Illuminate\Support\Str::slug($this->editTitle);
            $slug = $baseSlug;
            $count = 1;
            while (Document::where('slug', $slug)->where('id', '!=', $doc->id)->exists()) {
                $slug = $baseSlug . '-' . $count;
                $count++;
            }
            $doc->update(['slug' => $slug]);
        }

        // Update price
        if ((int)$this->editPrice > 0) {
            \Modules\Payment\Models\Product::updateOrCreate(
                ['document_id' => $doc->id],
                [
                    'name'      => $this->editTitle,
                    'price'     => (int)$this->editPrice,
                    'is_active' => true,
                ]
            );
        } else {
            \Modules\Payment\Models\Product::where('document_id', $doc->id)->update([
                'price'      => 0,
                'sale_price' => null,
                'is_active'  => false,
            ]);
        }

        // Sync tags (predefined + custom)
        $tagIds = $this->editSelectedTags ?? [];
        if (!empty($this->editCustomTagsInput)) {
            $customTagNames = array_map('trim', explode(',', $this->editCustomTagsInput));
            foreach ($customTagNames as $tagName) {
                if (empty($tagName)) continue;
                $tag = \App\Models\Tag::firstOrCreate(
                    ['slug' => \Illuminate\Support\Str::slug($tagName)],
                    ['name' => $tagName]
                );
                $tagIds[] = $tag->id;
            }
        }
        $doc->tags()->sync($tagIds);

        $this->editFile = null;
        $this->editThumbnail = null;
        $this->editGalleryFiles = [];
        $this->editMode = false;
    }

    
    public function cancelEdit()
    {
        $doc = Document::with('tags')->find($this->documentId);
        if ($doc) {
            $this->editTitle = $doc->title;
            $this->editCategoryId = $doc->category_id;
            $this->editSubjectId = $doc->subject_id;
            $this->editShortDescription = $doc->short_description;
            $this->editDescription = $doc->description;
            $this->editVisibility = $doc->visibility;
            $this->editIsDownloadable = $doc->is_downloadable;
            $this->editPrice = $doc->product?->price ?? 0;
            
            $this->editSelectedTags = [];
            $this->editCustomTagsInput = '';
            if ($doc->tags->isNotEmpty()) {
                $this->editSelectedTags = $doc->tags->pluck('id')->toArray();
            }
            
            $this->loadEditSubjects();
        }
        $this->editFile = null;
        $this->editThumbnail = null;
        $this->editGalleryFiles = [];
        $this->editMode = false;

        $this->dispatch('tags-updated', ['tags' => $this->editSelectedTags]);
    }

    public function updated($propertyName)
    {
        if ($propertyName === 'editCategoryId') {
            $this->editSubjectId = null;
            $this->loadEditSubjects();
        }

        if ($propertyName === 'editPrice' && (int)$this->editPrice === 0) {
            $this->resetValidation('editPrice');
        }
    }

    public function removeSelectedFile()
    {
        $this->editFile = null;
        $this->resetValidation('editFile');
    }

    public function removeSelectedThumbnail()
    {
        $this->editThumbnail = null;
        $this->resetValidation('editThumbnail');
    }

    public function removeEditGalleryImage($index)
    {
        $this->excludedEditGalleryIndices[] = $index;
    }
    
    protected function getFileSizeFromStorage($path)
    {
        if (!$path) return null;
        
        $cacheKey = 'file_size_' . md5($path);
        return \Illuminate\Support\Facades\Cache::rememberForever($cacheKey, function() use ($path) {
            try {
                if (Storage::disk('r2')->exists($path)) {
                    return Storage::disk('r2')->size($path);
                }
            } catch (\Exception $e) {
                \Log::warning("Failed to get file size from storage", ['path' => $path, 'error' => $e->getMessage()]);
            }
            return null;
        });
    }
    
    protected function formatFileSize($bytes)
    {
        if (!$bytes) return null;
        
        $mb = $bytes / 1024 / 1024;
        return number_format($mb, 2) . ' MB';
    }

    public function hideReview($reviewId)
    {
        $review = DocumentReview::where('id', $reviewId)
            ->where('document_id', $this->documentId)
            ->first();
        if ($review) {
            $review->update(['status' => 'hidden']);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Đã ẩn đánh giá.']);
        }
    }

    public function showReview($reviewId)
    {
        $review = DocumentReview::where('id', $reviewId)
            ->where('document_id', $this->documentId)
            ->first();
        if ($review) {
            $review->update(['status' => 'visible']);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Đã hiện đánh giá.']);
        }
    }

    public function hideComment($commentId)
    {
        $comment = DocumentComment::where('id', $commentId)
            ->where('document_id', $this->documentId)
            ->first();
        if ($comment) {
            $comment->update(['status' => 'hidden']);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Đã ẩn bình luận.']);
        }
    }

    public function showComment($commentId)
    {
        $comment = DocumentComment::where('id', $commentId)
            ->where('document_id', $this->documentId)
            ->first();
        if ($comment) {
            $comment->update(['status' => 'visible']);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Đã hiện bình luận.']);
        }
    }

    public function deleteComment($commentId)
    {
        $comment = DocumentComment::where('id', $commentId)
            ->where('document_id', $this->documentId)
            ->first();
        if ($comment) {
            $comment->update(['status' => 'hidden']);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Đã xoá bình luận.']);
        }
    }

    public function render()
    {
        $doc = Document::with([
            'author', 'product', 'tags',
            'currentVersion.category', 'currentVersion.subject', 'currentVersion.reviewedByUser',
            'pendingVersion.category', 'pendingVersion.subject', 'pendingVersion.reviewedByUser',
            'rejectedVersion.category', 'rejectedVersion.subject', 'rejectedVersion.reviewedByUser',
            'versions.category', 'versions.subject', 'versions.reviewedByUser',
        ])->find($this->documentId);

        $categories = Category::orderBy('name')->get();
        $allTags    = \App\Models\Tag::orderBy('name')->get();

        // Build changes array: compare pending version vs current approved version
        $pendingVersion = $doc?->pendingVersion;
        $currentVersion = $doc?->currentVersion;
        $changes = [];

        if ($pendingVersion && $currentVersion && $pendingVersion->version_number > 1) {
            $fields = ['title', 'short_description', 'description', 'visibility', 'is_downloadable'];
            foreach ($fields as $field) {
                if ($pendingVersion->$field !== $currentVersion->$field) {
                    $changes[$field] = ['old' => $currentVersion->$field, 'new' => $pendingVersion->$field];
                }
            }
            if ($pendingVersion->category_id !== $currentVersion->category_id) {
                $changes['category'] = [
                    'old' => optional($currentVersion->category)->name ?? 'N/A',
                    'new' => optional($pendingVersion->category)->name ?? 'N/A',
                ];
            }
            if ($pendingVersion->file_original_path !== $currentVersion->file_original_path) {
                $changes['file'] = ['old' => 'File cũ', 'new' => 'File mới được upload'];
            }
            if ($pendingVersion->price != $currentVersion->price) {
                $changes['price'] = [
                    'old' => $currentVersion->price > 0 ? number_format($currentVersion->price) . ' VND' : 'Miễn phí',
                    'new' => $pendingVersion->price > 0 ? number_format($pendingVersion->price) . ' VND' : 'Miễn phí',
                ];
            }
        }

        $activeVersion = $pendingVersion ?? $currentVersion ?? $doc->latestVersion;

        $previewFileSize     = $activeVersion?->preview_file_path     ? $this->getFileSizeFromStorage($activeVersion->preview_file_path)     : null;
        $watermarkedFileSize = $activeVersion?->file_watermarked_path ? $this->getFileSizeFromStorage($activeVersion->file_watermarked_path) : null;

        $reviews = DocumentReview::where('document_id', $doc->id)
            ->with('user')->orderBy('created_at', 'desc')->limit(10)->get();

        $comments = DocumentComment::where('document_id', $doc->id)
            ->whereNull('parent_id')->with(['user', 'replies.user'])
            ->orderBy('created_at', 'desc')->limit(10)->get();

        $totalReviews    = DocumentReview::where('document_id', $doc->id)->count();
        $visibleReviews  = DocumentReview::where('document_id', $doc->id)->where('status', 'visible')->count();
        $totalComments   = DocumentComment::where('document_id', $doc->id)->count();
        $visibleComments = DocumentComment::where('document_id', $doc->id)->where('status', 'visible')->count();
        $avgRating       = DocumentReview::where('document_id', $doc->id)->where('status', 'visible')->avg('rating') ?? 0;

        $originalFileExists = false;
        $previewFileExists = false;
        $watermarkedFileExists = false;

        if ($activeVersion) {
            if ($activeVersion->file_original_path) {
                $originalFileExists = $this->checkFileExists($activeVersion->file_original_path);
            }
            if ($activeVersion->preview_file_path) {
                $previewFileExists = $this->checkFileExists($activeVersion->preview_file_path);
            }
            if ($activeVersion->file_watermarked_path) {
                $watermarkedFileExists = $this->checkFileExists($activeVersion->file_watermarked_path);
            }
        }

        $zipFilesData = [];
        if ($activeVersion && strtolower($activeVersion->file_type) === 'zip') {
            $zipFilesData = \Illuminate\Support\Facades\Cache::rememberForever('zip_structure_' . $activeVersion->id, function() use ($activeVersion) {
                $zipPath = $activeVersion->file_original_path;
                $localZip = null;
                $zipFiles = [];

                // Local path
                $localPath = storage_path('app/public/' . $zipPath);
                if (file_exists($localPath)) {
                    $localZip = $localPath;
                }

                // R2 path
                if (!$localZip && $zipPath && Storage::disk('r2')->exists($zipPath)) {
                    $tempZip = storage_path('app/temp/' . uniqid('zip_') . '.zip');
                    $dir = dirname($tempZip);
                    if (!is_dir($dir)) mkdir($dir, 0755, true);
                    file_put_contents($tempZip, Storage::disk('r2')->get($zipPath));
                    $localZip = $tempZip;
                }

                if ($localZip) {
                    $zip = new \ZipArchive();
                    if ($zip->open($localZip) === TRUE) {
                        for ($i = 0; $i < $zip->numFiles; $i++) {
                            $name = $zip->getNameIndex($i);
                            if (substr($name, -1) === '/') continue;

                            $skipExts = ['png', 'jpg', 'jpeg', 'gif', 'svg', 'ico', 'webp', 'bmp', 'tiff', 'tif',
                                'ttf', 'woff', 'woff2', 'eot', 'otf',
                                'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx',
                                'zip', 'rar', '7z', 'tar', 'gz',
                                'mp3', 'mp4', 'avi', 'mov', 'wav', 'ogg',
                                'exe', 'dll', 'so', 'dylib', 'bin', 'obj'];
                            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                            if (in_array($ext, $skipExts)) continue;

                            $content = $zip->getFromIndex($i);
                            
                            if (!mb_check_encoding($content, 'UTF-8')) {
                                $detected = mb_detect_encoding($content, ['UTF-8', 'ISO-8859-1', 'Windows-1252', 'ASCII'], true);
                                if ($detected && $detected !== 'UTF-8') {
                                    $content = mb_convert_encoding($content, 'UTF-8', $detected);
                                } else {
                                    $zipFiles[$name] = [
                                        'isDir' => false,
                                        'content' => '[Binary file - không thể preview]'
                                    ];
                                    continue;
                                }
                            }
                            
                            $content = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $content);
                            
                            $lines = explode("\n", $content);
                            if (count($lines) > 50) {
                                $content = implode("\n", array_slice($lines, 0, 50));
                                $content .= "\n\n// ... [Hiển thị 50 dòng đầu, vui lòng tải xuống để xem đầy đủ]";
                            } elseif (strlen($content) > 10000) {
                                $content = substr($content, 0, 10000);
                                $content .= "\n\n// ... [Nội dung đã được cắt ngắn, vui lòng tải xuống để xem đầy đủ]";
                            }

                            $zipFiles[$name] = [
                                'isDir' => false,
                                'content' => $content
                            ];
                        }
                        $zip->close();
                    }
                    if (isset($tempZip) && file_exists($tempZip)) @unlink($tempZip);
                }
                return $zipFiles;
            });
        }
        $this->zipFiles = $zipFilesData;

        return view('document::livewire.admin.document-detail', [
            'doc'                 => $doc,
            'pendingVersion'      => $pendingVersion,
            'currentVersion'      => $currentVersion,
            'activeVersion'       => $activeVersion,
            'changes'             => $changes,
            'categories'          => $categories,
            'subjectsForEdit'     => $this->editSubjects,
            'allTags'             => $allTags,
            'originalFileSize'    => $this->formatFileSize($activeVersion?->file_size),
            'previewFileSize'     => $this->formatFileSize($previewFileSize),
            'watermarkedFileSize' => $this->formatFileSize($watermarkedFileSize),
            'originalFileExists'  => $originalFileExists,
            'previewFileExists'   => $previewFileExists,
            'watermarkedFileExists' => $watermarkedFileExists,
            'reviews'             => $reviews,
            'comments'            => $comments,
            'totalReviews'        => $totalReviews,
            'visibleReviews'      => $visibleReviews,
            'totalComments'       => $totalComments,
            'visibleComments'     => $visibleComments,
            'avgRating'           => round($avgRating, 1),
            'zipFiles'            => $this->zipFiles,
        ])->layout('layouts.admin', [
            'pageTitle'  => 'Chi tiết tài liệu',
            'breadcrumb' => new \Illuminate\Support\HtmlString('<span class="mx-2">/</span> Tài liệu <span class="mx-2">/</span> Chi tiết'),
        ]);
    }

    protected function checkFileExists($path)
    {
        if (!$path) return false;
        
        $cacheKey = 'file_exists_' . md5($path);
        return \Illuminate\Support\Facades\Cache::rememberForever($cacheKey, function() use ($path) {
            try {
                if (Storage::disk('public')->exists($path)) {
                    return true;
                }
                if (Storage::disk('r2')->exists($path)) {
                    return true;
                }
            } catch (\Exception $e) {
                \Log::warning("Failed to check file existence", ['path' => $path, 'error' => $e->getMessage()]);
            }
            return false;
        });
    }
}
