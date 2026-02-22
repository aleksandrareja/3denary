<?php

return [
    [
        'key'    => 'sales.payment_methods.custom_przelewy24_payment',
        'name'   => 'Custom Przelewy24 Payment',
        'info'   => 'Custom Przelewy24 Payment Method Configuration',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'active',
                'title'         => 'Status',
                'type'          => 'boolean',
                'default_value' => true,
                'channel_based' => true,
            ],
            [
                'name'          => 'title',
                'title'         => 'Title',
                'type'          => 'text',
                'default_value' => 'Przelewy24',
                'channel_based' => true,
                'locale_based'  => true,
            ],
            [
                'name'          => 'description',
                'title'         => 'Description',
                'type'          => 'textarea',
                'default_value' => 'Zapłać online przez Przelewy24 (BLIK, przelew, karta)',
                'channel_based' => true,
                'locale_based'  => true,
            ],
             [
                'name'          => 'merchant_id',
                'title'         => 'Merchant ID',
                'type'          => 'text',
                'channel_based' => true,
            ],
            [
                'name'          => 'pos_id',
                'title'         => 'POS ID',
                'type'          => 'text',
                'channel_based' => true,
            ],
            [
                'name'          => 'crc',
                'title'         => 'CRC Key',
                'type'          => 'password',
                'channel_based' => true,
            ],
            [
                'name'          => 'api_key',
                'title'         => 'API Key',
                'type'          => 'password',
                'channel_based' => true,
            ],
            [
                'name'          => 'test_mode',
                'title'         => 'Tryb testowy',
                'type'          => 'boolean',
                'default_value' => true,
                'channel_based' => true,
            ],
            [
                'name'          => 'sort',
                'title'         => 'Sort Order',
                'type'          => 'text',
                'default_value' => '1',
            ],
        ],
    ],
];