<?php

namespace Modules\Payment\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Modules\Payment\Models\Order;
use Modules\Payment\Models\OrderItem;

class OrderManagement extends Component
{
    use WithPagination;

    public $search = '';

    public $statusFilter = 'all';

    public $orderTypeFilter = 'all';

    public $dateFrom = '';

    public $dateTo = '';

    public $sortField = 'created_at';

    public $sortDirection = 'desc';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => 'all'],
        'orderTypeFilter' => ['except' => 'all'],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingOrderTypeFilter()
    {
        $this->resetPage();
    }

    public function updatingDateFrom()
    {
        $this->resetPage();
    }

    public function updatingDateTo()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        $query = Order::with(['user', 'items.document']);

        if (! empty($this->search)) {
            $query->where(function ($q) {
                $q->where('order_code', 'like', '%'.$this->search.'%')
                  ->orWhere('guest_email', 'like', '%'.$this->search.'%')
                  ->orWhereHas('user', function ($userQ) {
                      $userQ->where('name', 'like', '%'.$this->search.'%')
                            ->orWhere('email', 'like', '%'.$this->search.'%');
                  });
            });
        }

        if ($this->statusFilter !== 'all') {
            $query->where('payment_status', $this->statusFilter);
        }

        if ($this->orderTypeFilter !== 'all') {
            $query->where('order_type', $this->orderTypeFilter);
        }

        if (! empty($this->dateFrom)) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }

        if (! empty($this->dateTo)) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        $query->whereNotIn('payment_status', ['failed', 'cancelled']);

        $orders = $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate(20);

        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount');
        $totalContributorAmount = OrderItem::whereHas('order', function ($q) {
            $q->where('payment_status', 'paid');
        })->sum('contributor_amount');

        $stats = [
            'total_orders' => Order::whereNotIn('payment_status', ['failed', 'cancelled'])->count(),
            'paid_orders' => Order::where('payment_status', 'paid')->count(),
            'pending_orders' => Order::where('payment_status', 'pending')->count(),
            'subscription_count' => Order::where('order_type', 'subscription')->where('payment_status', 'paid')->count(),
            'subscription_revenue' => Order::where('order_type', 'subscription')->where('payment_status', 'paid')->sum('total_amount'),
            'total_revenue' => $totalRevenue,
            'total_contributor_amount' => $totalContributorAmount,
            'total_platform_amount' => $totalRevenue - $totalContributorAmount,
        ];

        return view('payment::livewire.admin.order-management', [
            'orders' => $orders,
            'stats' => $stats,
        ])->layout('layouts.admin', [
            'pageTitle' => 'Quản lý đơn hàng',
        ]);
    }
}
