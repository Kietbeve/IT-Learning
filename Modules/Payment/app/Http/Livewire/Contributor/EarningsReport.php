<?php

namespace Modules\Payment\Http\Livewire\Contributor;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Modules\Document\Models\Document;
use Modules\Payment\Models\WalletTransaction;

class EarningsReport extends Component
{
    public $dateRange = '30days';

    public $dateFrom;

    public $dateTo;

    public function mount()
    {
        $this->setDateRange();
    }

    public function updatedDateRange()
    {
        $this->setDateRange();
    }

    private function setDateRange()
    {
        switch ($this->dateRange) {
            case '7days':
                $this->dateFrom = now()->subDays(7);
                $this->dateTo = now();
                break;
            case '30days':
                $this->dateFrom = now()->subDays(30);
                $this->dateTo = now();
                break;
            case '3months':
                $this->dateFrom = now()->subMonths(3);
                $this->dateTo = now();
                break;
            case '1year':
                $this->dateFrom = now()->subYear();
                $this->dateTo = now();
                break;
            case 'custom':
                // User will set dateFrom and dateTo manually
                break;
        }
    }

    public function getStatsProperty()
    {
        $userId = Auth::id();

        // Earnings this month
        $earningsThisMonth = WalletTransaction::where('user_id', $userId)
            ->where('type', 'earning')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        // Earnings last month
        $earningsLastMonth = WalletTransaction::where('user_id', $userId)
            ->where('type', 'earning')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('amount');

        // Calculate change percent
        $changePercent = $earningsLastMonth > 0
            ? (($earningsThisMonth - $earningsLastMonth) / $earningsLastMonth) * 100
            : 0;

        // Average revenue per document
        $myDocuments = Document::where('author_id', $userId)->pluck('id');
        $totalEarnings = WalletTransaction::where('user_id', $userId)
            ->where('type', 'earning')
            ->sum('amount');
        $avgRevenuePerDoc = $myDocuments->count() > 0
            ? $totalEarnings / $myDocuments->count()
            : 0;

        // Conversion rate (views -> downloads -> purchases)
        $totalViews = Document::where('author_id', $userId)->sum('view_count');
        $totalDownloads = Document::where('author_id', $userId)->sum('download_count');
        $conversionRate = $totalViews > 0 ? ($totalDownloads / $totalViews) * 100 : 0;

        return [
            'earningsThisMonth' => $earningsThisMonth,
            'earningsLastMonth' => $earningsLastMonth,
            'changePercent' => round($changePercent, 1),
            'avgRevenuePerDoc' => round($avgRevenuePerDoc, 0),
            'conversionRate' => round($conversionRate, 2),
        ];
    }

    public function getChartDataProperty()
    {
        $userId = Auth::id();

        // Daily earnings for the selected date range
        $dailyEarnings = WalletTransaction::where('user_id', $userId)
            ->where('type', 'earning')
            ->whereBetween('created_at', [$this->dateFrom, $this->dateTo])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'labels' => $dailyEarnings->pluck('date')->map(function ($date) {
                return Carbon::parse($date)->format('d/m');
            })->toArray(),
            'data' => $dailyEarnings->pluck('total')->toArray(),
        ];
    }

    public function getTopDocumentsByRevenueProperty()
    {
        $userId = Auth::id();

        return Document::where('author_id', $userId)
            ->select('documents.*')
            ->selectSub(function ($query) {
                $query->selectRaw('COALESCE(SUM(order_items.contributor_amount), 0)')
                    ->from('order_items')
                    ->join('orders', 'orders.id', '=', 'order_items.order_id')
                    ->whereColumn('order_items.document_id', 'documents.id')
                    ->where('orders.payment_status', 'paid');
            }, 'total_revenue')
            ->selectSub(function ($query) {
                $query->selectRaw('COUNT(*)')
                    ->from('order_items')
                    ->join('orders', 'orders.id', '=', 'order_items.order_id')
                    ->whereColumn('order_items.document_id', 'documents.id')
                    ->where('orders.payment_status', 'paid');
            }, 'total_sales')
            ->orderByDesc('total_revenue')
            ->limit(10)
            ->get();
    }

    public function exportReport()
    {
        // Export Excel report - placeholder
        // You can implement Excel export similar to TransactionHistory
        session()->flash('info', 'Tính năng xuất báo cáo đang được phát triển');
    }

    public function render()
    {
        return view('payment::livewire.contributor.earnings-report', [
            'stats' => $this->stats,
            'chartData' => $this->chartData,
            'topDocuments' => $this->topDocumentsByRevenue,
        ])->layout('layouts.contributor');
    }
}
