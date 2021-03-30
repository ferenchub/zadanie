<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SitesController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', [SitesController::class, 'index']);
Route::get('/two', [SitesController::class, 'other']);
