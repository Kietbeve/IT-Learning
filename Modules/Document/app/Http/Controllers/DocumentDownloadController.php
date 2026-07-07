<?php

namespace Modules\Document\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Modules\Document\Models\Document;
use Modules\Document\Models\DocumentDownload;

class DocumentDownloadController extends Controller
{
    public function download(Request $request, $token)
    {
        // 1. Retrieve the token from Cache
        $cacheKey = "doc_download_{$token}";
        $data = Cache::get($cacheKey);

        if (! $data) {
            return response('
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Link tải đã hết hạn - IT-Learning</title>
                    <meta charset="utf-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1">
                    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
                    <script src="https://cdn.tailwindcss.com"></script>
                    <style>body { font-family: "Figtree", sans-serif; }</style>
                </head>
                <body class="bg-slate-50 flex items-center justify-center min-h-screen p-4">
                    <div class="max-w-md w-full bg-white border border-slate-200 shadow-xl rounded-[2.5rem] p-8 text-center space-y-6">
                        <div class="h-16 w-16 bg-rose-50 text-rose-500 rounded-full flex items-center justify-center mx-auto">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <h1 class="text-xl font-bold text-slate-900">Liên kết đã hết hạn hoặc không hợp lệ</h1>
                        <p class="text-sm text-slate-500 leading-relaxed">Vì lý do bảo mật, liên kết tải xuống tài nguyên chỉ có hiệu lực 1 lần duy nhất và hết hạn sau 30 phút. Vui lòng quay lại trang chi tiết tài liệu để tạo liên kết mới.</p>
                        <button onclick="window.history.back()" class="w-full rounded-2xl bg-slate-900 hover:bg-slate-800 text-white py-3.5 text-sm font-semibold transition-colors">Quay lại trang tài liệu</button>
                    </div>
                </body>
                </html>
            ', 403);
        }

        // Token is one-time use, remove it immediately
        Cache::forget($cacheKey);

        $docId = $data['document_id'];
        $userId = $data['user_id'];
        $ip = $data['ip'];
        $orderItemId = $data['order_item_id'] ?? null;

        $doc = Document::with('author')->find($docId);
        if (! $doc) {
            abort(404, 'Tài liệu không tồn tại.');
        }

        // 2. Check visibility (doc might have been made private after page loaded)
        if ($doc->visibility === 'private' && $doc->author_id !== $userId) {
            abort(403, 'Tài liệu này hiện đang ở chế độ riêng tư.');
        }

        // 3. Double-check authentication for download
        if (! $userId) {
            return response('
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Yêu cầu đăng nhập - IT-Learning</title>
                    <meta charset="utf-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1">
                    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
                    <script src="https://cdn.tailwindcss.com"></script>
                    <style>body { font-family: "Figtree", sans-serif; }</style>
                </head>
                <body class="bg-slate-50 flex items-center justify-center min-h-screen p-4">
                    <div class="max-w-md w-full bg-white border border-slate-200 shadow-xl rounded-[2.5rem] p-8 text-center space-y-6">
                        <div class="h-16 w-16 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mx-auto">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3 3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                        </div>
                        <h1 class="text-xl font-bold text-slate-900">Yêu cầu đăng nhập</h1>
                        <p class="text-sm text-slate-500 leading-relaxed">Bạn cần đăng nhập tài khoản để có thể tải tài nguyên từ hệ thống.</p>
                        <a href="/login" class="block w-full rounded-2xl bg-slate-900 hover:bg-slate-800 text-white py-3.5 text-sm font-semibold transition-colors text-center">Đăng nhập ngay</a>
                    </div>
                </body>
                </html>
            ', 403);
        }

        $isPaid = (bool) ($doc->product && $doc->product->is_active);

        // 3. Log download (skip if already logged in last 30 seconds to avoid duplicates)
        $recentDownload = DocumentDownload::where('document_id', $doc->id)
            ->where('user_id', $userId)
            ->where('downloaded_at', '>', now()->subSeconds(30))
            ->exists();

        if (! $recentDownload) {
            DocumentDownload::create([
                'document_id' => $doc->id,
                'user_id' => $userId,
                'order_item_id' => $orderItemId,
                'ip_address' => $ip,
                'user_agent' => $request->userAgent(),
                'source' => $isPaid ? 'paid' : ($userId ? 'free' : 'guest'),
                'downloaded_at' => now(),
            ]);
        }

        // 4. Increment download count (without updating updated_at timestamp)
        DB::table('documents')
            ->where('id', $doc->id)
            ->increment('download_count');

        // 5. Download the file — prefer watermarked version only if watermark succeeded
        if ($doc->watermark_status === 'success' && $doc->file_watermarked_path) {
            $filePath = $doc->file_watermarked_path;
        } else {
            $filePath = $doc->file_original_path;
        }
        $fileName = $doc->slug.'.'.($doc->file_type ?? 'pdf');

        // R2 path — generate presigned URL (15 minutes expiry) with forced download
        if ($filePath && ! str_starts_with($filePath, 'documents/') && ! str_starts_with($filePath, 'http')) {
            try {
                $bucket = config('filesystems.disks.r2.bucket');
                $key = $filePath;

                // Smart check: if the path doesn't exist, try prepending the bucket prefix for legacy files
                if (! Storage::disk('r2')->exists($key)) {
                    $legacyKey = $bucket.'/'.ltrim($key, '/');
                    if (Storage::disk('r2')->exists($legacyKey)) {
                        $key = $legacyKey;
                    }
                }

                $client = Storage::disk('r2')->getClient();
                $command = $client->getCommand('GetObject', [
                    'Bucket' => $bucket,
                    'Key' => $key,
                    'ResponseContentDisposition' => 'attachment; filename="'.addslashes($fileName).'"',
                ]);
                $request = $client->createPresignedRequest($command, '+15 minutes');
                $presignedUrl = (string) $request->getUri();

                return redirect()->away($presignedUrl);
            } catch (\Exception $e) {
                Log::error('Failed to generate presigned URL: '.$e->getMessage());
            }
        }

        // Local path (legacy)
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            return Storage::disk('public')->download($filePath, $fileName);
        }

        // Full URL or fallback
        if ($filePath && str_starts_with($filePath, 'http')) {
            return redirect()->away($filePath);
        }

        // Simulated streamed download with a nice mockup + watermark header
        return response()->streamDownload(function () use ($doc) {
            echo "=========================================================\n";
            echo "                 HỆ THỐNG IT-LEARNING\n";
            echo "=========================================================\n";
            echo 'Tên tài nguyên: '.$doc->title."\n";
            echo 'Định dạng file: '.strtoupper($doc->file_type)."\n";
            echo 'Đăng bởi tác giả: '.($doc->author?->name ?? 'Uploader')."\n";
            echo 'Năm đăng tải: '.($doc->published_at ? $doc->published_at->year : ($doc->created_at ? $doc->created_at->year : '2026'))."\n";
            echo "---------------------------------------------------------\n";
            echo "Mô tả nội dung:\n".$doc->description."\n";
            echo "---------------------------------------------------------\n";
            echo "[WATERMARK]: Bản quyền tài liệu thuộc về IT-Learning. Nghiêm cấm sao chép, thương mại hóa dưới mọi hình thức.\n";
            echo "=========================================================\n";
        }, $fileName);
    }
}
