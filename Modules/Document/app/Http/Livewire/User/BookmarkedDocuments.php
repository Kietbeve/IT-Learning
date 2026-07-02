<?php

namespace Modules\Document\Http\Livewire\User;

use Livewire\Component;
use Livewire\WithPagination;
use Modules\Document\Models\Document;
use Modules\Document\Models\DocumentFavorite;
use Illuminate\Support\Facades\Auth;

class BookmarkedDocuments extends Component
{
    use WithPagination;

    public function toggleFavorite($documentId)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userId = Auth::id();
        $fav = DocumentFavorite::where('document_id', $documentId)->where('user_id', $userId)->first();
        $doc = Document::find($documentId);

        if ($fav) {
            $fav->delete();
            if ($doc) {
                $doc->decrement('favorite_count');
            }
            $this->dispatch('notify', ['type' => 'info', 'message' => 'Đã bỏ lưu tài liệu khỏi mục Yêu thích.']);
        }
    }

    public function render()
    {
        $userId = Auth::id();

        $favorites = DocumentFavorite::where('user_id', $userId)
            ->with(['document.author', 'document.currentVersion.category', 'document.product'])
            ->paginate(15);

        return view('document::livewire.user.bookmarked-documents', [
            'favorites' => $favorites
        ])->layout('layouts.user');
    }
}
