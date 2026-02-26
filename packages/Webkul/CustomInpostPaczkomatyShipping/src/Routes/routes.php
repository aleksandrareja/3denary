<?php

use Illuminate\Support\Facades\Route;
use Webkul\CustomInpostPaczkomatyShipping\Http\Controllers\InpostPaczkomatyController;

Route::group(['middleware' => ['web']], function () {
    Route::post('inpost/save-paczkomat', [InpostPaczkomatyController::class, 'savePaczkomat'])
        ->name('inpost.save_paczkomat');
});