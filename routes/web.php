<?php
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ScoutController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\ChangeRequestController;


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

require __DIR__.'/auth.php';

Route::middleware(['auth'])->group(function(){

    Route::get('/', function () {
        return view('master')->with('programs', \App\Models\Program::all());
    });

    Route::prefix('admin')->middleware(['admin'])->group(function () {
        Route::get('/', function () { return view('admin'); });
        Route::get('plan_week', [AdminController::class, 'plan_week']);
        Route::get('import_data', [AdminController::class, 'import_data']);
        Route::get('stats', [AdminController::class, 'getStats']);
        Route::get('seed', [AdminController::class, 'seedDatabase']);
        Route::get('add_scout', [ScoutController::class, 'create']);
        Route::post('add_scout', [ScoutController::class, 'store']);
        // TODO: users
    });

    Route::resource('scouts', ScoutController::class);
    Route::resource('sessions', SessionController::class);
    Route::resource('programs', ProgramController::class);
    Route::resource('requests', ChangeRequestController::class);

    Route::get('troops', function() {
        return view('troops.index')->with('troops', DB::table('scouts')->select('unit')->distinct()->get()->pluck('unit'));
    });

    Route::get('troops/{id}', function($id) {
        return view('troops.show')->with('troop', $id)->with('scouts', \App\Models\Scout::where('unit', $id)->get());
    });
    Route::get('print', function() {
        return view('print')->with('troops', DB::table('scouts')->select('unit')->distinct()->get()->pluck('unit'))->with('scouts', \App\Models\Scout::all());
    });
    Route::get('search', function() {
        return view('search');
    });

    Route::middleware(['admin'])->group(function(){
        Route::post('requests/{changeRequest}/approve', [ChangeRequestController::class, 'approveRequest']);
        Route::post('requests/{changeRequest}/unapprove', [ChangeRequestController::class, 'unapproveRequest']);
    });
    Route::post('requests/{request}/confirm', [ChangeRequestController::class, 'confirmRequest']);
    Route::post('requests/{request}/waitlist', [ChangeRequestController::class, 'waitRequest']);

    Route::get('/search/', 'App\Http\Controllers\ScoutController@search')->name('search');

    Route::get('pi', function () {
        return view('pi')->with('programs', \App\Models\Program::all());
    });

});
