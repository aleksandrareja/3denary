<?php

return [
    'custom_inpostpaczkomaty_shipping' => [
        'code'         => 'custom_inpostpaczkomaty_shipping',
        'title'        => 'Inpost Paczkomaty',
        'description'  => 'Shipping to Inpost Paczkomaty',
        'active'       => true,
        'default_rate' => '16.00',
        'type'         => 'per_order',
        'class'        => 'Webkul\CustomInpostPaczkomatyShipping\Carriers\CustomInpostPaczkomatyShipping',

    ]
];