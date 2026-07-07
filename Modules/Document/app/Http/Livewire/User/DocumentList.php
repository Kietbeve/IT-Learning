<?php

namespace Modules\Document\Http\Livewire\User;

use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Document\Models\Document;
use Modules\Document\Models\DocumentFavorite;

class DocumentList extends Component
{
    use WithPagination;

    public $search = '';

    public $category = null;

    public $selectedPrice = '';

    public $selectedYear = '';

    public $selectedResourceType = '';

    public $selectedCustomCategory = '';

    public $selectedSubject = '';

    public $sort = 'newest';

    protected $queryString = [
        'search' => ['except' => ''],
        'category' => ['except' => null],
        'selectedPrice' => ['except' => ''],
        'selectedYear' => ['except' => ''],
        'selectedResourceType' => ['except' => ''],
        'selectedCustomCategory' => ['except' => ''],
        'selectedSubject' => ['except' => ''],
        'sort' => ['except' => 'newest'],
    ];

    public function updated($propertyName)
    {
        if (in_array($propertyName, [
            'search', 'category', 'selectedFileType', 'selectedPrice',
            'selectedYear', 'selectedResourceType', 'selectedCustomCategory',
            'selectedSubject', 'selectedLanguage',
        ])) {
            $this->resetPage();
        }
    }

    public function toggleFavorite($documentId)
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $document = Document::find($documentId);
        if (! $document) {
            return;
        }

