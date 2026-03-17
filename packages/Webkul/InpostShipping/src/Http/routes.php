<?php

use Illuminate\Support\Facades\Route;
use Webkul\InpostShipping\Http\Controllers\InpostController;

Route::group([
    'middleware' => ['web'],
    'prefix'     => 'inpost',
], function () {
    Route::post('save-point', [InpostController::class, 'savePoint'])
        ->name('inpost.save-point');
});
