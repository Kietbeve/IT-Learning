<?php

namespace Modules\Document\Http\Livewire\User;

use Livewire\Component;
use Livewire\WithPagination;
use Modules\Document\Models\Document;
use Modules\Document\Models\DocumentDownload;
use Modules\Payment\Models\DocumentAccess;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PurchasedDocuments extends Component
{
    use WithPagination;

    public function download($documentId)
    {
        $userId = Auth::id();
        if (!$userId) return;

        $access = DocumentAccess::where('user_id', $userId)
            ->where('document_id', $documentId)
            ->where('access_type', 'purchased')
            ->first();

        if (!$access) {
            $this->dispatch('notify', ['type' => 'info', 'message' => 'Bạn không có quyền tải tài liệu này.']);
            return;
        }

        $doc = Document::find($documentId);
        if (!$doc) return;

        $token = \Illuminate\Support\Str::random(40);

        \Illuminate\Support\Facades\Cache::put("doc_download_{$token}", [
            'document_id' => $doc->id,
            'user_id' => $userId,
            'order_item_id' => $access->order_item_id,
            'ip' => request()->ip()
        ], now()->addMinutes(30));

        $this->dispatch('notify', ['type' => 'success', 'message' => 'Liên kết tải xuống đã được tạo. Đang tải...']);

        return redirect()->route('documents.download', ['token' => $token]);
    }

    public function render()
    {
        $userId = Auth::id();

        $accesses = DocumentAccess::where('user_id', $userId)
            ->where('access_type', 'purchased')
            ->with(['document.author', 'document.currentVersion.category'])
            ->paginate(15);

        return view('document::livewire.user.purchased-documents', [
            'accesses' => $accesses
        ])->layout('layouts.user');
    }
}