        $userId = Auth::id();
        $document->toggleFavoriteForUser($userId);
    }

    public function resetFilters()
    {
        $this->reset([
            'search',
            'category',
            'selectedPrice',
            'selectedYear',
            'selectedResourceType',
            'selectedCustomCategory',
            'selectedSubject',
        ]);
        $this->resetPage();
    }

    public function render()
    {
        $categories = Category::where('is_active', true)->orderBy('sort_order', 'asc')->get();

        // status nằm trên documents, các cột còn lại (như visibility) truy vấn qua currentVersion
        $query = Document::where('documents.status', 'approved')
            ->whereHas('currentVersion', function ($q) {
                $q->where('visibility', 'public');
            });

        if (! empty($this->search)) {
            $searchStr = '%' . $this->search . '%';
            
            $query->where(function ($q) use ($searchStr) {
                $q->whereHas('currentVersion', function ($vq) use ($searchStr) {
                    $vq->where('title', 'like', $searchStr)
                        ->orWhere('short_description', 'like', $searchStr)
                        ->orWhere('description', 'like', $searchStr);
                })
                ->orWhereHas('tags', function ($tq) use ($searchStr) {
                    $tq->where('name', 'like', $searchStr);
                })
                ->orWhereHas('author', function ($aq) use ($searchStr) {
                    $aq->where('name', 'like', $searchStr);
                });
            });

            // Tính điểm Relevance theo đề xuất:
            // Title (10) -> Tags (5) -> Short description (3) -> Description (2) -> Author (1)
            $query->selectRaw("documents.*, (
                (SELECT CASE WHEN title LIKE ? THEN 10 WHEN short_description LIKE ? THEN 3 WHEN description LIKE ? THEN 2 ELSE 0 END FROM document_versions WHERE document_versions.id = documents.current_version_id LIMIT 1) +
                (SELECT CASE WHEN count(*) > 0 THEN 5 ELSE 0 END FROM document_tag_maps JOIN tags ON tags.id = document_tag_maps.tag_id WHERE document_tag_maps.document_id = documents.id AND tags.name LIKE ?) +
                (SELECT CASE WHEN name LIKE ? THEN 1 ELSE 0 END FROM users WHERE users.id = documents.author_id LIMIT 1)
            ) as relevance_score", [$searchStr, $searchStr, $searchStr, $searchStr, $searchStr]);
        } else {
            $query->select('documents.*');
        }

        if ($this->category) {
            $catId = Category::where('slug', $this->category)->value('id');
            if ($catId) {
                $query->whereHas('currentVersion', function ($q) use ($catId) {
                    $q->where('category_id', $catId);
                });
            }
        }

        if ($this->selectedSubject) {
            $query->whereHas('currentVersion', function ($q) {
                $q->where('subject_id', $this->selectedSubject);
            });
        }

        // 1. Loại tài nguyên (Resource Type)
        if (! empty($this->selectedResourceType)) {
            if ($this->selectedResourceType === 'pdf') {
                $query->whereHas('currentVersion', function ($q) {
                    $q->where('file_type', 'pdf');
                });
            } elseif ($this->selectedResourceType === 'docx') {
                $query->whereHas('currentVersion', function ($q) {
                    $q->where('file_type', 'docx');
                });
            } elseif ($this->selectedResourceType === 'source_code') {
                $query->where(function ($q) {
                    $q->whereHas('currentVersion', function ($vq) {
                        $vq->whereIn('file_type', ['zip', 'rar', 'tar', 'gz', '7z'])
                            ->orWhere('title', 'like', '%source code%')
                            ->orWhere('title', 'like', '%mã nguồn%')
                            ->orWhere('title', 'like', '%source%')
                            ->orWhere('title', 'like', '%code%')
                            ->orWhere('title', 'like', '%dự án%')
                            ->orWhere('title', 'like', '%project%');
                    });
                });
            } elseif ($this->selectedResourceType === 'do_an') {
                $query->whereHas('currentVersion', function ($vq) {
                    $vq->where('title', 'like', '%đồ án%')
                        ->orWhere('title', 'like', '%do an%')
                        ->orWhere('title', 'like', '%báo cáo%')
                        ->orWhere('title', 'like', '%khóa luận%')
                        ->orWhere('title', 'like', '%niên luận%')
                        ->orWhere('title', 'like', '%tiểu luận%')
                        ->orWhere('description', 'like', '%đồ án%')
                        ->orWhere('description', 'like', '%do an%');
                });
            } elseif ($this->selectedResourceType === 'ebook') {
                $query->whereHas('currentVersion', function ($vq) {
                    $vq->where('title', 'like', '%ebook%')
                        ->orWhere('title', 'like', '%sách%')
                        ->orWhere('title', 'like', '%giáo trình%')
                        ->orWhere('description', 'like', '%ebook%')
                        ->orWhere('description', 'like', '%sách%')
                        ->orWhere('description', 'like', '%giáo trình%');
                });
            }
        }



        if (! empty($this->selectedYear)) {
            // Thay thế checking documents.published_at bằng created_at của current_version_id
            $query->whereHas('currentVersion', function ($q) {
                $q->whereYear('reviewed_at', $this->selectedYear);
            });
        }

        if ($this->selectedPrice === 'free') {
            $query->whereDoesntHave('product');
        } elseif ($this->selectedPrice === 'paid') {
            $query->whereHas('product');
        }

        if ($this->sort === 'newest') {
            if (!empty($this->search)) {
                $query->orderBy('relevance_score', 'desc');
            }
            $query->orderBy('created_at', 'desc');
        } elseif ($this->sort === 'popular') {
            $query->orderBy('download_count', 'desc');
        } elseif ($this->sort === 'highest_rated') {
            $query->orderByRaw('COALESCE((SELECT AVG(dr.rating) FROM document_reviews dr WHERE dr.document_id = documents.id), 0) DESC')
                ->orderBy('download_count', 'desc');
        }

        $documents = $query->with(['author', 'currentVersion', 'currentVersion.category', 'product', 'tags', 'favorites' => function ($q) {
            $q->where('user_id', Auth::id());
        }])
        ->withAvg('reviews', 'rating')
        ->withCount('reviews')
        ->paginate(20);

        $categories = Category::where('is_active', true)->orderBy('sort_order', 'asc')->get();
        $subjects = \Modules\Document\Models\Subject::where('is_active', true)->get();

        return view('document::livewire.user.document-list', [
            'documents' => $documents,
            'categories' => $categories,
            'subjects' => $subjects,
        ])->layout('layouts.user');
    }
}
