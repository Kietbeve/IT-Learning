<?php

namespace Modules\Payment\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Modules\Payment\Models\PayoutRequest;
use Modules\Payment\Models\WalletTransaction;
use Modules\Auth\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use WireUi\Traits\WireUiActions;

class PayoutReview extends Component
{
    use WithPagination, WithFileUploads, WireUiActions;

    public $search = '';
    public $statusFilter = 'pending';
    public $sortField = 'created_at';
    public $sortDirection = 'asc';

    public $selectedPayoutId = null;
    public $rejectionReason = '';
    public $receiptImage = null;

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
        $this->dispatch('open-modal', 'approve-modal');
    }

    public function openRejectModal($id)
    {
        $this->selectedPayoutId = $id;
        $this->rejectionReason = '';
        $this->dispatch('open-modal', 'reject-modal');
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

            $payout = PayoutRequest::with('user')->find($this->selectedPayoutId);
            
            if (!$payout || $payout->status !== 'pending') {
                $this->dispatch('close-modal', 'approve-modal');
                $this->notification()->error(
                    title: 'Lỗi',
                    description: 'Yêu cầu không hợp lệ hoặc đã được xử lý.'
                );
                return;
            }

            $user = $payout->user;

            if ($user->contributor_balance < $payout->amount) {
                $this->dispatch('close-modal', 'approve-modal');
                $this->notification()->error(
                    title: 'Lỗi',
                    description: 'Số dư contributor không đủ.'
                );
                DB::rollBack();
                return;
            }

            $receiptPath = null;
            if ($this->receiptImage) {
                $receiptName = 'receipt_' . $payout->id . '_' . time() . '.' . $this->receiptImage->extension();
                $receiptPath = $this->receiptImage->storeAs('payout_receipts', $receiptName, 'public');
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
                'amount' => -$payout->amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'reference_type' => 'payout_request',
                'reference_id' => $payout->id,
                'note' => 'Rút tiền đã được duyệt #' . $payout->id,
                'created_by' => Auth::id(),
                'created_at' => now(),
            ]);

            DB::commit();

            $this->dispatch('close-modal', 'approve-modal');
            $this->notification()->success(
                title: 'Thành công',
                description: 'Đã duyệt yêu cầu rút tiền thành công.'
            );
            
            $this->selectedPayoutId = null;
            $this->receiptImage = null;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('close-modal', 'approve-modal');
            $this->notification()->error(
                title: 'Lỗi',
                description: 'Lỗi: ' . $e->getMessage()
            );
        }
    }

    public function rejectPayout()
    {
        $this->validate([
            'rejectionReason' => 'required|string|min:10|max:500'
        ], [
            'rejectionReason.required' => 'Vui lòng nhập lý do từ chối.',
            'rejectionReason.min' => 'Lý do phải có ít nhất 10 ký tự.',
        ]);

        try {
            $payout = PayoutRequest::find($this->selectedPayoutId);
            
            if (!$payout || $payout->status !== 'pending') {
                $this->dispatch('close-modal', 'reject-modal');
                $this->notification()->error(
                    title: 'Lỗi',
                    description: 'Yêu cầu không hợp lệ hoặc đã được xử lý.'
                );
                return;
            }

            $payout->update([
                'status' => 'rejected',
                'note' => $this->rejectionReason,
                'processed_by' => Auth::id(),
                'processed_at' => now(),
            ]);

            $this->dispatch('close-modal', 'reject-modal');
            $this->notification()->success(
                title: 'Thành công',
                description: 'Đã từ chối yêu cầu rút tiền.'
            );
            
            $this->selectedPayoutId = null;
            $this->rejectionReason = '';

        } catch (\Exception $e) {
            $this->dispatch('close-modal', 'reject-modal');
            $this->notification()->error(
                title: 'Lỗi',
                description: 'Lỗi: ' . $e->getMessage()
            );
        }
    }

    public function render()
    {
        $query = PayoutRequest::with('user');

        if (!empty($this->search)) {
            $query->whereHas('user', function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
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
