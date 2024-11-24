<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

Route::group(['prefix' => 'v1'], function () {
    Route::apiResource('users', UserController::class)->only([
        'index', 'store', 'destroy'
    ]);
});