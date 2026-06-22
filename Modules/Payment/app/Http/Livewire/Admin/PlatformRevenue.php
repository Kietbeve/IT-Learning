<?php

namespace Modules\Payment\Http\Livewire\Admin;

use Livewire\Component;
use Modules\Payment\Models\WalletTransaction;
use Modules\Payment\Models\PayoutRequest;
use Modules\Auth\Models\User;
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
        $dailyTotals = WalletTransaction::whereIn('type', ['purchase', 'subscription'])
            ->where('amount', '>', 0)
            ->where('created_at', '>=', now()->subDays(7))
            ->selectRaw('DATE(created_at) as date, SUM(amount) as total')
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
        $totalRevenue = WalletTransaction::whereIn('type', ['purchase', 'subscription'])
            ->where('amount', '>', 0)->sum('amount');

        $revenueThisMonth = WalletTransaction::whereIn('type', ['purchase', 'subscription'])
            ->where('amount', '>', 0)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        $pendingPayouts = PayoutRequest::where('status', 'pending')->sum('amount');

        $totalPaid = PayoutRequest::where('status', 'completed')->sum('amount');

        $netProfit = $totalRevenue - $totalPaid;

        $lastMonthRevenue = WalletTransaction::whereIn('type', ['purchase', 'subscription'])
            ->where('amount', '>', 0)
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('amount');

        $revenueGrowth = $lastMonthRevenue > 0
            ? round(($revenueThisMonth - $lastMonthRevenue) / $lastMonthRevenue * 100)
            : ($revenueThisMonth > 0 ? 100 : 0);

        $topContributors = User::where('contributor_balance', '>', 0)
            ->orWhereHas('walletTransactions', function($q) {
                $q->where('type', 'purchase');
            })
            ->withCount(['walletTransactions as earnings_total' => function($q) {
                $q->whereIn('type', ['purchase', 'subscription']);
            }])
            ->withSum(['walletTransactions as total_earned' => function($q) {
                $q->whereIn('type', ['purchase', 'subscription']);
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
        ])->layout('layouts.admin', [
            'pageTitle' => 'Dashboard',
        ]);
    }
}
