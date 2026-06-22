<?php

namespace Modules\Payment\Http\Livewire\User;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Payment\Models\DocumentAccess;
use WireUi\Traits\WireUiActions;

class Purchases extends Component
{
    use WithPagination, WireUiActions;

    public function mount()
    {
    }

    public function render()
    {
        $user = Auth::user();

        $purchasedDocuments = DocumentAccess::with(['document'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(12);

        return view('payment::livewire.user.purchases', [
            'purchasedDocuments' => $purchasedDocuments
        ])->layout('layouts.user');
    }

    public function downloadDocument($documentId)
    {
        $userId = Auth::id();
        if (!$userId) return;

        $access = DocumentAccess::where('user_id', $userId)
            ->where('document_id', $documentId)
            ->where('access_type', 'purchased')
            ->first();

        if (!$access) {
            $this->notification()->error(
                title: 'Không thể tải',
                description: 'Bạn không có quyền tải tài liệu này.'
            );
            return;
        }

        $doc = \Modules\Document\Models\Document::find($documentId);
        if (!$doc) return;

        $token = \Illuminate\Support\Str::random(40);

        \Illuminate\Support\Facades\Cache::put("doc_download_{$token}", [
            'document_id' => $doc->id,
            'user_id' => $userId,
            'order_item_id' => $access->order_item_id,
            'ip' => request()->ip()
        ], now()->addMinutes(30));

        $this->notification()->success(
            title: 'Thành công',
            description: 'Liên kết tải xuống đã được tạo.'
        );

        return redirect()->route('documents.download', ['token' => $token]);
    }
}
