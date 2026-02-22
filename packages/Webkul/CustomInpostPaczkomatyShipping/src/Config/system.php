<?php

return [
    [
        'key'    => 'sales.carriers.custom_inpostpaczkomaty_shipping',
        'name'   => 'Custom Inpost Paczkomaty Shipping',
        'info'   => 'Configure the Custom Inpost Paczkomaty Shipping method settings.',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'title',
                'title'         => 'Method Title',
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => true
            ],
            [
                'name'          => 'description', 
                'title'         => 'Description',
                'type'          => 'textarea',
                'channel_based' => true,
                'locale_based'  => false
            ],
            [
                'name'          => 'image',
                'title'         => 'Logo/Image',
                'type'          => 'file',
                'channel_based' => true,
                'locale_based'  => false
            ],
            [
                'name'          => 'default_rate',
                'title'         => 'Base Rate ($)',
                'type'          => 'text',
                'validation'    => 'required|numeric|min:0',
                'channel_based' => true,
                'locale_based'  => false
            ],
            [
                'name'    => 'type',
                'title'   => 'Pricing Type',
                'type'    => 'select',
                'options' => [
                    [
                        'title' => 'Per Order (Flat Rate)',
                        'value' => 'per_order',
                    ],
                    [
                        'title' => 'Per Item',
                        'value' => 'per_unit',
                    ],
                ],
                'channel_based' => true,
                'locale_based'  => false,
            ],
            [
                'name'          => 'active',
                'title'         => 'Enabled',
                'type'          => 'boolean',
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => false
            ]
        ]
    ]
];