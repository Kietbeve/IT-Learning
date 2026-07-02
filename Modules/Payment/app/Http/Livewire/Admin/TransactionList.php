<?php

namespace Modules\Payment\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Modules\Payment\Models\WalletTransaction;
use Modules\Auth\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Payment\Exports\TransactionExport;

class TransactionList extends Component
{
    use WithPagination;

    public $search = '';
    public $typeFilter = 'all';
    public $dateFrom = '';
    public $dateTo = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    protected $queryString = [
        'search' => ['except' => ''],
        'typeFilter' => ['except' => 'all'],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingTypeFilter()
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

    public function exportExcel()
    {
        $query = WalletTransaction::with('user');

        if (!empty($this->search)) {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->typeFilter !== 'all') {
            $query->where('type', $this->typeFilter);
        }

        if (!empty($this->dateFrom)) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }

        if (!empty($this->dateTo)) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        $transactions = $query->orderBy('created_at', 'desc')->get();

        return Excel::download(
            new TransactionExport($transactions),
            'transactions_' . now()->format('Ymd_His') . '.xlsx'
        );
    }

    public function render()
    {
        $query = WalletTransaction::with('user');

        if (!empty($this->search)) {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->typeFilter !== 'all') {
            $query->where('type', $this->typeFilter);
        } else {
            $query->whereIn('type', ['earning', 'purchase', 'payout', 'refund', 'adjustment', 'subscription']);
        }

        if (!empty($this->dateFrom)) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }

        if (!empty($this->dateTo)) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        $transactions = $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate(20);

        $stats = [
            'total_credit' => WalletTransaction::whereIn('type', ['purchase', 'refund', 'adjustment', 'subscription', 'earning'])
                ->sum('amount'),
            'total_debit' => WalletTransaction::whereIn('type', ['payout'])->sum('amount'),
            'total_count' => WalletTransaction::count(),
            'purchase_count' => WalletTransaction::where('type', 'purchase')->count(),
        ];

        return view('payment::livewire.admin.transaction-list', [
            'transactions' => $transactions,
            'stats' => $stats,
        ])->layout('layouts.admin', [
            'pageTitle' => 'Lịch sử giao dịch',
        ]);
    }
}
