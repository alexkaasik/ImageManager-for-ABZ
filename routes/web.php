<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\UserController;

# API
Route::get('/positions', [PositionController::class, 'getPositions'])->name('position.getPositions');;
Route::post('/user', [UserController::class, 'store'])->name('user.store');

# Web page
Route::get('/userform', [UserController::class, 'viewUserForm'])->name('user.viewUserForm');