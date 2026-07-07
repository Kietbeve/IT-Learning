<?php

namespace Modules\Payment\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TransactionExport implements FromCollection, WithHeadings
{
    protected $transactions;

    public function __construct($transactions)
    {
        $this->transactions = $transactions;
    }

    public function collection()
    {
        return $this->transactions->map(function ($tx) {
            return [
                'ID' => $tx->id,
                'User' => $tx->user->name ?? 'N/A',
                'Email' => $tx->user->email ?? '',
                'Thời gian' => $tx->created_at->format('d/m/Y H:i:s'),
                'Loại' => $this->getTypeLabel($tx->type),
                'Số tiền' => number_format($tx->amount),
                'Số dư trước' => number_format($tx->balance_before),
                'Số dư sau' => number_format($tx->balance_after),
                'Tham chiếu' => $tx->reference_type.' #'.$tx->reference_id,
                'Ghi chú' => $tx->note ?? '',
            ];
        });
    }

    public function headings(): array
    {
        return ['ID', 'User', 'Email', 'Thời gian', 'Loại', 'Số tiền', 'Số dư trước', 'Số dư sau', 'Tham chiếu', 'Ghi chú'];
    }

    private function getTypeLabel($type)
    {
        $labels = [
            'earning' => 'Thu nhập',
            'purchase' => 'Mua tài liệu',
            'subscription' => 'VIP',
            'payout' => 'Rút tiền',
            'refund' => 'Hoàn tiền',
            'adjustment' => 'Điều chỉnh',
        ];

        return $labels[$type] ?? ucfirst($type);
    }
}
