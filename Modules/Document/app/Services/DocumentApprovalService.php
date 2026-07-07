<?php

namespace Modules\Document\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Document\Events\DocumentApproved;
use Modules\Document\Events\DocumentRejected;
use Modules\Document\Models\Document;
use Modules\Payment\Models\Product;

class DocumentApprovalService
{
    /**
     * Approve a document's pending version
     *
     * @param int $documentId
     * @return bool
     * @throws \Exception
     */
    public function approve(int $documentId): bool
    {
        $doc = Document::with(['pendingVersion'])->lockForUpdate()->find($documentId);

        if (! $doc) {
            throw new \Exception('Không tìm thấy tài liệu.');
        }

        $pendingVersion = $doc->pendingVersion;

        if (! $pendingVersion) {
            throw new \Exception('Không tìm thấy phiên bản đang chờ duyệt.');
        }

        DB::transaction(function () use ($doc, $pendingVersion) {
            // Mark the pending version as approved
            $pendingVersion->update([
                'status' => 'approved',
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
            ]);

            // Merge version data into the main document record
            $doc->update([
                'status' => 'approved',
                'current_version_id' => $pendingVersion->id,
            ]);

            // Handle product based on version price
            if ($pendingVersion->price > 0) {
                Product::updateOrCreate(
                    ['document_id' => $doc->id],
                    [
                        'name' => $pendingVersion->title, 
                        'price' => $pendingVersion->price, 
                        'sale_price' => $pendingVersion->sale_price ?: null, 
                        'is_active' => true
                    ]
                );
            } else {
                Product::where('document_id', $doc->id)->delete();
            }

            // Fire event
            event(new DocumentApproved($doc));
        });

        return true;
    }

    /**
     * Reject a document's pending version
     *
     * @param int $documentId
     * @param string $reason
     * @return bool
     * @throws \Exception
     */
    public function reject(int $documentId, string $reason): bool
    {
        $doc = Document::with(['pendingVersion'])->lockForUpdate()->find($documentId);

        if (! $doc) {
            throw new \Exception('Không tìm thấy tài liệu.');
        }

        $pendingVersion = $doc->pendingVersion;

        DB::transaction(function () use ($doc, $pendingVersion, $reason) {
            if ($pendingVersion) {
                // Reject the pending version
                $pendingVersion->update([
                    'status' => 'rejected',
                    'rejected_reason' => $reason,
                    'reviewed_by' => Auth::id(),
                    'reviewed_at' => now(),
                ]);

                // If this is Version 1 (first submission), also reject the document itself
                if ($pendingVersion->version_number === 1 && $doc->status === 'pending') {
                    $doc->update([
                        'status' => 'rejected',
                    ]);
                }
            } else {
                // No pending version found - fallback: reject document directly
                $doc->update(['status' => 'rejected']);
            }

            // Fire event
            event(new DocumentRejected($doc, $reason));
        });

        return true;
    }
}
