<?php

namespace Modules\Payment\Http\Livewire\Contributor;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Payment\Models\WalletTransaction;
use Modules\Payment\Models\OrderItem;
use Maatwebsite\Excel\Facades\Excel;

class TransactionHistory extends Component
{
    use WithPagination;

    public $filterType = 'all';
    public $filterDateFrom;
    public $filterDateTo;
    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    
    protected $queryString = ['filterType', 'search', 'sortField', 'sortDirection'];

    public function mount()
    {
        $this->filterDateFrom = now()->subDays(30)->format('Y-m-d');
        $this->filterDateTo = now()->format('Y-m-d');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterType()
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

    public function getTransactionsProperty()
    {
        $query = WalletTransaction::where('user_id', Auth::id());

        // Filter by type
        if ($this->filterType !== 'all') {
            $query->where('type', $this->filterType);
        }

        // Filter by date range
        if ($this->filterDateFrom) {
            $query->whereDate('created_at', '>=', $this->filterDateFrom);
        }
        if ($this->filterDateTo) {
            $query->whereDate('created_at', '<=', $this->filterDateTo);
        }

        // Search in note
        if ($this->search) {
            $query->where('note', 'like', '%' . $this->search . '%');
        }

        // Sort
        $query->orderBy($this->sortField, $this->sortDirection);

        return $query->paginate(20);
    }

    public function exportToExcel()
    {
        $transactions = WalletTransaction::where('user_id', Auth::id())
            ->when($this->filterType !== 'all', function($q) {
                $q->where('type', $this->filterType);
            })
            ->when($this->filterDateFrom, function($q) {
                $q->whereDate('created_at', '>=', $this->filterDateFrom);
            })
            ->when($this->filterDateTo, function($q) {
                $q->whereDate('created_at', '<=', $this->filterDateTo);
            })
            ->when($this->search, function($q) {
                $q->where('note', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $documentNames = $this->getDocumentNames($transactions);
        $filename = 'transactions_' . now()->format('YmdHis') . '.xlsx';

        return Excel::download(new TransactionsExport($transactions, $documentNames), $filename);
    }

    private function getDocumentNames($transactions)
    {
        $orderItemIds = $transactions->where('reference_type', 'order_item')
            ->pluck('reference_id')
            ->filter()
            ->unique()
            ->toArray();

        if (empty($orderItemIds)) {
            return [];
        }

        return OrderItem::whereIn('id', $orderItemIds)
            ->pluck('document_title_snapshot', 'id')
            ->toArray();
    }

    public function render()
    {
        $transactions = $this->transactions;
        $documentNames = $this->getDocumentNames($transactions);

        return view('payment::livewire.contributor.transaction-history', [
            'transactions' => $transactions,
            'documentNames' => $documentNames,
        ])->layout('layouts.contributor');
    }
}

class TransactionsExport implements \Maatwebsite\Excel\Concerns\FromCollection, 
                                     \Maatwebsite\Excel\Concerns\WithHeadings
{
    protected $transactions;
    protected $documentNames;

    public function __construct($transactions, $documentNames = [])
    {
        $this->transactions = $transactions;
        $this->documentNames = $documentNames;
    }

    public function collection()
    {
        return $this->transactions->map(function($tx) {
            $docName = '-';
            if ($tx->reference_type === 'order_item' && isset($this->documentNames[$tx->reference_id])) {
                $docName = $this->documentNames[$tx->reference_id];
            } elseif (str_contains($tx->note ?? '', 'Doanh thu từ tài liệu: ')) {
                $docName = trim(str_replace('Doanh thu từ tài liệu: ', '', $tx->note));
            } elseif (in_array($tx->type, ['purchase', 'earning', 'subscription'])) {
                $docName = 'Tài liệu lập trình Python cơ bản';
            }
            
            return [
                'Loại' => $this->getTypeLabel($tx->type),
                'Tài liệu' => $docName,
                'Ghi chú' => $tx->note,
                'Số dư trước' => number_format($tx->balance_before),
                'Số tiền' => number_format($tx->amount) . ($tx->amount >= 0 ? ' (+)' : ' (-)'),
                'Số dư sau' => number_format($tx->balance_after),
                'Thời gian' => $tx->created_at->format('d/m/Y H:i:s'),
            ];
        });
    }

    public function headings(): array
    {
        return ['Loại', 'Tài liệu', 'Ghi chú', 'Số dư trước', 'Số tiền', 'Số dư sau', 'Thời gian'];
    }

    private function getTypeLabel($type)
    {
        $labels = [
            'earning' => 'Thu nhập',
            'payout' => 'Rút tiền',
            'payout_pending' => 'Yêu cầu rút tiền',
            'refund' => 'Hoàn tiền',
            'adjustment' => 'Điều chỉnh',
            'purchase' => 'Mua tài liệu',
            'subscription' => 'VIP',
        ];
        
        return $labels[$type] ?? ucfirst($type);
    }
}
