<?php

namespace Modules\Payment\Http\Livewire\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Modules\Payment\Models\PayoutRequest;
use Modules\Payment\Models\WalletTransaction;
use WireUi\Traits\WireUiActions;

class PayoutReview extends Component
{
    use WireUiActions, WithFileUploads, WithPagination;

    public $search = '';

    public $statusFilter = 'pending';

    public $sortField = 'created_at';

    public $sortDirection = 'asc';

    public $selectedPayoutId = null;

    public $rejectionReason = '';

    public $receiptImage = null;

    public $showApproveModal = false;

    public $showRejectModal = false;

    public $showDetailModal = false;

    public $detailPayout = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => 'pending'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function openApproveModal($id)
    {
        $this->selectedPayoutId = $id;
        $this->receiptImage = null;
        $this->showApproveModal = true;
    }

    public function openRejectModal($id)
    {
        $this->selectedPayoutId = $id;
        $this->rejectionReason = '';
        $this->showRejectModal = true;
    }

    public function openDetailModal($id)
    {
        $this->detailPayout = PayoutRequest::with(['user', 'processor', 'rejectionTransaction'])->find($id);
        $this->showDetailModal = true;
    }

    public function closeDetailModal()
    {
        $this->showDetailModal = false;
        $this->detailPayout = null;
    }

    public function approvePayout()
    {
        $this->validate([
            'receiptImage' => 'nullable|image|max:5120',
        ], [
            'receiptImage.image' => 'File phải là hình ảnh.',
            'receiptImage.max' => 'Kích thước tối đa 5MB.',
        ]);

        try {
            DB::beginTransaction();

            $payout = PayoutRequest::with('user')
                ->lockForUpdate()
                ->find($this->selectedPayoutId);

            if (! $payout || $payout->status !== 'pending') {
                $this->showApproveModal = false;
                $this->notification()->error(
                    title: 'Lỗi',
                    description: 'Yêu cầu không hợp lệ hoặc đã được xử lý.'
                );

                return;
            }

            $user = $payout->user;

            if ($user->contributor_balance < $payout->amount) {
                $this->showApproveModal = false;
                $this->notification()->error(
                    title: 'Lỗi',
                    description: 'Số dư contributor không đủ.'
                );
                DB::rollBack();

                return;
            }

            $receiptPath = null;
            if ($this->receiptImage) {
                $receiptName = 'receipt_'.$payout->id.'_'.time().'.'.$this->receiptImage->extension();
                $receiptPath = 'payout_receipts/'.$receiptName;
                Storage::disk('r2')->put($receiptPath, file_get_contents($this->receiptImage->getRealPath()));
            }

            $balanceBefore = $user->contributor_balance;
            $balanceAfter = $balanceBefore - $payout->amount;

            $user->update(['contributor_balance' => $balanceAfter]);

            $payout->update([
                'status' => 'completed',
                'processed_by' => Auth::id(),
                'processed_at' => now(),
                'receipt_image' => $receiptPath,
            ]);

            WalletTransaction::create([
                'user_id' => $user->id,
                'type' => 'payout',
                'amount' => $payout->amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'reference_type' => 'payout_request',
                'reference_id' => $payout->id,
                'note' => 'Rút tiền đã được duyệt #'.$payout->id,
                'created_by' => Auth::id(),
                'created_at' => now(),
            ]);

            DB::commit();

            // Send Notification
            $user->notify(new \Modules\Payment\Notifications\PayoutApprovedNotification($payout));

            $this->showApproveModal = false;
            $this->notification()->success(
                title: 'Thành công',
                description: 'Đã duyệt yêu cầu rút tiền thành công.'
            );

            $this->selectedPayoutId = null;
            $this->receiptImage = null;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->showApproveModal = false;
            $this->notification()->error(
                title: 'Lỗi',
                description: 'Lỗi: '.$e->getMessage()
            );
        }
    }

    public function rejectPayout()
    {
        $this->validate([
            'rejectionReason' => 'required|string|min:10|max:500',
        ], [
            'rejectionReason.required' => 'Vui lòng nhập lý do từ chối.',
            'rejectionReason.min' => 'Lý do phải có ít nhất 10 ký tự.',
        ]);

        try {
            DB::beginTransaction();

            $payout = PayoutRequest::with('user')
                ->lockForUpdate()
                ->find($this->selectedPayoutId);

            if (! $payout || $payout->status !== 'pending') {
                $this->showRejectModal = false;
                $this->notification()->error(
                    title: 'Lỗi',
                    description: 'Yêu cầu không hợp lệ hoặc đã được xử lý.'
                );

                return;
            }

            $user = $payout->user;

            $payout->update([
                'status' => 'rejected',
                'processed_by' => Auth::id(),
                'processed_at' => now(),
            ]);

            WalletTransaction::create([
                'user_id' => $user->id,
                'type' => 'payout_rejected',
                'amount' => 0,
                'balance_before' => $user->contributor_balance,
                'balance_after' => $user->contributor_balance,
                'reference_type' => 'payout_request',
                'reference_id' => $payout->id,
                'note' => $this->rejectionReason,
                'created_by' => Auth::id(),
                'created_at' => now(),
            ]);

            DB::commit();

            // Send Notification
            $user->notify(new \Modules\Payment\Notifications\PayoutRejectedNotification($payout));

            $this->showRejectModal = false;
            $this->notification()->success(
                title: 'Thành công',
                description: 'Đã từ chối yêu cầu rút tiền.'
            );

            $this->selectedPayoutId = null;
            $this->rejectionReason = '';

        } catch (\Exception $e) {
            DB::rollBack();
            $this->showRejectModal = false;
            $this->notification()->error(
                title: 'Lỗi',
                description: 'Lỗi: '.$e->getMessage()
            );
        }
    }

    public function render()
    {
        $query = PayoutRequest::with('user', 'rejectionTransaction');

        if (! empty($this->search)) {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('email', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        $payouts = $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);

        $stats = [
            'pending_count' => PayoutRequest::where('status', 'pending')->count(),
            'pending_amount' => PayoutRequest::where('status', 'pending')->sum('amount'),
            'completed_today' => PayoutRequest::where('status', 'completed')
                ->whereDate('processed_at', today())
                ->count(),
            'completed_today_amount' => PayoutRequest::where('status', 'completed')
                ->whereDate('processed_at', today())
                ->sum('amount'),
            'total_paid_count' => PayoutRequest::where('status', 'completed')->count(),
            'total_paid' => PayoutRequest::where('status', 'completed')->sum('amount'),
        ];

        return view('payment::livewire.admin.payout-review', [
            'payouts' => $payouts,
            'stats' => $stats,
        ])->layout('layouts.admin', [
            'pageTitle' => 'Duyệt yêu cầu rút tiền',
        ]);
    }
}
