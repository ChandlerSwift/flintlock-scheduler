<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ScoutController;
use App\Http\Controllers\SessionController;


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

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->group(function () { // TODO: auth
    Route::get('plan_week', [AdminController::class, 'plan_week']);
    Route::get('import_data', [AdminController::class, 'import_data']);
});

Route::resource('scouts', ScoutController::class);
Route::resource('sessions', SessionController::class);