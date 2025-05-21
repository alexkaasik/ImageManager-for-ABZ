<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\UserController;

# API
Route::get('/positions', [PositionController::class, 'getPositions'])->name('position.get');;

Route::post('/users', [UserController::class, 'postUsers'])->name('user.store');
Route::post('/formhandler', [UserController::class, 'handleFormPost'])->name('user.formhandler');

Route::get('/users', [UserController::class, 'getUsers'])->name('user.get');
Route::get('/users/{id}', [UserController::class, 'getUsersDetail'])->name('user.detail.get');


# Web page
Route::get('/form', [UserController::class, 'showForm'])->name('user.form');
Route::get('/list', [UserController::class, 'showList'])->name('user.list');