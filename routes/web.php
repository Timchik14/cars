<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarsController;

Route::post('/', [CarsController::class, 'store']);
