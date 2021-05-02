<?php
use Illuminate\Support\Facades\DB;

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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';



Route::prefix('admin')->group(function () { // TODO: auth
    Route::get('/', function () {return view('admin');});   
    Route::get('plan_week', [AdminController::class, 'plan_week']);
    Route::get('import_data', [AdminController::class, 'import_data']);
    Route::get('stats', [AdminController::class, 'getStats']);
    Route::get('seed', [AdminController::class, 'seedDatabase']);
});

Route::resource('scouts', ScoutController::class);
Route::resource('sessions', SessionController::class);

Route::get('master', function() {
    return view('master')->with('programs', \App\Models\Program::all());
});

Route::get('troops', function() {
    return view('troops.index')->with('troops', DB::table('scouts')->select('unit')->distinct()->get()->pluck('unit'));
});

Route::get('troops/{id}', function($id) {
    return view('troops.show')->with('troop', $id)->with('scouts', \App\Models\Scout::where('unit', $id)->get());
});