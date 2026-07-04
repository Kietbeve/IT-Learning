<?php

namespace Modules\Payment\Http\Livewire\Admin;

use Livewire\Component;
use Modules\Payment\Models\WalletTransaction;
use Modules\Payment\Models\PayoutRequest;
use Modules\Auth\Models\User;
use Modules\Document\Models\Document;
use Modules\Document\Models\DocumentDownload;
use Illuminate\Support\Facades\DB;

class PlatformRevenue extends Component
{
    public $chartData = [];
    public $chartCategories = [];

    public function mount()
    {
        $this->loadChartData();
    }

    public function loadChartData()
    {
        $dailyTotals = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.payment_status', 'paid')
            ->where('orders.created_at', '>=', now()->subDays(7))
            ->selectRaw('DATE(orders.created_at) as date, SUM(order_items.platform_amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date')
            ->toArray();

        $this->chartCategories = [];
        $this->chartData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $label = now()->subDays($i)->format('d/m');
            $this->chartCategories[] = $label;
            $this->chartData[] = (int) ($dailyTotals[$date] ?? 0);
        }
    }

    public function render()
    {
        $totalRevenue = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.payment_status', 'paid')
            ->sum('order_items.subtotal');

        $revenueThisMonth = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.payment_status', 'paid')
            ->whereMonth('orders.created_at', now()->month)
            ->whereYear('orders.created_at', now()->year)
            ->sum('order_items.subtotal');

        $pendingPayouts = PayoutRequest::where('status', 'pending')->sum('amount');

        $totalPaid = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.payment_status', 'paid')
            ->sum('order_items.contributor_amount');

        $netProfit = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.payment_status', 'paid')
            ->sum('order_items.platform_amount');

        $lastMonthRevenue = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.payment_status', 'paid')
            ->whereMonth('orders.created_at', now()->subMonth()->month)
            ->whereYear('orders.created_at', now()->subMonth()->year)
            ->sum('order_items.subtotal');

        $revenueGrowth = $lastMonthRevenue > 0
            ? round(($revenueThisMonth - $lastMonthRevenue) / $lastMonthRevenue * 100)
            : ($revenueThisMonth > 0 ? 100 : 0);

        // Thêm 3 metrics mới
        $totalDocuments = Document::count();
        $totalDownloads = DocumentDownload::count();
        $totalWalletBalance = User::sum('contributor_balance');

        $topContributors = User::where('contributor_balance', '>', 0)
            ->orWhereHas('walletTransactions', function($q) {
                $q->where('type', 'earning');
            })
            ->withCount(['walletTransactions as earnings_total' => function($q) {
                $q->where('type', 'earning');
            }])
            ->withSum(['walletTransactions as total_earned' => function($q) {
                $q->where('type', 'earning');
            }], 'amount')
            ->orderByDesc('total_earned')
            ->take(10)
            ->get();

        return view('payment::livewire.admin.platform-revenue', [
            'totalRevenue' => $totalRevenue,
            'revenueThisMonth' => $revenueThisMonth,
            'pendingPayouts' => $pendingPayouts,
            'totalPaid' => $totalPaid,
            'netProfit' => $netProfit,
            'revenueGrowth' => $revenueGrowth,
            'topContributors' => $topContributors,
            // 3 metrics mới
            'totalDocuments' => $totalDocuments,
            'totalDownloads' => $totalDownloads,
            'totalWalletBalance' => $totalWalletBalance,
        ])->layout('layouts.admin', [
            'pageTitle' => 'Dashboard',
        ]);
    }
}
