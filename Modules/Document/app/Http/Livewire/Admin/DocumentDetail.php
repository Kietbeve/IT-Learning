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

        $doc->update([
            'status' => 'approved',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'published_at' => now(),
        ]);

        session()->flash('success', 'Phê duyệt tài liệu thành công.');
        
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
            $doc->update([
                'status' => 'rejected',
                'rejected_reason' => $this->rejectionReason,
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
            ]);

            $this->dispatch('close-modal', 'reject-detail-modal');
            session()->flash('success', 'Từ chối phê duyệt tài liệu thành công.');
            
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
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            return Storage::disk('public')->download($filePath);
        } elseif ($filePath && Storage::exists($filePath)) {
            return Storage::download($filePath);
        } else {
            return response()->streamDownload(function () use ($doc) {
                echo "Nội dung gốc tài liệu kiểm duyệt: " . $doc->title . "\n";
            }, 'original_' . $doc->slug . '.' . ($doc->file_type ?? 'pdf'));
        }
    }

    public function render()
    {
        $doc = Document::with(['author', 'category', 'product', 'reviewer'])->find($this->documentId);

        return view('document::livewire.admin.document-detail', [
            'doc' => $doc
        ])->layout('layouts.admin', [
            'pageTitle' => 'Chi tiết tài liệu',
            'breadcrumb' => new \Illuminate\Support\HtmlString('<span class="mx-2">/</span> Kiểm duyệt <span class="mx-2">/</span> Chi tiết')
        ]);
    }
}
