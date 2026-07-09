<?php

namespace App\Livewire;

use Livewire\Component;
use Modules\Payment\Models\PayoutRequest;
use Modules\Document\Models\DocumentReport;
use Modules\Document\Models\Document;

class AdminBadge extends Component
{
    public string $type;

    public function render()
    {
        $count = 0;
        if ($this->type === 'payouts') {
            if (class_exists('\Modules\Payment\Models\PayoutRequest')) {
                $count = PayoutRequest::where('status', 'pending')->count();
            }
        } elseif ($this->type === 'document_reports') {
            if (class_exists('\Modules\Document\Models\DocumentReport')) {
                $count = DocumentReport::where('status', 'pending')->count();
            }
        } elseif ($this->type === 'documents') {
            if (class_exists('\Modules\Document\Models\Document')) {
                $count = Document::whereHas('pendingVersion', function ($sq) { 
                    $sq->where('watermark_status', '!=', 'pending'); 
                })->count();
            }
        }

        return view('livewire.admin-badge', ['count' => $count]);
    }
}
