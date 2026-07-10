<?php

namespace Modules\Payment\Http\Livewire\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Document\Models\Document;
use Modules\Payment\Models\OrderItem;

class Purchases extends Component
{
    use WithPagination;

    public function render()
    {
        $user = Auth::user();

        // Lấy danh sách document_id đã mua (mỗi tài liệu chỉ hiện 1 lần)
        $purchasedDocuments = OrderItem::whereHas('order', function ($query) use ($user) {
            $query->where('user_id', $user->id)
                ->where('payment_status', 'paid');
        })
            ->whereNotNull('document_id')
            ->select('document_id', DB::raw('MAX(id) as id'), DB::raw('MAX(created_at) as created_at'))
            ->groupBy('document_id')
            ->latest('created_at')
            ->paginate(12);

        // Eager load document cho kết quả đã group
        $documentIds = collect($purchasedDocuments->items())->pluck('document_id')->unique();
        $documents = Document::with(['currentVersion.category'])
            ->whereIn('id', $documentIds)
            ->get()
            ->keyBy('id');

        return view('payment::livewire.user.purchases', [
            'purchasedDocuments' => $purchasedDocuments,
            'documents' => $documents,
        ])->layout('layouts.user');
    }
}
