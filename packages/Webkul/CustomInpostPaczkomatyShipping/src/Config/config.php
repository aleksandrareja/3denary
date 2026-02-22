<?php

return [

    'inpost' => [
    'shipx_token' => env('INPOST_SHIPX_TOKEN'),
    'geo_token' => env('INPOST_GEO_TOKEN'),
    'organization_id' => env('INPOST_ORGANIZATION_ID'),
    'mode' => env('INPOST_MODE', 'production'),
    ],
];