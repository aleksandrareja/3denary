<?php

return [
    'custom_przelewy24_payment' => [
        'code'        => 'custom_przelewy24_payment',
        'title'       => 'Przelewy24 Payment',
        'description' => 'Secure Przelewy24 payments powered by Przelewy24',
        'class'       => 'Webkul\CustomPrzelewy24Payment\Payment\CustomPrzelewy24Payment',
        'active'      => true,
        'sort'        => 1,
    ],
];