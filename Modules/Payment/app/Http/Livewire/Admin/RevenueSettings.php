<?php

namespace Modules\Payment\Http\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use WireUi\Traits\WireUiActions;

class RevenueSettings extends Component
{
    use WireUiActions;
    public $platformFeePercent = 20;
    public $payoutMinimum = 50000;
    public $vipMonthlyPrice = 100000;
    public $vipYearlyPrice = 1000000;

    public function mount()
    {
        $settings = DB::table('settings')
            ->where('group', 'payment')
            ->pluck('value', 'key')
            ->toArray();

        $this->platformFeePercent = (int) ($settings['platform_fee_percent'] ?? 20);
        $this->payoutMinimum = (int) ($settings['payout_minimum'] ?? 50000);
        $this->vipMonthlyPrice = (int) ($settings['vip_monthly_price'] ?? 100000);
        $this->vipYearlyPrice = (int) ($settings['vip_yearly_price'] ?? 1000000);
    }

    public function save()
    {
        $this->validate([
            'platformFeePercent' => 'required|integer|min:0|max:100',
            'payoutMinimum' => 'required|integer|min:10000|max:10000000',
            'vipMonthlyPrice' => 'required|integer|min:0|max:10000000',
            'vipYearlyPrice' => 'required|integer|min:0|max:100000000',
        ], [
            'platformFeePercent.required' => 'Vui lòng nhập phí nền tảng.',
            'platformFeePercent.max' => 'Phí nền tảng tối đa 100%.',
            'payoutMinimum.required' => 'Vui lòng nhập số tiền rút tối thiểu.',
            'payoutMinimum.min' => 'Số tiền rút tối thiểu từ 10.000đ.',
        ]);

        $this->upsertSetting('platform_fee_percent', $this->platformFeePercent);
        $this->upsertSetting('payout_minimum', $this->payoutMinimum);
        $this->upsertSetting('vip_monthly_price', $this->vipMonthlyPrice);
        $this->upsertSetting('vip_yearly_price', $this->vipYearlyPrice);

        $this->notification()->success(
            title: 'Thành công',
            description: 'Đã lưu cài đặt thành công.'
        );
    }

    private function upsertSetting($key, $value)
    {
        $exists = DB::table('settings')
            ->where('key', $key)
            ->where('group', 'payment')
            ->exists();

        if ($exists) {
            DB::table('settings')
                ->where('key', $key)
                ->where('group', 'payment')
                ->update([
                    'value' => $value,
                    'updated_at' => now(),
                ]);
        } else {
            DB::table('settings')->insert([
                'key' => $key,
                'value' => $value,
                'group' => 'payment',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function render()
    {
        return view('payment::livewire.admin.revenue-settings')
            ->layout('layouts.admin', [
                'pageTitle' => 'Cài đặt doanh thu',
            ]);
    }
}
