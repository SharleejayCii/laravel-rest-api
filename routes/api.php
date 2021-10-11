<?php

use Illuminate\Support\Facades\Route;

Route::post('/reset', 'ResetController@reset');

Route::get('/balance','BalanceController@show');