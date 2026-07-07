<?php

namespace Modules\Payment\Http\Livewire\Admin;

use App\Services\SettingService;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class RevenueSettings extends Component
{
    use WireUiActions;

    public $platformFeePercent = 20;

    public $minPayoutAmount = 200000;

    public $vipPrice = 100000;
    
    public $vipSalePrice = 100000;

    public $vipQuota = 5;

    public function mount()
    {
        $settings = DB::table('settings')
            ->pluck('value', 'key')
            ->toArray();

        $this->platformFeePercent = (int) ($settings['platform_fee_percent'] ?? 20);
        $this->minPayoutAmount = (int) ($settings['min_payout_amount'] ?? 200000);
        $this->vipPrice = (int) ($settings['vip_price'] ?? 100000);
        $this->vipSalePrice = (int) ($settings['vip_sale_price'] ?? 100000);
        $this->vipQuota = (int) ($settings['vip_quota'] ?? 5);
    }

    public function save()
    {
        $this->validate([
            'platformFeePercent' => 'required|integer|min:0|max:100',
            'minPayoutAmount' => 'required|integer|min:10000|max:10000000',
            'vipPrice' => 'required|integer|min:0|max:100000000',
            'vipSalePrice' => 'required|integer|min:0|max:100000000',
            'vipQuota' => 'required|integer|min:1|max:1000',
        ], [
            'platformFeePercent.required' => 'Vui lòng nhập phí nền tảng.',
            'platformFeePercent.max' => 'Phí nền tảng tối đa 100%.',
            'minPayoutAmount.required' => 'Vui lòng nhập số tiền rút tối thiểu.',
            'minPayoutAmount.min' => 'Số tiền rút tối thiểu từ 10.000đ.',
            'vipPrice.required' => 'Vui lòng nhập giá gốc.',
            'vipSalePrice.required' => 'Vui lòng nhập giá khuyến mãi.',
            'vipQuota.required' => 'Vui lòng nhập số lượt tải VIP.',
        ]);

        $commissionRate = 100 - $this->platformFeePercent;

        SettingService::set('platform_fee_percent', $this->platformFeePercent, 'payment');
        SettingService::set('commission_rate', $commissionRate, 'payment');
        SettingService::set('min_payout_amount', $this->minPayoutAmount, 'payment');
        SettingService::set('payout_minimum', $this->minPayoutAmount, 'payment');
        SettingService::set('vip_price', $this->vipPrice, 'payment');
        SettingService::set('vip_sale_price', $this->vipSalePrice, 'payment');
        SettingService::set('vip_quota', $this->vipQuota, 'payment');

        $this->notification()->success(
            title: 'Thành công',
            description: 'Đã lưu cài đặt thành công.'
        );
    }

    public function render()
    {
        return view('payment::livewire.admin.revenue-settings')
            ->layout('layouts.admin', [
                'pageTitle' => 'Cài đặt doanh thu',
            ]);
    }
}
