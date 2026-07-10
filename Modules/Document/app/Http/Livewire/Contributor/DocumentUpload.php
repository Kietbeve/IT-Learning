<?php

namespace Modules\Document\Http\Livewire\Contributor;

use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\HtmlString;
use Livewire\Component;
use Modules\Document\Traits\WithDocumentUpload;

class DocumentUpload extends Component
{
    use WithDocumentUpload;

    public function mount()
    {
        if (\App\Services\SettingService::get('allow_contributor_upload', '1') === '0') {
            session()->flash('error', 'Hệ thống đang tạm khóa chức năng đăng tài liệu của Contributor.');
            return $this->redirectRoute('contributor.documents.index', navigate: true);
        }
    }

    public function save()
    {
        return $this->processUpload(
            'pending',
            'Đăng tải tài liệu thành công! Tài liệu đang chờ Admin kiểm duyệt.',
            'contributor.documents.index'
        );
    }

    public function render()
    {
        $categories = Category::where('is_active', true)->get();
        $allTags = Tag::orderBy('name')->get();

        return view('document::livewire.contributor.document-upload', [
            'categories' => $categories,
            'subjects' => $this->subjects,
            'allTags' => $allTags,
        ])->layout('layouts.contributor', [
            'pageTitle' => 'Đăng tải tài liệu mới',
            'breadcrumb' => new HtmlString('<span class="mx-2">/</span> Contributor <span class="mx-2">/</span> Tài liệu <span class="mx-2">/</span> Tải lên'),
        ]);
    }
}
