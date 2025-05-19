<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PositionController;

Route::get('/positions', [PositionController::class, 'GetPositions']);