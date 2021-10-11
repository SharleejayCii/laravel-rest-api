<?php

use App\Http\Controllers\BalanceController;
use App\Http\Controllers\ResetController;

use Illuminate\Support\Facades\Route;

Route::post('/reset', [ResetController::class, 'reset']);

Route::get('/balance',[BalanceController::class, 'show']);
