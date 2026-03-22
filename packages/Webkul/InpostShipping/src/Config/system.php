<?php

return [
    [
        'key'    => 'sales.carriers.inpost',
        'name'   => 'admin::app.configuration.index.sales.shipping-methods.inpost.title',
        'info'   => 'admin::app.configuration.index.sales.shipping-methods.inpost.info',
        'sort'   => 5,
        'fields' => [
            [
                'name'          => 'title',
                'title'         => 'admin::app.configuration.index.sales.shipping-methods.inpost.title-field',
                'type'          => 'text',
                'value'         => 'InPost Paczkomat',
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => true,
            ],
            [
                'name'          => 'description',
                'title'         => 'admin::app.configuration.index.sales.shipping-methods.inpost.description',
                'type'          => 'textarea',
                'value'         => 'Dostawa do paczkomatu InPost w całej Polsce',
                'channel_based' => true,
                'locale_based'  => false,
            ],
            [
                'name'          => 'default_rate',
                'title'         => 'admin::app.configuration.index.sales.shipping-methods.inpost.rate',
                'type'          => 'text',
                'value'         => '9.99',
                'validation'    => 'required|numeric|min:0',
                'channel_based' => true,
                'locale_based'  => false,
            ],
            [
                'name'          => 'geowidget_token',
                'title'         => 'admin::app.configuration.index.sales.shipping-methods.inpost.geowidget-token',
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => false,
                'locale_based'  => false,
            ],
            [
                'name'          => 'environment',
                'title'         => 'admin::app.configuration.index.sales.shipping-methods.inpost.environment',
                'type'          => 'select',
                'options'       => [
                    [
                        'title' => 'Sandbox (testy)',
                        'value' => 'sandbox',
                    ],
                    [
                        'title' => 'Production (produkcja)',
                        'value' => 'production',
                    ],
                ],
                'value'         => 'sandbox',
                'channel_based' => false,
                'locale_based'  => false,
            ],
            [
                'name'          => 'logo',
                'title'         => 'admin::app.configuration.index.sales.shipping-methods.inpost.logo',
                'type'          => 'image',
                'validation'    => 'mimes:jpeg,jpg,png,svg',
                'channel_based' => false,
                'locale_based'  => false,
            ],
            [
                'name'          => 'active',
                'title'         => 'admin::app.configuration.index.sales.shipping-methods.inpost.status',
                'type'          => 'boolean',
                'value'         => true,
                'validation'    => 'required',
                'channel_based' => true,
                'locale_based'  => false,
            ],
        ],
    ],
];
