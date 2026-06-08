<?php

namespace Modules\Document\Http\Livewire\User;

use Livewire\Component;
use Modules\Document\Models\Document;
use Modules\Document\Models\DocumentFavorite;
use Modules\Document\Models\DocumentReview;
use Modules\Document\Models\DocumentDownload;
use Modules\Payment\Models\DocumentAccess;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentDetail extends Component
{
    public $documentId;
    public $rating = 5;
    public $reviewContent = '';
    public $zipFiles = [];
    public $showReportModal = false;
    public $reportReason = 'Bản quyền';
    public $reportDetails = '';

    public function mount($id)
    {
        $this->documentId = $id;

        $doc = Document::find($id);
        if (!$doc) {
            abort(404);
        }

        // Increment view count
        $doc->increment('view_count');

        // Load real ZIP contents if it is a ZIP format
        if ($doc->file_type === 'zip') {
            $useWatermarked = ($doc->watermark_status === 'success' && $doc->file_watermarked_path);
            $filePath = storage_path('app/public/' . ($useWatermarked ? $doc->file_watermarked_path : $doc->file_original_path));
            if (file_exists($filePath)) {
                $zip = new \ZipArchive();
                if ($zip->open($filePath) === TRUE) {
                    for ($i = 0; $i < $zip->numFiles; $i++) {
                        $name = $zip->getNameIndex($i);
                        // Skip folder directories
                        if (substr($name, -1) === '/') continue;

                        $content = $zip->getFromIndex($i);
                        // Limit size to prevent memory crash on very large files
                        if (strlen($content) > 50000) {
                            $content = substr($content, 0, 50000) . "\n\n... [Nội dung tệp quá dài, vui lòng tải xuống để xem đầy đủ]";
                        }

                        $this->zipFiles[$name] = [
                            'isDir' => false,
                            'content' => $content
                        ];
                    }
                    $zip->close();
                }
            }

            // Fallback mock files for demonstration if the actual file does not exist
            if (empty($this->zipFiles)) {
                $this->zipFiles = [
                    'README.md' => [
                        'isDir' => false,
                        'content' => "# Hướng dẫn cài đặt và sử dụng\n\n1. Yêu cầu hệ thống:\n- PHP >= 8.2\n- MySQL >= 8.0\n\n2. Cài đặt các thư viện:\n\$ composer install\n\$ npm install\n\n3. Chạy ứng dụng:\n\$ php artisan serve\n\nChúc các bạn triển khai thành công!"
                    ],
                    'app/Http/Controllers/ProductController.php' => [
                        'isDir' => false,
                        'content' => "<?php\n\nnamespace App\\Http\\Controllers;\n\nuse App\\Models\\Product;\nuse Illuminate\\Http\\Request;\n\nclass ProductController extends Controller\n{\n    public function index()\n    {\n        \$products = Product::paginate(12);\n        return view('products.index', compact('products'));\n    }\n}"
                    ],
                    'app/Models/Product.php' => [
                        'isDir' => false,
                        'content' => "<?php\n\nnamespace App\\Models;\n\nuse Illuminate\\Database\\Eloquent\\Model;\n\nclass Product extends Model\n{\n    protected \$fillable = ['name', 'price', 'description', 'stock'];\n}"
                    ],
                    'config/database.php' => [
                        'isDir' => false,
                        'content' => "<?php\n\nreturn [\n    'default' => env('DB_CONNECTION', 'mysql'),\n    'connections' => [\n        'mysql' => [\n            'driver' => 'mysql',\n            'host' => env('DB_HOST', '127.0.0.1'),\n            'database' => env('DB_DATABASE', 'forge'),\n        ]\n    ]\n];"
                    ],
                    'database/migrations/create_products_table.php' => [
                        'isDir' => false,
                        'content' => "<?php\n\nuse Illuminate\\Database\\Migrations\\Migration;\nuse Illuminate\\Database\\Schema\\Blueprint;\nuse Illuminate\\Support\\Facades\\Schema;\n\nreturn new class extends Migration {\n    public function up() {\n        Schema::create('products', function (Blueprint \$table) {\n            \$table->id();\n            \$table->string('name');\n            \$table->decimal('price', 10, 2);\n            \$table->timestamps();\n        });\n    }\n};"
                    ]
                ];
            }
        }
    }

    public function toggleFavorite()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userId = Auth::id();
        $doc = Document::find($this->documentId);
        if (!$doc) return;

        $fav = DocumentFavorite::where('document_id', $this->documentId)->where('user_id', $userId)->first();

        if ($fav) {
            $fav->delete();
            $doc->decrement('favorite_count');
            $this->dispatch('notify', ['type' => 'info', 'message' => 'Đã bỏ lưu tài liệu']);
        } else {
            DocumentFavorite::create([
                'document_id' => $this->documentId,
                'user_id' => $userId
            ]);
            $doc->increment('favorite_count');
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Đã lưu tài liệu vào danh sách yêu thích']);
        }
    }

    public function download()
    {
        $doc = Document::find($this->documentId);
        if (!$doc) return;

        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Check is_downloadable flag
        if (!$doc->is_downloadable) {
            $this->dispatch('notify', ['type' => 'info', 'message' => 'Tài liệu này chỉ hỗ trợ xem online, không cho phép tải xuống.']);
            return;
        }

        $userId = Auth::id();

        $isPaid = (bool)$doc->product;
        $hasAccess = false;
        $orderItemId = null;

        if ($isPaid) {
            $access = DocumentAccess::where('user_id', $userId)
                ->where('document_id', $doc->id)
                ->first();

            if ($access) {
                $hasAccess = true;
                $orderItemId = $access->order_item_id;
            } else {
                $this->dispatch('notify', ['type' => 'info', 'message' => 'Bạn cần mua tài liệu này trước khi tải xuống.']);
                return;
            }
        } else {
            // Free document
            $hasAccess = true;
        }

        if ($hasAccess) {
            $token = \Illuminate\Support\Str::random(40);

            \Illuminate\Support\Facades\Cache::put("doc_download_{$token}", [
                'document_id' => $doc->id,
                'user_id' => $userId,
                'order_item_id' => $orderItemId,
                'ip' => request()->ip()
            ], now()->addMinutes(30));

            $this->dispatch('notify', ['type' => 'success', 'message' => 'Liên kết tải xuống đã được tạo. Đang tải...']);

            return redirect()->route('documents.download', ['token' => $token]);
        }
    }

    public function submitReview()
    {
        if (!Auth::check()) {
            $this->dispatch('notify', ['type' => 'info', 'message' => 'Vui lòng đăng nhập để đánh giá.']);
            return;
        }

        $userId = Auth::id();

        // Check if uploader downloaded it
        $hasDownloaded = DocumentDownload::where('document_id', $this->documentId)
            ->where('user_id', $userId)
            ->exists();

        if (!$hasDownloaded) {
            $this->dispatch('notify', ['type' => 'info', 'message' => 'Chỉ những ai đã tải tài liệu mới có thể đánh giá và nhận xét.']);
            return;
        }

        // Check if already reviewed
        $hasReviewed = DocumentReview::where('document_id', $this->documentId)
            ->where('user_id', $userId)
            ->exists();

        if ($hasReviewed) {
            $this->dispatch('notify', ['type' => 'info', 'message' => 'Bạn đã đánh giá tài liệu này rồi.']);
            return;
        }

        $this->validate([
            'rating' => 'required|integer|between:1,5',
            'reviewContent' => 'required|string|min:20|max:500',
        ], [
            'reviewContent.min' => 'Nhận xét phải có độ dài tối thiểu 20 ký tự.',
            'reviewContent.max' => 'Nhận xét có độ dài tối đa là 500 ký tự.',
        ]);

        DocumentReview::create([
            'document_id' => $this->documentId,
            'user_id' => $userId,
            'rating' => $this->rating,
            'review' => $this->reviewContent,
            'status' => 'visible',
        ]);

        $this->reviewContent = '';
        $this->rating = 5;

        $this->dispatch('notify', ['type' => 'success', 'message' => 'Gửi đánh giá thành công!']);
    }

    public function buyDocument()
    {
        // Simulate purchase for the sake of demo/testing, creating the DocumentAccess record
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $doc = Document::find($this->documentId);
        if (!$doc || !$doc->product) return;

        // Create document access directly (Mocking successful Payment)
        DocumentAccess::updateOrCreate(
            ['user_id' => Auth::id(), 'document_id' => $doc->id],
            ['access_type' => 'purchased']
        );

        $this->dispatch('notify', ['type' => 'success', 'message' => 'Thanh toán thành công! Bạn hiện đã có quyền truy cập tài liệu này.']);
    }

    public function openReportModal()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $this->reportReason = 'Bản quyền';
        $this->reportDetails = '';
        $this->showReportModal = true;
    }

    public function closeReportModal()
    {
        $this->showReportModal = false;
    }

    public function submitReport()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $this->validate([
            'reportReason' => 'required|string',
            'reportDetails' => 'nullable|string|max:1000',
        ]);

        \Modules\Document\Models\DocumentReport::create([
            'user_id' => Auth::id(),
            'document_id' => $this->documentId,
            'reason' => $this->reportReason,
            'details' => $this->reportDetails,
            'status' => 'pending'
        ]);

        $this->showReportModal = false;
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Báo cáo vi phạm đã được gửi thành công. Admin sẽ kiểm duyệt tệp này.']);
    }

    public function render()
    {
        $doc = Document::with(['author', 'category', 'product', 'tags', 'reviews.user', 'favorites' => function($q) {
            $q->where('user_id', Auth::id());
        }])->find($this->documentId);

        $isBookmarked = Auth::check() && $doc->favorites->isNotEmpty();

        $hasAccess = false;
        if (Auth::check()) {
            if (!$doc->product) {
                $hasAccess = true;
            } else {
                $hasAccess = DocumentAccess::where('user_id', Auth::id())
                    ->where('document_id', $doc->id)
                    ->exists();
            }
        }

        $hasDownloaded = Auth::check() && DocumentDownload::where('document_id', $doc->id)
            ->where('user_id', Auth::id())
            ->exists();

        $hasReviewed = Auth::check() && DocumentReview::where('document_id', $doc->id)
            ->where('user_id', Auth::id())
            ->exists();

        // Calculate average stars
        $avgRating = $doc->reviews->where('status', 'visible')->avg('rating') ?? 0;
        $totalReviews = $doc->reviews->where('status', 'visible')->count();

        // Related documents
        $relatedDocuments = Document::where('status', 'approved')
            ->where('visibility', 'public')
            ->where('category_id', $doc->category_id)
            ->where('id', '!=', $doc->id)
            ->orderBy('download_count', 'desc')
            ->limit(4)
            ->get();

        return view('document::livewire.user.document-detail', [
            'doc' => $doc,
            'isBookmarked' => $isBookmarked,
            'hasAccess' => $hasAccess,
            'hasDownloaded' => $hasDownloaded,
            'hasReviewed' => $hasReviewed,
            'avgRating' => round($avgRating, 1),
            'totalReviews' => $totalReviews,
            'relatedDocuments' => $relatedDocuments
        ])->layout('layouts.user');
    }
}
