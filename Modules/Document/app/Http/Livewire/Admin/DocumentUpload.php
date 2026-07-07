<?php

namespace Modules\Document\Http\Livewire\Admin;

use App\Models\Category;
use App\Models\Tag;
use Livewire\Component;
use Modules\Document\Traits\WithDocumentUpload;

class DocumentUpload extends Component
{
    use WithDocumentUpload;

    public function save()
    {
        return $this->processUpload(
            'approved',
            'Đăng tải tài liệu thành công! Tài liệu đã được phê duyệt tự động.',
            'admin.documents.index'
        );
    }

    public function render()
    {
        $categories = Category::where('is_active', true)->get();
        $allTags = Tag::orderBy('name')->get();

        return view('document::livewire.admin.document-upload', [
            'categories' => $categories,
            'subjects' => $this->subjects,
            'allTags' => $allTags,
        ])->layout('layouts.admin');
    }
}
