<?php

namespace Modules\Document\Http\Livewire\User;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Document\Models\Document;
use Modules\Document\Models\DocumentFavorite;

class BookmarkedDocuments extends Component
{
    use WithPagination;

    public function toggleFavorite($documentId)
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $userId = Auth::id();
        $doc = Document::find($documentId);
        
        if ($doc) {
            $doc->toggleFavoriteForUser($userId);
            // The list of bookmarks is paginated and loaded in render(), so it will refresh automatically
        }
    }

    public function render()
    {
        $userId = Auth::id();

        $favorites = DocumentFavorite::where('user_id', $userId)
            ->with(['document.author', 'document.currentVersion.category', 'document.product'])
            ->paginate(15);

        return view('document::livewire.user.bookmarked-documents', [
            'favorites' => $favorites,
        ])->layout('layouts.user');
    }
}
