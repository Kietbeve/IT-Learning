<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Platform Fee Percentage
    |--------------------------------------------------------------------------
    |
    | The percentage of revenue that the platform takes from each transaction.
    | The remaining amount goes to the contributor.
    |
    */
    'platform_fee_percent' => env('PLATFORM_FEE_PERCENT', 10),

    /*
    |--------------------------------------------------------------------------
    | VIP Subscription Packages
    |--------------------------------------------------------------------------
    |
    | Define available VIP subscription packages with pricing, duration,
    | and download quota. Keys are used as package identifiers.
    |
    */
    'packages' => [
        'vip' => [
            'name' => 'VIP Premium',
            'price' => 199000,
            'sale_price' => 99000,
            'duration_days' => 30,
            'download_quota' => 5,
            'description' => 'Tải 5 tài liệu Premium trong 1 tháng',
            'features' => [
                'Tải 5 tài liệu Premium',
                'Tiết kiệm 50% so với mua lẻ',
                'Thời hạn 30 ngày',
                'Hỗ trợ ưu tiên',
            ],
        ],
    ],
];
