<?php

namespace Modules\Payment\Http\Livewire\Admin;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Models\User;
use Modules\Payment\Models\WalletTransaction;
use Modules\Payment\Models\PayoutRequest;
use Modules\Document\Models\Document;
use Modules\Document\Models\DocumentDownload;

class PlatformRevenue extends Component
{
    public function render()
    {
        $totalRevenue = DB::table('orders')
            ->where('payment_status', 'paid')
            ->sum('total_amount');

        $revenueThisMonth = DB::table('orders')
            ->where('payment_status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');

        $pendingPayouts = PayoutRequest::where('status', 'pending')->sum('amount');

        $totalPaid = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.payment_status', 'paid')
            ->sum('order_items.contributor_amount');

        $netProfit = $totalRevenue - $totalPaid;

        $lastMonthRevenue = DB::table('orders')
            ->where('payment_status', 'paid')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('total_amount');

        $revenueGrowth = $lastMonthRevenue > 0
            ? round(($revenueThisMonth - $lastMonthRevenue) / $lastMonthRevenue * 100)
            : ($revenueThisMonth > 0 ? 100 : 0);

        // Thêm 3 metrics mới
        $totalDocuments = Document::count();
        $totalDownloads = DocumentDownload::count();
        $totalWalletBalance = User::sum('contributor_balance');

        $topContributors = User::where('contributor_balance', '>', 0)
            ->orWhereHas('walletTransactions', function ($q) {
                $q->where('type', 'earning');
            })
            ->withCount(['walletTransactions as earnings_total' => function ($q) {
                $q->where('type', 'earning');
            }])
            ->withSum(['walletTransactions as total_earned' => function ($q) {
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
