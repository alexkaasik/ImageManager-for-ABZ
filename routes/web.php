<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

# API endpoint
Route::get('/positions', [PositionController::class, 'getPositions'])->name('position.get');
Route::get('/users', [UserController::class, 'getUsers'])->name('user.get');
Route::get('/users/{id}', [UserController::class, 'getUsersDetail'])->name('user.detail.get');
Route::post('/users', [UserController::class, 'postUsers'])->name('user.store');
Route::post('/formhandler', [UserController::class, 'handleFormPost'])->name('user.formhandler');
Route::post('/token', [AuthController::class, 'generate'])->name('token.generate');

# Web page
Route::get('/', function () { return redirect()->route('user.list'); });
Route::get('/form', [UserController::class, 'showForm'])->name('user.form');
Route::get('/list', [UserController::class, 'showList'])->name('user.list');
