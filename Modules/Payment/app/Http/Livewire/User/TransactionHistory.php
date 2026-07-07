<?php

namespace Modules\Payment\Http\Livewire\User;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Payment\Models\Order;

class TransactionHistory extends Component
{
    use WithPagination;

    public $orderType = '';

    public $paymentStatus = '';

    public $selectedOrder = null;

    protected $queryString = [
        'orderType' => ['except' => ''],
        'paymentStatus' => ['except' => ''],
    ];

    public function updatingOrderType()
    {
        $this->resetPage();
    }

    public function updatingPaymentStatus()
    {
        $this->resetPage();
    }

    public function viewOrder($orderId)
    {
        $this->selectedOrder = Order::with(['items.document', 'payments'])
            ->where('user_id', Auth::id())
            ->findOrFail($orderId);
    }

    public function closeModal()
    {
        $this->selectedOrder = null;
    }

    public function render()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with(['items.document', 'payments'])
            ->when($this->orderType, function ($query) {
                $query->where('order_type', $this->orderType);
            })
            ->when($this->paymentStatus, function ($query) {
                $query->where('payment_status', $this->paymentStatus);
            })
            ->latest()
            ->paginate(15);

        return view('payment::livewire.user.transaction-history', [
            'orders' => $orders,
        ])->layout('layouts.user');
    }
}
