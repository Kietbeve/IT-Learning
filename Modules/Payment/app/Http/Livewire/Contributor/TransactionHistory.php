<?php

namespace Modules\Payment\Http\Livewire\Contributor;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Payment\Models\OrderItem;
use Modules\Payment\Models\WalletTransaction;

class TransactionHistory extends Component
{
    use WithPagination;

    public $filterDateFrom;

    public $filterDateTo;

    public $search = '';

    public $sortField = 'created_at';

    public $sortDirection = 'desc';

    protected $queryString = ['search', 'sortField', 'sortDirection'];

    public function mount()
    {
        $this->filterDateFrom = null;
        $this->filterDateTo = null;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->filterDateFrom = null;
        $this->filterDateTo = null;
        $this->search = '';
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
        $query = WalletTransaction::where('user_id', Auth::id())
            ->where('type', 'earning');

        // Filter by date range
        if ($this->filterDateFrom) {
            $query->whereDate('created_at', '>=', $this->filterDateFrom);
        }
        if ($this->filterDateTo) {
            $query->whereDate('created_at', '<=', $this->filterDateTo);
        }

        // Search in note
        if ($this->search) {
            $query->where('note', 'like', '%'.$this->search.'%');
        }

        // Sort
        $query->orderBy($this->sortField, $this->sortDirection);

        return $query->paginate(20);
    }

    public function exportToExcel()
    {
        // Get all items according to filters but without pagination
        $transactionsExportData = WalletTransaction::where('user_id', Auth::id())
            ->where('type', 'earning')
            ->when($this->filterDateFrom, function ($q) {
                $q->whereDate('created_at', '>=', $this->filterDateFrom);
            })
            ->when($this->filterDateTo, function ($q) {
                $q->whereDate('created_at', '<=', $this->filterDateTo);
            })
            ->when($this->search, function ($q) {
                $q->where('note', 'like', '%'.$this->search.'%');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $orderItems = $this->getOrderItemsData($transactionsExportData);
        $filename = 'Lich_su_thu_nhap_'.now()->format('Ymd_His').'.xlsx';

        return Excel::download(new TransactionsExport($transactionsExportData, $orderItems), $filename);
    }

    private function getOrderItemsData($transactions)
    {
        $orderItemIds = $transactions->where('reference_type', 'order_item')
            ->pluck('reference_id')
            ->filter()
            ->unique()
            ->toArray();

        if (empty($orderItemIds)) {
            return collect();
        }

        return OrderItem::with('order')->whereIn('id', $orderItemIds)->get()->keyBy('id');
    }

    public function render()
    {
        $transactions = $this->transactions;
        $orderItems = $this->getOrderItemsData($transactions);

        return view('payment::livewire.contributor.transaction-history', [
            'transactions' => $transactions,
            'orderItems' => $orderItems,
        ])->layout('layouts.contributor', ['title' => 'Lịch sử thu nhập']);
    }
}

class TransactionsExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $transactions;

    protected $orderItems;

    public function __construct($transactions, $orderItems = null)
    {
        $this->transactions = $transactions;
        $this->orderItems = $orderItems ?: collect();
    }

    public function collection()
    {
        return $this->transactions->map(function ($tx) {
            $docName = '-';
            $hinhThuc = 'Thu nhập';
            if ($tx->reference_type === 'order_item' && $this->orderItems->has($tx->reference_id)) {
                $item = $this->orderItems->get($tx->reference_id);
                $docName = $item->document_title_snapshot;
                if ($item->order && $item->order->total_amount == 0) {
                    $hinhThuc = 'VIP';
                } else {
                    $hinhThuc = 'Tiền mặt';
                }
            } elseif (str_contains($tx->note ?? '', 'Doanh thu từ tài liệu: ')) {
                $docName = trim(str_replace('Doanh thu từ tài liệu: ', '', $tx->note));
            } elseif (in_array($tx->type, ['purchase', 'earning', 'subscription'])) {
                $docName = 'Tài liệu lập trình Python cơ bản';
            }

            return [
                'Hình thức' => $hinhThuc,
                'Tài liệu' => $docName,
                'Ghi chú' => $tx->note,
                'Số dư trước' => number_format($tx->balance_before),
                'Số tiền' => number_format($tx->amount).($tx->type === 'earning' ? ' (+)' : ' (-)'),
                'Số dư sau' => number_format($tx->balance_after),
                'Thời gian' => $tx->created_at->format('d/m/Y H:i:s'),
            ];
        });
    }

    public function headings(): array
    {
        return ['Hình thức', 'Tài liệu', 'Ghi chú', 'Số dư trước', 'Số tiền', 'Số dư sau', 'Thời gian'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true], 'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFE5E7EB']]],
        ];
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
