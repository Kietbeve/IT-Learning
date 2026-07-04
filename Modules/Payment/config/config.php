<?php

return [
    'name' => 'Payment',

    'payos' => [
        'client_id' => env('PAYOS_CLIENT_ID'),
        'api_key' => env('PAYOS_API_KEY'),
        'checksum_key' => env('PAYOS_CHECKSUM_KEY'),
        'return_url' => env('PAYOS_RETURN_URL'),
        'cancel_url' => env('PAYOS_CANCEL_URL'),
    ],

    'platform_fee_percent' => env('PLATFORM_FEE_PERCENT', 10),

    'contributor' => [
        'payout' => [
            'minimum_amount' => env('PAYOUT_MINIMUM_AMOUNT', 50000),
            'maximum_amount' => env('PAYOUT_MAXIMUM_AMOUNT', 50000000),
            'processing_days' => env('PAYOUT_PROCESSING_DAYS', 3),
            'daily_limit' => env('PAYOUT_DAILY_LIMIT', 5),
        ],
        'commission_rate' => env('CONTRIBUTOR_COMMISSION_RATE', 70),
    ],
];
