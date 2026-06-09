<?php

namespace Modules\Document\Http\Livewire\Contributor;

use Livewire\Component;
use Modules\Document\Models\Document;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    public function render()
    {
        $user = Auth::user() ?? \Modules\Auth\Models\User::whereHas('roles', function($q) {
            $q->where('name', 'contributor');
        })->first() ?? \Modules\Auth\Models\User::first();
        
        // Count stats for the logged-in contributor
        $totalDocs = Document::where('author_id', $user->id)->count();
        $approvedDocs = Document::where('author_id', $user->id)->where('status', 'approved')->count();
        $pendingDocs = Document::where('author_id', $user->id)->where('status', 'pending')->count();
        
        $totalViews = Document::where('author_id', $user->id)->sum('view_count');
        $totalDownloads = Document::where('author_id', $user->id)->sum('download_count');
        $balance = $user->contributor_balance ?? 0;

        // Fetch 5 latest documents uploaded
        $latestDocs = Document::where('author_id', $user->id)
            ->with(['category', 'product'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('document::livewire.contributor.dashboard', [
            'totalDocs' => $totalDocs,
            'approvedDocs' => $approvedDocs,
            'pendingDocs' => $pendingDocs,
            'totalViews' => $totalViews,
            'totalDownloads' => $totalDownloads,
            'balance' => $balance,
            'latestDocs' => $latestDocs,
        ])->layout('layouts.contributor', [
            'pageTitle' => 'Kênh Người Đăng Tải',
            'breadcrumb' => new \Illuminate\Support\HtmlString('<span class="mx-2">/</span> Contributor <span class="mx-2">/</span> Dashboard')
        ]);
    }
}
