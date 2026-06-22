<?php

namespace Modules\Payment\Http\Livewire\Contributor;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Payment\Models\PayoutRequest as PayoutRequestModel;
use Modules\Payment\Models\WalletTransaction;
use WireUi\Traits\WireUiActions;

class PayoutRequest extends Component
{
    use WithPagination, WireUiActions;

    // Form
    public $amount;
    public $bank_name;
    public $bank_account_number;
    public $bank_account_name;
    public $note;

    // Validation
    public $availableBalance;
    public $minimumAmount;
    public $hasPendingRequest = false;

    // Stats
    public $totalEarnings;
    public $totalWithdrawn;
    public $pendingPayouts;

    protected function rules()
    {
        return [
            'amount' => [
                'required',
                'numeric',
                'min:' . $this->minimumAmount,
                'max:' . $this->availableBalance,
            ],
            'bank_name' => 'required|string|max:100',
            'bank_account_number' => 'required|string|max:50',
            'bank_account_name' => 'required|string|max:100',
            'note' => 'nullable|string|max:500',
        ];
    }

    protected $messages = [
        'amount.required' => 'Vui lòng nhập số tiền muốn rút',
        'amount.min' => 'Số tiền tối thiểu là :min đ',
        'amount.max' => 'Số tiền không được vượt quá số dư khả dụng',
        'bank_name.required' => 'Vui lòng chọn ngân hàng',
        'bank_account_number.required' => 'Vui lòng nhập số tài khoản',
        'bank_account_name.required' => 'Vui lòng nhập tên chủ tài khoản',
    ];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $user = Auth::user();
        $this->availableBalance = $user->contributor_balance ?? 0;
        $this->minimumAmount = config('payment.contributor.payout.minimum_amount', 50000);
        
        // Stats calculations
        $this->totalEarnings = WalletTransaction::where('user_id', $user->id)
            ->where('type', 'earning')
            ->sum('amount');
        
        $this->totalWithdrawn = PayoutRequestModel::where('user_id', $user->id)
            ->where('status', 'completed')
            ->sum('amount');
        
        $this->pendingPayouts = PayoutRequestModel::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'approved'])
            ->sum('amount');
        
        // Allow multiple pending requests
        $this->hasPendingRequest = false;
    }

    public function submitPayoutRequest()
    {
        $this->validate();

        try {
            DB::transaction(function() {
                $user = Auth::user();
                
                // Create payout request
                $payout = PayoutRequestModel::create([
                    'user_id' => $user->id,
                    'amount' => $this->amount,
                    'bank_name' => $this->bank_name,
                    'bank_account_number' => $this->bank_account_number,
                    'bank_account_name' => $this->bank_account_name,
                    'note' => $this->note,
                    'status' => 'pending',
                ]);
                
                // Create pending transaction record
                WalletTransaction::create([
                    'user_id' => $user->id,
                    'type' => 'payout_pending',
                    'amount' => -$this->amount,
                    'balance_before' => $user->contributor_balance,
                    'balance_after' => $user->contributor_balance, // Not deducted yet
                    'reference_type' => 'payout_request',
                    'reference_id' => $payout->id,
                    'note' => 'Yêu cầu rút tiền #' . $payout->id,
                ]);
            });

            // Reset form
            $this->reset(['amount', 'bank_name', 'bank_account_number', 'bank_account_name', 'note']);
            
            // Reload data
            $this->loadData();
            $this->resetPage();

            $this->notification()->success(
                'Thành công!',
                'Yêu cầu rút tiền đã được gửi. Chúng tôi sẽ xử lý trong 1-3 ngày làm việc.'
            );

        } catch (\Exception $e) {
            $this->notification()->error(
                'Lỗi',
                'Có lỗi xảy ra: ' . $e->getMessage()
            );
        }
    }

    public function cancelRequest($id)
    {
        $payout = PayoutRequestModel::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->first();

        if (!$payout) {
            $this->notification()->error('Không thể hủy', 'Yêu cầu không tồn tại hoặc đã được xử lý');
            return;
        }

        try {
            DB::transaction(function() use ($payout) {
                $payout->update([
                    'status' => 'cancelled',
                    'note' => 'Người dùng tự hủy',
                ]);

                // Update the pending transaction
                WalletTransaction::where('reference_type', 'payout_request')
                    ->where('reference_id', $payout->id)
                    ->update([
                        'note' => 'Yêu cầu rút tiền #' . $payout->id . ' (Đã hủy)',
                    ]);
            });

            $this->loadData();
            $this->notification()->success('Đã hủy yêu cầu rút tiền');

        } catch (\Exception $e) {
            $this->notification()->error('Lỗi', $e->getMessage());
        }
    }

    public function getPayoutHistoryProperty()
    {
        return PayoutRequestModel::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function render()
    {
        return view('payment::livewire.contributor.payout-request', [
            'payoutHistory' => $this->payoutHistory,
        ])->layout('layouts.contributor');
    }
}
