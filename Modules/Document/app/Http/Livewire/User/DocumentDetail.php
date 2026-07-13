<?php

namespace Modules\Document\Http\Livewire\User;

use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Attributes\On;
use Modules\Document\Models\Document;
use Modules\Document\Models\DocumentFavorite;
use Modules\Document\Models\DocumentReview;
use Modules\Document\Models\DocumentDownload;
use Modules\Document\Models\DocumentComment;
use Modules\Payment\Models\DocumentAccess;
use Modules\Payment\Models\Order;
use Modules\Payment\Models\OrderItem;
use Modules\Payment\Services\SubscriptionService;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class DocumentDetail extends Component
{
    use \WireUi\Traits\WireUiActions;
    use \Modules\Document\Traits\WithDocumentReviews;
    use \Modules\Document\Traits\WithDocumentComments;
    use \Modules\Document\Traits\WithDocumentDownloads;
    use \Modules\Document\Traits\WithDocumentReporting;
    use \Modules\Document\Traits\WithFileExistence;

    public $documentId;
    protected $zipFiles = [];

    public function mount($id, $slug = null)
    {
        $this->documentId = $id;

        $doc = Document::withTrashed()->with(['currentVersion', 'latestVersion', 'author'])->find($id);
        if (!$doc) {
            abort(404);
        }

        $isAdmin = false;
        if (Auth::check() && Auth::user()->roles()->where('name', 'admin')->exists()) {
            $isAdmin = true;
        }

        $isContributor = Auth::check() && Auth::user()->roles()->where('name', 'contributor')->exists();

        $userId = Auth::id();

        if (!$isAdmin) {
            if ($doc->trashed()) {
                if (!($isContributor && $doc->author_id === $userId)) {
                    abort(404);
                }
            }
            if ($doc->status !== 'approved' && $doc->author_id !== $userId) {
                abort(404);
            }
            if ($doc->visibility === 'private' && (!$userId || $doc->author_id !== $userId)) {
                abort(404);
            }
        }

        $userIdentifier = Auth::check() ? Auth::id() : request()->ip();
        $cacheKey = 'viewed_document_' . $doc->id . '_' . $userIdentifier;
        
        if (!\Illuminate\Support\Facades\Cache::has($cacheKey)) {
            \Illuminate\Support\Facades\DB::table('documents')
                ->where('id', $doc->id)
                ->increment('view_count');
                
            \Illuminate\Support\Facades\Cache::put($cacheKey, true, now()->addMinutes(10));
        }

        if (!Auth::check() && !request()->cookie('guest_device_id')) {
            $deviceId = \Illuminate\Support\Str::uuid()->toString();
            \Illuminate\Support\Facades\Cookie::queue('guest_device_id', $deviceId, 60 * 24 * 365 * 10);
        }
    }

    public function toggleFavorite()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $doc = Document::find($this->documentId);
        if (!$doc) return;

        $doc->toggleFavoriteForUser(Auth::id());
    }

    public function render()
    {
        $doc = Document::withTrashed()->with([
            'author', 'currentVersion.category', 'product', 'tags',
            'reviews.user',
            'comments' => function($q) {
                $q->visible()->whereNull('parent_id')->orderBy('created_at', 'desc');
            },
            'comments.user',
            'comments.replies' => function($q) {
                $q->visible()->orderBy('created_at', 'asc');
            },
            'comments.replies.user',
            'favorites' => function($q) {
                $q->where('user_id', Auth::id());
            }
        ])->find($this->documentId);

        $isBookmarked = Auth::check() && $doc->favorites->isNotEmpty();

        $accessInfo = $this->checkDocumentAccess($doc);
        $hasAccess = $accessInfo['hasAccess'];
        
        $isVip = false;
        if (Auth::check()) {
            $user = Auth::user();
            $isVip = $user->checkAndExpireVip();
        }

        if (!$this->hasDownloaded) {
            $this->hasDownloaded = Auth::check() && (
                DocumentDownload::where('document_id', $doc->id)
                    ->where('user_id', Auth::id())
                    ->exists()
                ||
                DocumentAccess::where('user_id', Auth::id())
                    ->where('document_id', $doc->id)
                    ->exists()
            );
        }

        if (Auth::check()) {
            $this->hasReviewed = DocumentReview::where('document_id', $doc->id)
                ->where('user_id', Auth::id())
                ->exists();
        } else {
            $this->hasReviewed = false;
        }

        $avgRating = $doc->reviews->where('status', 'visible')->avg('rating') ?? 0;
        $totalReviews = $doc->reviews->where('status', 'visible')->count();

        $currentCategoryId = $doc->currentVersion ? $doc->currentVersion->category_id : null;
        $currentSubjectId = $doc->currentVersion ? $doc->currentVersion->subject_id : null;
        $currentTagIds = $doc->tags->pluck('id')->toArray();

        $candidates = Document::where('status', 'approved')
            ->whereHas('currentVersion', function($q) {
                $q->where('visibility', 'public');
            })
            ->where('id', '!=', $doc->id)
            ->where(function ($query) use ($currentCategoryId, $currentSubjectId, $currentTagIds) {
                if ($currentCategoryId || $currentSubjectId) {
                    $query->whereHas('currentVersion', function ($q) use ($currentCategoryId, $currentSubjectId) {
                        $q->where(function($sq) use ($currentCategoryId, $currentSubjectId) {
                            if ($currentCategoryId) {
                                $sq->where('category_id', $currentCategoryId);
                            }
                            if ($currentSubjectId) {
                                $sq->orWhere('subject_id', $currentSubjectId);
                            }
                        });
                    });
                }
                
                if (!empty($currentTagIds)) {
                    $query->orWhereHas('tags', function ($q) use ($currentTagIds) {
                        $q->whereIn('tags.id', $currentTagIds);
                    });
                }
            })
            ->with(['tags', 'author', 'currentVersion'])
            ->get();

        $relatedDocuments = $candidates->map(function ($related) use ($currentCategoryId, $currentSubjectId, $currentTagIds) {
            $score = 0;
            $relatedVer = $related->currentVersion;

            if ($currentCategoryId && $relatedVer && $relatedVer->category_id === $currentCategoryId) {
                $score += 3;
                if ($related->download_count >= 50) {
                    $score += 2;
                } elseif ($related->download_count >= 20) {
                    $score += 1;
                }
            }

            if ($currentSubjectId && $relatedVer && $relatedVer->subject_id === $currentSubjectId) {
                $score += 4;
            }

            if (!empty($currentTagIds)) {
                $relatedTagIds = $related->tags->pluck('id')->toArray();
                $matchingTags = array_intersect($currentTagIds, $relatedTagIds);
                $score += (count($matchingTags) * 5);
            }

            $related->relevance_score = $score;
            return $related;
        })
        ->filter(function ($related) {
            return $related->relevance_score > 0;
        })
        ->sortByDesc(function ($related) {
            return [$related->relevance_score, $related->download_count];
        })
        ->take(4);

        $isAdmin = $this->isAdmin();
        $vipQuota = Auth::check() ? (Auth::user()->vip_download_quota ?? 0) : 0;

        $previewFileExists = false;
        if ($doc->file_type === 'pdf') {
            $activeVer = $doc->currentVersion;
            if ($activeVer) {
                $watermarkedUrl = ($doc->watermark_status === 'success' && $activeVer->file_watermarked_path) ? $activeVer->file_watermarked_path : null;
                $pdfUrlPath = $hasAccess ? ($watermarkedUrl ?? $activeVer->file_original_path) : ($activeVer->preview_file_path ?? $activeVer->file_original_path);
                if ($pdfUrlPath) {
                    $previewFileExists = $this->checkFileExists($pdfUrlPath);
                }
            }
        } else {
            $previewFileExists = true;
        }

        $zipFilesData = [];
        if ($doc->file_type === 'zip') {
            $activeVer = $doc->currentVersion ?? $doc->latestVersion;
            if ($activeVer) {
                $zipService = app(\Modules\Document\Services\ZipPreviewService::class);
                $zipFilesData = $zipService->getZipStructure($doc, $activeVer);
            }
        }
        $this->zipFiles = $zipFilesData;

        $activeVerForTags = $doc->currentVersion ?? $doc->latestVersion;
        $displayTags = collect();
        if ($doc->status !== 'approved' && $activeVerForTags && !empty($activeVerForTags->version_tags)) {
            $selectedTags = $activeVerForTags->version_tags['selectedTags'] ?? [];
            $customTags = $activeVerForTags->version_tags['customTagsInput'] ?? '';
            
            $tagNames = \App\Models\Tag::whereIn('id', $selectedTags)->pluck('name')->toArray();
            if (!empty(trim($customTags))) {
                $tagNames = array_merge($tagNames, array_map('trim', explode(',', $customTags)));
            }
            foreach ($tagNames as $name) {
                if ($name) $displayTags->push((object)['name' => $name]);
            }
        } else {
            $displayTags = clone $doc->tags; 
        }

        return view('document::livewire.user.document-detail', [
            'doc' => $doc,
            'displayTags' => $displayTags,
            'isBookmarked' => $isBookmarked,
            'hasAccess' => $hasAccess,
            'isVip' => $isVip,
            'vipQuota' => $vipQuota,
            'hasDownloaded' => $this->hasDownloaded,
            'hasReviewed' => $this->hasReviewed,
            'avgRating' => round($avgRating, 1),
            'totalReviews' => $totalReviews,
            'comments' => $doc->comments,
            'relatedDocuments' => $relatedDocuments,
            'isAdmin' => $isAdmin,
            'zipFiles' => $this->zipFiles,
            'previewFileExists' => $previewFileExists
        ])->layout('layouts.user', [
            'description' => $doc->short_description ?? Str::limit(strip_tags($doc->description), 160),
            'keywords' => $doc->tags->pluck('name')->implode(', '),
            'author' => $doc->author?->name ?? '',
        ]);
    }

    // checkFileExists() is now provided by WithFileExistence trait

    private function isAdmin()
    {
        $user = Auth::user();
        if (!$user) return false;
        
        return $user->roles()->where('name', 'admin')->exists();
    }

    protected function checkDocumentAccess(Document $doc): array
    {
        $isPaid = (bool)($doc->product && $doc->product->is_active);
        $userId = Auth::id();
        
        $result = [
            'hasAccess' => false,
            'orderItemId' => null,
            'guestOrder' => null,
        ];

        if ($this->isAdmin()) {
            $result['hasAccess'] = true;
            return $result;
        }

        if (!$userId) {
            if (!$isPaid) {
                return $result;
            }

            $deviceId = request()->cookie('guest_device_id');
            if ($deviceId) {
                $guestOrder = \Modules\Payment\Models\Order::whereNull('user_id')
                    ->where('order_type', 'document')
                    ->where('payment_status', 'paid')
                    ->where('guest_device_id', $deviceId)
                    ->whereHas('items', function($q) use ($doc) {
                        $q->where('document_id', $doc->id);
                    })->first();

                if ($guestOrder) {
                    $result['guestOrder'] = $guestOrder;
                    $result['hasAccess'] = true;
                    $result['orderItemId'] = $guestOrder->items->where('document_id', $doc->id)->first()?->id;
                }
            }
        } else {
            if ($userId === $doc->author_id) {
                $result['hasAccess'] = true;
            } elseif ($isPaid) {
                $access = DocumentAccess::where('user_id', $userId)
                    ->where('document_id', $doc->id)
                    ->first();

                if ($access) {
                    $result['orderItemId'] = $access->order_item_id;
                    $result['hasAccess'] = true;
                }
            } else {
                $result['hasAccess'] = true;
            }
        }

        return $result;
    }

    protected function isEditableWithin24Hours($model): bool
    {
        return $model && $model->created_at->diffInHours(now()) < 24;
    }

    protected function getReviewForAuthUser($reviewId)
    {
        return DocumentReview::where('id', $reviewId)
            ->where('user_id', Auth::id())
            ->first();
    }

    protected function getCommentForAuthUser($commentId)
    {
        return DocumentComment::where('id', $commentId)
            ->where('user_id', Auth::id())
            ->first();
    }
}
