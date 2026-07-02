<?php

namespace Modules\Document\Http\Livewire\User;

use Livewire\Component;
use Livewire\WithPagination;
use Modules\Document\Models\Document;
use Modules\Document\Models\DocumentFavorite;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class DocumentList extends Component
{
    use WithPagination;

    public $search = '';
    public $category = null;
    public $selectedFileType = '';
    public $selectedPrice = '';
    public $selectedYear = '';
    public $selectedResourceType = '';
    public $selectedCustomCategory = '';
    public $selectedSubject = '';
    public $selectedLanguage = '';
    public $sort = 'newest';

    protected $queryString = [
        'search' => ['except' => ''],
        'category' => ['except' => null],
        'selectedFileType' => ['except' => ''],
        'selectedPrice' => ['except' => ''],
        'selectedYear' => ['except' => ''],
        'selectedResourceType' => ['except' => ''],
        'selectedCustomCategory' => ['except' => ''],
        'selectedSubject' => ['except' => ''],
        'selectedLanguage' => ['except' => ''],
        'sort' => ['except' => 'newest'],
    ];

    public function updated($propertyName)
    {
        if (in_array($propertyName, [
            'search', 'category', 'selectedFileType', 'selectedPrice', 
            'selectedYear', 'selectedResourceType', 'selectedCustomCategory', 
            'selectedSubject', 'selectedLanguage'
        ])) {
            $this->resetPage();
        }
    }

    public function toggleFavorite($documentId)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userId = Auth::id();
        $fav = DocumentFavorite::where('document_id', $documentId)->where('user_id', $userId)->first();

        $document = Document::find($documentId);
        if (!$document) return;

        if ($fav) {
            $fav->delete();
            $document->decrement('favorite_count');
            $this->dispatch('notify', ['type' => 'info', 'message' => 'Đã bỏ lưu tài liệu']);
        } else {
            DocumentFavorite::create([
                'document_id' => $documentId,
                'user_id' => $userId
            ]);
            \Illuminate\Support\Facades\DB::table('documents')
                ->where('id', $document->id)
                ->increment('favorite_count');
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Đã lưu tài liệu vào danh sách yêu thích']);
        }
    }

    public function resetFilters()
    {
        $this->reset([
            'search',
            'category',
            'selectedFileType',
            'selectedPrice',
            'selectedYear',
            'selectedResourceType',
            'selectedCustomCategory',
            'selectedSubject',
            'selectedLanguage',
        ]);
        $this->resetPage();
    }

    public function render()
    {
        $categories = Category::where('is_active', true)->get();

        // status và visibility nằm trên documents, các cột còn lại ta truy vấn qua currentVersion
        $query = Document::where('documents.status', 'approved');

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->whereHas('currentVersion', function($vq) {
                    $vq->where('title', 'like', '%' . $this->search . '%')
                       ->orWhere('short_description', 'like', '%' . $this->search . '%')
                       ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            });
        }

        if ($this->category) {
            $catId = Category::where('slug', $this->category)->value('id');
            if ($catId) {
                $query->whereHas('currentVersion', function($q) use ($catId) {
                    $q->where('category_id', $catId);
                });
            }
        }

        if (!empty($this->selectedFileType)) {
            $query->whereHas('currentVersion', function($q) {
                $q->where('file_type', $this->selectedFileType);
            });
        }

        // 1. Loại tài nguyên (Resource Type)
        if (!empty($this->selectedResourceType)) {
            if ($this->selectedResourceType === 'pdf') {
                $query->whereHas('currentVersion', function($q) { $q->where('file_type', 'pdf'); });
            } elseif ($this->selectedResourceType === 'docx') {
                $query->whereHas('currentVersion', function($q) { $q->where('file_type', 'docx'); });
            } elseif ($this->selectedResourceType === 'source_code') {
                $query->where(function($q) {
                    $q->whereHas('currentVersion', function($vq) {
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
                $query->whereHas('currentVersion', function($vq) {
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
                $query->whereHas('currentVersion', function($vq) {
                    $vq->where('title', 'like', '%ebook%')
                      ->orWhere('title', 'like', '%sách%')
                      ->orWhere('title', 'like', '%giáo trình%')
                      ->orWhere('description', 'like', '%ebook%')
                      ->orWhere('description', 'like', '%sách%')
                      ->orWhere('description', 'like', '%giáo trình%');
                });
            }
        }

        // 2. Danh mục (Category)
        if (!empty($this->selectedCustomCategory)) {
            if ($this->selectedCustomCategory === 'web') {
                $query->where(function($q) {
                    $q->whereHas('currentVersion', function($vq) {
                        $vq->where('title', 'like', '%web%')
                          ->orWhere('title', 'like', '%website%')
                          ->orWhere('title', 'like', '%laravel%')
                          ->orWhere('title', 'like', '%php%')
                          ->orWhere('title', 'like', '%html%')
                          ->orWhere('title', 'like', '%css%')
                          ->orWhere('title', 'like', '%js%')
                          ->orWhere('title', 'like', '%react%')
                          ->orWhere('title', 'like', '%vue%')
                          ->orWhere('title', 'like', '%angular%');
                    })
                    ->orWhereHas('currentVersion.category', function($catQ) {
                        $catQ->where('name', 'like', '%web%')
                             ->orWhere('name', 'like', '%laravel%')
                             ->orWhere('name', 'like', '%php%');
                    });
                });
            } elseif ($this->selectedCustomCategory === 'mobile') {
                $query->where(function($q) {
                    $q->whereHas('currentVersion', function($vq) {
                        $vq->where('title', 'like', '%mobile%')
                          ->orWhere('title', 'like', '%android%')
                          ->orWhere('title', 'like', '%ios%')
                          ->orWhere('title', 'like', '%flutter%')
                          ->orWhere('title', 'like', '%react native%')
                          ->orWhere('title', 'like', '%swift%')
                          ->orWhere('title', 'like', '%kotlin%');
                    })
                    ->orWhereHas('currentVersion.category', function($catQ) {
                        $catQ->where('name', 'like', '%mobile%')
                             ->orWhere('name', 'like', '%android%')
                             ->orWhere('name', 'like', '%ios%');
                    });
                });
            } elseif ($this->selectedCustomCategory === 'ai_ml') {
                $query->where(function($q) {
                    $q->whereHas('currentVersion', function($vq) {
                        $vq->where('title', 'like', '%ai%')
                          ->orWhere('title', 'like', '%ml%')
                          ->orWhere('title', 'like', '%artificial%')
                          ->orWhere('title', 'like', '%machine learning%')
                          ->orWhere('title', 'like', '%deep learning%')
                          ->orWhere('title', 'like', '%trí tuệ nhân tạo%')
                          ->orWhere('title', 'like', '%python%');
                    })
                    ->orWhereHas('currentVersion.category', function($catQ) {
                        $catQ->where('name', 'like', '%ai%')
                             ->orWhere('name', 'like', '%machine learning%')
                             ->orWhere('name', 'like', '%trí tuệ nhân tạo%');
                    });
                });
            } elseif ($this->selectedCustomCategory === 'devops') {
                $query->where(function($q) {
                    $q->whereHas('currentVersion', function($vq) {
                        $vq->where('title', 'like', '%devops%')
                          ->orWhere('title', 'like', '%docker%')
                          ->orWhere('title', 'like', '%kubernetes%')
                          ->orWhere('title', 'like', '%aws%')
                          ->orWhere('title', 'like', '%cicd%')
                          ->orWhere('title', 'like', '%git%')
                          ->orWhere('title', 'like', '%linux%');
                    })
                    ->orWhereHas('currentVersion.category', function($catQ) {
                        $catQ->where('name', 'like', '%devops%')
                             ->orWhere('name', 'like', '%docker%');
                    });
                });
            } elseif ($this->selectedCustomCategory === 'database') {
                $query->where(function($q) {
                    $q->whereHas('currentVersion', function($vq) {
                        $vq->where('title', 'like', '%database%')
                          ->orWhere('title', 'like', '%mysql%')
                          ->orWhere('title', 'like', '%sql%')
                          ->orWhere('title', 'like', '%csdl%')
                          ->orWhere('title', 'like', '%cơ sở dữ liệu%')
                          ->orWhere('title', 'like', '%mongodb%')
                          ->orWhere('title', 'like', '%postgres%')
                          ->orWhere('title', 'like', '%oracle%');
                    })
                    ->orWhereHas('currentVersion.category', function($catQ) {
                        $catQ->where('name', 'like', '%database%')
                             ->orWhere('name', 'like', '%csdl%')
                             ->orWhere('name', 'like', '%cơ sở dữ liệu%');
                    });
                });
            }
        }

        // 3. Môn học (Subject)
        if (!empty($this->selectedSubject)) {
            if ($this->selectedSubject === 'lap_trinh_web') {
                $query->whereHas('currentVersion', function($vq) {
                    $vq->where('title', 'like', '%lập trình web%')
                      ->orWhere('title', 'like', '%web programming%')
                      ->orWhere('title', 'like', '%laravel%')
                      ->orWhere('title', 'like', '%php%')
                      ->orWhere('title', 'like', '%html%')
                      ->orWhere('title', 'like', '%css%')
                      ->orWhere('title', 'like', '%js%')
                      ->orWhere('title', 'like', '%react%')
                      ->orWhere('title', 'like', '%vue%');
                });
            } elseif ($this->selectedSubject === 'csdl') {
                $query->whereHas('currentVersion', function($vq) {
                    $vq->where('title', 'like', '%csdl%')
                      ->orWhere('title', 'like', '%cơ sở dữ liệu%')
                      ->orWhere('title', 'like', '%database%')
                      ->orWhere('title', 'like', '%mysql%')
                      ->orWhere('title', 'like', '%sql%');
                });
            } elseif ($this->selectedSubject === 'mang_may_tinh') {
                $query->whereHas('currentVersion', function($vq) {
                    $vq->where('title', 'like', '%mạng máy tính%')
                      ->orWhere('title', 'like', '%computer network%')
                      ->orWhere('title', 'like', '%network%')
                      ->orWhere('title', 'like', '%tcp/ip%')
                      ->orWhere('title', 'like', '%cisco%');
                });
            }
        }

        // 4. Ngôn ngữ lập trình (Programming Language)
        if (!empty($this->selectedLanguage)) {
            if ($this->selectedLanguage === 'php') {
                $query->whereHas('currentVersion', function($vq) {
                    $vq->where('title', 'like', '%php%')
                      ->orWhere('title', 'like', '%laravel%')
                      ->orWhere('description', 'like', '%php%')
                      ->orWhere('description', 'like', '%laravel%');
                });
            } elseif ($this->selectedLanguage === 'python') {
                $query->whereHas('currentVersion', function($vq) {
                    $vq->where('title', 'like', '%python%')
                      ->orWhere('title', 'like', '%django%')
                      ->orWhere('title', 'like', '%flask%')
                      ->orWhere('description', 'like', '%python%')
                      ->orWhere('description', 'like', '%django%')
                      ->orWhere('description', 'like', '%flask%');
                });
            } elseif ($this->selectedLanguage === 'javascript') {
                $query->whereHas('currentVersion', function($vq) {
                    $vq->where('title', 'like', '%javascript%')
                      ->orWhere('title', 'like', '%js%')
                      ->orWhere('title', 'like', '%react%')
                      ->orWhere('title', 'like', '%vue%')
                      ->orWhere('title', 'like', '%node%')
                      ->orWhere('description', 'like', '%javascript%')
                      ->orWhere('description', 'like', '%js%')
                      ->orWhere('description', 'like', '%react%')
                      ->orWhere('description', 'like', '%vue%')
                      ->orWhere('description', 'like', '%node%');
                });
            } elseif ($this->selectedLanguage === 'java') {
                $query->whereHas('currentVersion', function($vq) {
                    $vq->where('title', 'like', '%java%')
                      ->orWhere('title', 'like', '%spring%')
                      ->orWhere('description', 'like', '%java%')
                      ->orWhere('description', 'like', '%spring%')
                      ->where('title', 'not like', '%javascript%');
                });
            }
        }

        if (!empty($this->selectedYear)) {
            // Thay thế checking documents.published_at bằng created_at của current_version_id
            $query->whereHas('currentVersion', function($q) {
                $q->whereYear('reviewed_at', $this->selectedYear);
            });
        }

        if ($this->selectedPrice === 'free') {
            $query->whereDoesntHave('product');
        } elseif ($this->selectedPrice === 'paid') {
            $query->whereHas('product');
        }

        if ($this->sort === 'newest') {
            $query->orderBy('created_at', 'desc');
        } elseif ($this->sort === 'popular') {
            $query->orderBy('download_count', 'desc');
        } elseif ($this->sort === 'highest_rated') {
            $query->orderByRaw('COALESCE((SELECT AVG(dr.rating) FROM document_reviews dr WHERE dr.document_id = documents.id), 0) DESC')
                ->orderBy('download_count', 'desc');
        }

        $documents = $query->with(['author', 'currentVersion', 'currentVersion.category', 'product', 'tags', 'favorites' => function($q) {
            $q->where('user_id', Auth::id());
        }])->paginate(20);

        return view('document::livewire.user.document-list', [
            'documents' => $documents,
            'categories' => $categories
        ])->layout('layouts.user');
    }
}
