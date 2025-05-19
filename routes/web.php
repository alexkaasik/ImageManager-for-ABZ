<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\UserController;

# API
Route::get('/positions', [PositionController::class, 'GetPositions']);


# Web page
Route::get('/userform', [UserController::class, 'GetUserForm']);