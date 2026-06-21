<?php

namespace Modules\Document\Http\Livewire\Admin;

use Livewire\Component;
use Modules\Document\Models\Document;
use Modules\Document\Models\DocumentDownload;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentDetail extends Component
{
    public $documentId;
    public $rejectionReason = '';
    public $from = 'moderation'; // Nguồn truy cập: 'moderation' hoặc 'list'

    public function mount($id)
    {
        $this->documentId = $id;
        $this->from = request()->query('from', 'moderation');
        $doc = Document::find($id);
        if (!$doc) {
            abort(404);
        }
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

    public function render()
    {
        $doc = Document::with(['author', 'category', 'product', 'reviewer'])->find($this->documentId);

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

        return view('document::livewire.admin.document-detail', [
            'doc' => $doc,
            'original' => $original,
            'changes' => $changes
        ])->layout('layouts.admin', [
            'pageTitle' => 'Chi tiết tài liệu',
            'breadcrumb' => new \Illuminate\Support\HtmlString('<span class="mx-2">/</span> Kiểm duyệt <span class="mx-2">/</span> Chi tiết')
        ]);
    }
}
