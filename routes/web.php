<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\UserController;

# API
Route::get('/positions', [PositionController::class, 'getPositions'])->name('position.getPositions');;
Route::post('/user', [UserController::class, 'store'])->name('user.store');
Route::get('/user', [UserController::class, 'getUsers'])->name('user.get');

# Web page
Route::get('/userform', [UserController::class, 'viewUserForm'])->name('user.viewUserForm');
Route::get('/userList', [UserController::class, 'viewUserList'])->name('user.viewUserList');