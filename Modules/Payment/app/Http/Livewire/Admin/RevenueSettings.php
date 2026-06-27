<?php

namespace Modules\Payment\Http\Livewire\Admin;

use App\Services\SettingService;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class RevenueSettings extends Component
{
    use WireUiActions;

    public $siteName = 'IT Learning';
    public $commissionRate = 70;
    public $minPayoutAmount = 200000;
    public $themePrimaryColor = '#6366f1';
    public $smtpFromEmail = 'no-reply@myapp.com';

    public $platformFeePercent = 20;
    public $payoutMinimum = 50000;
    public $vipMonthlyPrice = 100000;
    public $vipYearlyPrice = 1000000;

    public function mount()
    {
        $settings = DB::table('settings')
            ->pluck('value', 'key')
            ->toArray();

        $this->siteName = $settings['site_name'] ?? 'IT Learning';
        $this->commissionRate = (int) ($settings['commission_rate'] ?? 70);
        $this->minPayoutAmount = (int) ($settings['min_payout_amount'] ?? 200000);
        $this->themePrimaryColor = $settings['theme_primary_color'] ?? '#6366f1';
        $this->smtpFromEmail = $settings['smtp_from_email'] ?? 'no-reply@myapp.com';

        $this->platformFeePercent = (int) ($settings['platform_fee_percent'] ?? (100 - $this->commissionRate));
        $this->payoutMinimum = (int) ($settings['payout_minimum'] ?? $this->minPayoutAmount);
        $this->vipMonthlyPrice = (int) ($settings['vip_monthly_price'] ?? 100000);
        $this->vipYearlyPrice = (int) ($settings['vip_yearly_price'] ?? 1000000);
    }

    public function save()
    {
        $this->validate([
            'siteName' => 'required|string|max:255',
            'commissionRate' => 'required|integer|min:0|max:100',
            'minPayoutAmount' => 'required|integer|min:10000|max:10000000',
            'themePrimaryColor' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'smtpFromEmail' => 'required|email|max:255',
            'platformFeePercent' => 'required|integer|min:0|max:100',
            'payoutMinimum' => 'required|integer|min:10000|max:10000000',
            'vipMonthlyPrice' => 'required|integer|min:0|max:10000000',
            'vipYearlyPrice' => 'required|integer|min:0|max:100000000',
        ], [
            'siteName.required' => 'Vui lòng nhập tên website.',
            'commissionRate.required' => 'Vui lòng nhập tỷ lệ chia doanh thu.',
            'commissionRate.max' => 'Tỷ lệ chia doanh thu tối đa 100%.',
            'minPayoutAmount.required' => 'Vui lòng nhập số tiền rút tối thiểu.',
            'minPayoutAmount.min' => 'Số tiền rút tối thiểu từ 10.000đ.',
            'themePrimaryColor.regex' => 'Màu chủ đạo phải đúng định dạng HEX, ví dụ #6366f1.',
            'smtpFromEmail.email' => 'Email gửi hệ thống không hợp lệ.',
            'platformFeePercent.required' => 'Vui lòng nhập phí nền tảng.',
            'platformFeePercent.max' => 'Phí nền tảng tối đa 100%.',
            'payoutMinimum.required' => 'Vui lòng nhập số tiền rút tối thiểu.',
            'payoutMinimum.min' => 'Số tiền rút tối thiểu từ 10.000đ.',
        ]);

        SettingService::set('site_name', $this->siteName, 'general');
        SettingService::set('commission_rate', $this->commissionRate, 'payment');
        SettingService::set('min_payout_amount', $this->minPayoutAmount, 'payment');
        SettingService::set('theme_primary_color', $this->themePrimaryColor, 'theme');
        SettingService::set('smtp_from_email', $this->smtpFromEmail, 'email');

        SettingService::set('platform_fee_percent', $this->platformFeePercent, 'payment');
        SettingService::set('payout_minimum', $this->payoutMinimum, 'payment');
        SettingService::set('vip_monthly_price', $this->vipMonthlyPrice, 'payment');
        SettingService::set('vip_yearly_price', $this->vipYearlyPrice, 'payment');

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
