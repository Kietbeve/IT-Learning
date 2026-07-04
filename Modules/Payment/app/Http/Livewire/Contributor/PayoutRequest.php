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

    // Receipt modal
    public $selectedReceiptUrl = null;
    public $showReceiptModal = false;

    protected function rules()
    {
        return [
            'amount' => [
                'required',
                'integer',
                'min:' . $this->minimumAmount,
                'max:' . $this->availableBalance,
            ],
            'bank_name' => [
                'required',
                'string',
                'min:3',
                'max:100',
                'regex:/^[a-zA-Z0-9\s\-\/]+$/u',
            ],
            'bank_account_number' => [
                'required',
                'string',
                'min:6',
                'max:50',
                'regex:/^[0-9]+$/',
            ],
            'bank_account_name' => [
                'required',
                'string',
                'min:3',
                'max:100',
                'regex:/^[a-zA-ZÀ-ỹ\s]+$/u',
            ],
            'note' => 'nullable|string|max:500',
        ];
    }

    protected function messages()
    {
        return [
            'amount.required' => 'Vui lòng nhập số tiền muốn rút',
            'amount.integer' => 'Số tiền phải là số nguyên',
            'amount.min' => 'Số tiền tối thiểu là ' . number_format($this->minimumAmount) . 'đ',
            'amount.max' => 'Số tiền không được vượt quá số dư khả dụng (' . number_format($this->availableBalance) . 'đ)',
            'bank_name.required' => 'Vui lòng chọn ngân hàng',
            'bank_name.min' => 'Tên ngân hàng phải có ít nhất 3 ký tự',
            'bank_name.regex' => 'Tên ngân hàng chỉ được chứa chữ, số, dấu cách, và dấu gạch ngang',
            'bank_account_number.required' => 'Vui lòng nhập số tài khoản',
            'bank_account_number.min' => 'Số tài khoản phải có ít nhất 6 ký tự',
            'bank_account_number.regex' => 'Số tài khoản chỉ được chứa chữ số',
            'bank_account_name.required' => 'Vui lòng nhập tên chủ tài khoản',
            'bank_account_name.min' => 'Tên chủ tài khoản phải có ít nhất 3 ký tự',
            'bank_account_name.regex' => 'Tên chủ tài khoản chỉ được chứa chữ cái và dấu cách',
        ];
    }

    public function mount()
    {
        $this->loadData();
    }

    public function hydrate()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $user = Auth::user();
        $this->availableBalance = $user->contributor_balance ?? 0;
        $this->minimumAmount = config('payment.contributor.payout.minimum_amount', 50000);
        
        $this->totalEarnings = WalletTransaction::where('user_id', $user->id)
            ->where('type', 'earning')
            ->sum('amount');
        
        $this->totalWithdrawn = PayoutRequestModel::where('user_id', $user->id)
            ->where('status', 'completed')
            ->sum('amount');
        
        $this->pendingPayouts = PayoutRequestModel::where('user_id', $user->id)
            ->where('status', 'pending')
            ->sum('amount');
        
        $this->hasPendingRequest = PayoutRequestModel::where('user_id', $user->id)
            ->where('status', 'pending')
            ->exists();
    }

    public function submitPayoutRequest()
    {
        if ($this->hasPendingRequest) {
            $this->notification()->error(
                'Không thể gửi yêu cầu',
                'Bạn có yêu cầu rút tiền đang chờ xử lý. Vui lòng đợi Admin xử lý trước khi tạo yêu cầu mới.'
            );
            return;
        }

        $this->validate();

        try {
            $user = Auth::user();
            
            PayoutRequestModel::create([
                'user_id' => $user->id,
                'amount' => $this->amount,
                'bank_name' => $this->bank_name,
                'bank_account_number' => $this->bank_account_number,
                'bank_account_name' => $this->bank_account_name,
                'note' => $this->note,
                'status' => 'pending',
            ]);

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
            $payout->update([
                'status' => 'cancelled',
            ]);

            $this->loadData();
            $this->notification()->success('Đã hủy yêu cầu rút tiền');

        } catch (\Exception $e) {
            $this->notification()->error('Lỗi', $e->getMessage());
        }
    }

    public function showReceipt($url)
    {
        $this->selectedReceiptUrl = $url;
        $this->showReceiptModal = true;
    }

    public function closeReceiptModal()
    {
        $this->showReceiptModal = false;
        $this->selectedReceiptUrl = null;
    }

    public function getPayoutHistoryProperty()
    {
        return PayoutRequestModel::with('rejectionTransaction')
            ->where('user_id', Auth::id())
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
