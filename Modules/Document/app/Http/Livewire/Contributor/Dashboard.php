<?php

namespace Modules\Document\Http\Livewire\Contributor;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
use Livewire\Component;
use App\Models\User;
use Modules\Document\Models\Document;
use Modules\Payment\Models\WalletTransaction;

class Dashboard extends Component
{
    public function render()
    {
        $user = Auth::user() ?? User::whereHas('roles', function ($q) {
            $q->where('name', 'contributor');
        })->first() ?? User::first();

        // Document stats (only total count, moved detail stats to DocumentList)
        $totalDocs = Document::where('author_id', $user->id)->count();
        $balance = $user->contributor_balance ?? 0;

        // Top 5 best-selling documents
        $latestDocs = Document::where('author_id', $user->id)
            ->select('documents.*')
            ->selectSub(function ($query) {
                $query->selectRaw('COALESCE(COUNT(*), 0)')
                    ->from('order_items')
                    ->join('orders', 'orders.id', '=', 'order_items.order_id')
                    ->whereColumn('order_items.document_id', 'documents.id')
                    ->where('orders.payment_status', 'paid');
            }, 'total_sales')
            ->with(['currentVersion.category', 'currentVersion.subject', 'product'])
            ->orderByDesc('total_sales')
            ->limit(5)
            ->get();

        // ===== EARNINGS REPORT DATA =====

        // Earnings this month
        $earningsThisMonth = WalletTransaction::where('user_id', $user->id)
            ->where('type', 'earning')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        // Earnings last month
        $earningsLastMonth = WalletTransaction::where('user_id', $user->id)
            ->where('type', 'earning')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('amount');

        // Change percent
        $changePercent = $earningsLastMonth > 0
            ? (($earningsThisMonth - $earningsLastMonth) / $earningsLastMonth) * 100
            : 0;

        // Today's earnings
        $todayEarnings = WalletTransaction::where('user_id', $user->id)
            ->where('type', 'earning')
            ->whereDate('created_at', now()->toDateString())
            ->sum('amount');

        // Chart data (last 30 days)
        $chartData = WalletTransaction::where('user_id', $user->id)
            ->where('type', 'earning')
            ->whereBetween('created_at', [now()->subDays(30), now()])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top documents by revenue
        $topDocuments = Document::where('author_id', $user->id)
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
            ->limit(5)
            ->get();

        $platformFeePercent = (int) \App\Services\SettingService::get('platform_fee_percent', 20);
        $contributorPercent = 100 - $platformFeePercent;

        return view('document::livewire.contributor.dashboard', [
            'totalDocs' => $totalDocs,
            'balance' => $balance,
            'latestDocs' => $latestDocs,

            // Earnings stats
            'earningsThisMonth' => $earningsThisMonth,
            'earningsLastMonth' => $earningsLastMonth,
            'changePercent' => round($changePercent, 1),
            'todayEarnings' => $todayEarnings,
            'chartData' => $chartData,
            'topDocuments' => $topDocuments,
            'contributorPercent' => $contributorPercent,
        ])->layout('layouts.contributor', [
            'breadcrumb' => new HtmlString('<span class="mx-2">/</span> Contributor <span class="mx-2">/</span> Dashboard'),
        ]);
    }
}
