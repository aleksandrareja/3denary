<?php

return [
    'inpost' => [
        'code'        => 'inpost',
        'title'       => 'InPost Paczkomat',
        'description' => 'Dostawa do paczkomatu InPost',
        'active'      => true,
        'default_rate' => '9.99',
        'type'        => 'per_order',
        'class'       => 'Webkul\InpostShipping\Carriers\Inpost',
    ],
];
