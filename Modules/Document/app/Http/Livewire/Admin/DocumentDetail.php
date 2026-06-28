<?php

namespace Modules\Document\Http\Livewire\Admin;

use Livewire\Component;
use Modules\Document\Models\Document;
use Modules\Document\Models\DocumentRelationship;
use Modules\Document\Models\DocumentDownload;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentDetail extends Component
{
    public $documentId;
    public $rejectionReason = '';
    public $from = 'moderation';
    
    public $showHistoryModal = false;
    public $submissionHistory = [];
    
    public $editMode = false;
    public $editTitle;
    public $editCategoryId;
    public $editShortDescription;
    public $editDescription;
    public $editVisibility;
    public $editIsDownloadable;
    public $editPrice;

    public function mount($id)
    {
        $this->documentId = $id;
        $this->from = request()->query('from', 'moderation');
        $doc = Document::find($id);
        if (!$doc) {
            abort(404);
        }
        
        $this->editTitle = $doc->title;
        $this->editCategoryId = $doc->category_id;
        $this->editShortDescription = $doc->short_description;
        $this->editDescription = $doc->description;
        $this->editVisibility = $doc->visibility;
        $this->editIsDownloadable = $doc->is_downloadable;
        $this->editPrice = $doc->product?->price ?? 0;
    }

    public function approve()
    {
        $doc = Document::find($this->documentId);
        if (!$doc) return;

        if ($doc->parent_document_id) {
            // This is a draft - merge into original document
            $original = Document::find($doc->parent_document_id);
            
            if (!$original) {
                session()->flash('error', 'Không tìm thấy tài liệu gốc!');
                return redirect()->route('admin.moderation.documents.index');
            }

            // Merge all changes from draft to original
            $original->update([
                'title' => $doc->title,
                // 'slug' => keep original slug unchanged
                'category_id' => $doc->category_id,
                'description' => $doc->description,
                'short_description' => $doc->short_description,
                'thumbnail' => $doc->thumbnail,
                'file_original_path' => $doc->file_original_path,
                'file_watermarked_path' => $doc->file_watermarked_path,
                'preview_file_path' => $doc->preview_file_path,
                'file_type' => $doc->file_type,
                'file_size' => $doc->file_size,
                'visibility' => $doc->visibility,
                'is_downloadable' => $doc->is_downloadable,
                'watermark_status' => $doc->watermark_status,
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
                'published_at' => now(),
            ]);

            // Handle product
            $draftProduct = $doc->product;
            if ($draftProduct) {
                \Modules\Payment\Models\Product::updateOrCreate(
                    ['document_id' => $original->id],
                    [
                        'name' => $draftProduct->name,
                        'price' => $draftProduct->price,
                        'is_active' => $draftProduct->is_active,
                    ]
                );
            } else {
                \Modules\Payment\Models\Product::where('document_id', $original->id)->delete();
            }

            // Delete ALL other drafts with same parent (clean up rejected/pending drafts)
            Document::where('parent_document_id', $original->id)
                ->where('id', '!=', $doc->id)
                ->forceDelete();

            // Delete the approved draft permanently (already merged into original)
            $doc->forceDelete();

            session()->flash('success', 'Đã merge draft vào tài liệu #' . $original->id);
        } else {
            // Normal document - just approve
            $doc->update([
                'status' => 'approved',
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
                'published_at' => now(),
            ]);

            session()->flash('success', 'Phê duyệt tài liệu thành công.');
        }
        
        if ($this->from === 'list') {
            return redirect()->route('admin.documents.index');
        }
        return redirect()->route('admin.moderation.documents.index');
    }

    public function openRejectionModal()
    {
        $this->rejectionReason = '';
        $this->dispatch('open-modal', 'reject-detail-modal');
    }

    public function confirmRejection()
    {
        $this->validate([
            'rejectionReason' => 'required|string|min:10|max:500'
        ], [
            'rejectionReason.min' => 'Lý do từ chối phải có tối thiểu 10 ký tự.'
        ]);

        $doc = Document::find($this->documentId);
        if ($doc) {
            if ($doc->parent_document_id) {
                // This is a draft - reject it (keep original, contributor can see reason)
                $doc->update([
                    'status' => 'rejected',
                    'rejected_reason' => $this->rejectionReason,
                    'reviewed_by' => Auth::id(),
                    'reviewed_at' => now(),
                ]);
                
                $this->dispatch('close-modal', 'reject-detail-modal');
                session()->flash('success', 'Đã từ chối bản chỉnh sửa. Tài liệu gốc vẫn còn.');
            } else {
                // Normal document - reject it
                $doc->update([
                    'status' => 'rejected',
                    'rejected_reason' => $this->rejectionReason,
                    'reviewed_by' => Auth::id(),
                    'reviewed_at' => now(),
                ]);

                $this->dispatch('close-modal', 'reject-detail-modal');
                session()->flash('success', 'Từ chối phê duyệt tài liệu thành công.');
            }
            
            if ($this->from === 'list') {
                return redirect()->route('admin.documents.index');
            }
            return redirect()->route('admin.moderation.documents.index');
        }
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
        $doc = Document::withTrashed()->find($this->documentId);
        if (!$doc) {
            $this->submissionHistory = collect();
            $this->showHistoryModal = true;
            return;
        }
        
        // Walk UP to find root document
        $root = $doc;
        while ($root->parent_document_id) {
            $parent = Document::withTrashed()->find($root->parent_document_id);
            if (!$parent) break;
            $root = $parent;
        }
        
        // Collect all document IDs in the chain
        $documentIds = [$root->id];
        $children = Document::withTrashed()
            ->where('parent_document_id', $root->id)
            ->pluck('id')
            ->toArray();
        $documentIds = array_merge($documentIds, $children);
        
        // Query all relationships for documents in the chain
        $relationships = DocumentRelationship::whereIn('draft_document_id', $documentIds)
            ->with(['submittedByUser', 'reviewedByUser'])
            ->orderBy('submitted_at', 'asc')
            ->get();
        
        // Transform into separate events
        $timeline = collect();
        foreach ($relationships as $rel) {
            $timeline->push((object)[
                'type' => 'submission',
                'timestamp' => $rel->submitted_at,
                'user' => $rel->submittedByUser,
                'status' => null,
            ]);
            
            if ($rel->reviewed_at) {
                $timeline->push((object)[
                    'type' => 'review',
                    'timestamp' => $rel->reviewed_at,
                    'user' => $rel->reviewedByUser,
                    'status' => $rel->status,
                    'details' => $rel->rejected_reason,
                ]);
            }
        }
        
        $this->submissionHistory = $timeline->sortBy('timestamp')->values();
        $this->showHistoryModal = true;
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
        $this->validate([
            'editTitle' => 'required|string|max:255',
            'editCategoryId' => 'nullable|exists:categories,id',
            'editShortDescription' => 'nullable|string|max:500',
            'editDescription' => 'required|string',
            'editVisibility' => 'required|in:public,private,unlisted',
            'editIsDownloadable' => 'required|boolean',
            'editPrice' => 'nullable|numeric|min:0|max:999999999',
        ]);
        
        $doc = Document::find($this->documentId);
        if ($doc) {
            $doc->update([
                'title' => $this->editTitle,
                'category_id' => $this->editCategoryId,
                'short_description' => $this->editShortDescription,
                'description' => $this->editDescription,
                'visibility' => $this->editVisibility,
                'is_downloadable' => $this->editIsDownloadable,
            ]);
            
            // Update price
            if ((int)$this->editPrice > 0) {
                \Modules\Payment\Models\Product::updateOrCreate(
                    ['document_id' => $doc->id],
                    [
                        'name' => $doc->title,
                        'price' => (int)$this->editPrice,
                        'is_active' => true,
                    ]
                );
            } else {
                \Modules\Payment\Models\Product::where('document_id', $doc->id)->delete();
            }
            
            $this->editMode = false;
            session()->flash('success', 'Đã cập nhật tài liệu thành công.');
        }
    }
    
    public function cancelEdit()
    {
        $doc = Document::find($this->documentId);
        if ($doc) {
            $this->editTitle = $doc->title;
            $this->editCategoryId = $doc->category_id;
            $this->editShortDescription = $doc->short_description;
            $this->editDescription = $doc->description;
            $this->editVisibility = $doc->visibility;
            $this->editIsDownloadable = $doc->is_downloadable;
            $this->editPrice = $doc->product?->price ?? 0;
        }
        $this->editMode = false;
    }
    
    protected function getFileSizeFromStorage($path)
    {
        if (!$path) return null;
        
        try {
            if (Storage::disk('r2')->exists($path)) {
                return Storage::disk('r2')->size($path);
            }
        } catch (\Exception $e) {
            \Log::warning("Failed to get file size from storage", ['path' => $path, 'error' => $e->getMessage()]);
        }
        
        return null;
    }
    
    protected function formatFileSize($bytes)
    {
        if (!$bytes) return null;
        
        $mb = $bytes / 1024 / 1024;
        return number_format($mb, 2) . ' MB';
    }

    public function render()
    {
        $doc = Document::with(['author', 'category', 'product', 'reviewer'])->find($this->documentId);
        $categories = Category::orderBy('name')->get();

        // Check if this is a draft and build comparison with original
        $original = null;
        $changes = [];
        
        if ($doc && $doc->parent_document_id) {
            $original = Document::with(['category', 'product'])->find($doc->parent_document_id);
            
            if ($original) {
                // Compare fields and build changes array
                if ($doc->title !== $original->title) {
                    $changes['title'] = ['old' => $original->title, 'new' => $doc->title];
                }
                
                if ($doc->short_description !== $original->short_description) {
                    $changes['short_description'] = ['old' => $original->short_description, 'new' => $doc->short_description];
                }
                
                if ($doc->description !== $original->description) {
                    $changes['description'] = ['old' => $original->description, 'new' => $doc->description];
                }
                
                if ($doc->category_id !== $original->category_id) {
                    $changes['category'] = [
                        'old' => $original->category->name ?? 'N/A',
                        'new' => $doc->category->name ?? 'N/A'
                    ];
                }
                
                if ($doc->visibility !== $original->visibility) {
                    $changes['visibility'] = [
                        'old' => $original->visibility === 'public' ? 'Công khai' : 'Riêng tư',
                        'new' => $doc->visibility === 'public' ? 'Công khai' : 'Riêng tư'
                    ];
                }
                
                if ($doc->is_downloadable !== $original->is_downloadable) {
                    $changes['is_downloadable'] = [
                        'old' => $original->is_downloadable ? 'Có' : 'Không',
                        'new' => $doc->is_downloadable ? 'Có' : 'Không'
                    ];
                }
                
                // Compare price
                $oldPrice = $original->product ? $original->product->price : 0;
                $newPrice = $doc->product ? $doc->product->price : 0;
                if ($oldPrice != $newPrice) {
                    $changes['price'] = [
                        'old' => $oldPrice > 0 ? number_format($oldPrice) . ' VND' : 'Miễn phí',
                        'new' => $newPrice > 0 ? number_format($newPrice) . ' VND' : 'Miễn phí'
                    ];
                }
                
                // Compare file
                if ($doc->file_original_path !== $original->file_original_path) {
                    $changes['file'] = [
                        'old' => 'File gốc',
                        'new' => 'File mới được upload'
                    ];
                }
            }
        }

        $previewFileSize = $doc->preview_file_path ? $this->getFileSizeFromStorage($doc->preview_file_path) : null;
        $watermarkedFileSize = $doc->file_watermarked_path ? $this->getFileSizeFromStorage($doc->file_watermarked_path) : null;
        
        return view('document::livewire.admin.document-detail', [
            'doc' => $doc,
            'original' => $original,
            'changes' => $changes,
            'categories' => $categories,
            'originalFileSize' => $this->formatFileSize($doc->file_size),
            'previewFileSize' => $this->formatFileSize($previewFileSize),
            'watermarkedFileSize' => $this->formatFileSize($watermarkedFileSize),
        ])->layout('layouts.admin', [
            'pageTitle' => 'Chi tiết tài liệu',
            'breadcrumb' => new \Illuminate\Support\HtmlString('<span class="mx-2">/</span> Kiểm duyệt <span class="mx-2">/</span> Chi tiết')
        ]);
    }
}
