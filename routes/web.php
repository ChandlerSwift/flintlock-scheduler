<?php
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ScoutController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\ChangeRequestController;
use App\Http\Controllers\DefaultSessionController;
use App\Http\Controllers\ParticipationRequirementController;
use App\Http\Controllers\PrintController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WeekController;

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

Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::post('plan_week/{week}', [AdminController::class, 'plan_week']);
    Route::post('import_data', [AdminController::class, 'import_data']);
    Route::get('stats', [AdminController::class, 'getStats']);
    Route::get('seed', [AdminController::class, 'seedDatabase']);
    Route::resource('scouts', ScoutController::class);
    Route::post('scouts/{scout}/addSession', [ScoutController::class, 'addSession']);
    Route::post('scouts/{scout}/dropSession/{session}', [ScoutController::class, 'dropSession']);
    Route::get('add_scout', [ScoutController::class, 'create']);
    Route::post('add_scout', [ScoutController::class, 'store']);
    Route::resource('users', UserController::class);
    Route::resource('weeks', WeekController::class);
    Route::resource('programs', ProgramController::class);
    Route::resource('sessions', DefaultSessionController::class);
    Route::get('/participation-requirements', [ParticipationRequirementController::class, 'index']);
    Route::post('/participation-requirements', [ParticipationRequirementController::class, 'store']);
    Route::post('/participation-requirements/sync', [ParticipationRequirementController::class, 'updatePrograms']);
});

Route::middleware(['auth'])->get('/weeks', [WeekController::class, 'select']);
Route::middleware(['auth'])->get('/weeks/{id}', [WeekController::class, 'choose']);

Route::middleware(['auth', 'week'])->group(function(){

    Route::get('/', function () {
        return view('master')->with('programs', \App\Models\Program::all());
    });
    Route::get('/scouts/{scout}', [ScoutController::class, 'show']);
    Route::get('sessions', [SessionController::class, 'index']);
    Route::get('programs', [ProgramController::class, 'list']);
    Route::get('programs/{program}', [ProgramController::class, 'show']);
    Route::get('/all_programs', [ProgramController::class, 'showAll']);
    Route::resource('requests', ChangeRequestController::class);

    Route::get('units', function() {
        return view('troops.index')->with('units', DB::table('scouts')->select('unit')->distinct()->get()->pluck('unit'));
    });

    Route::get('units/{id}', function($id) {
        return view('troops.show')->with('unit', $id)->with('scouts', \App\Models\Scout::where('unit', $id)->get());
    });
    Route::get('print/units', [PrintController::class, 'units']);
    Route::get('print/rosters', [PrintController::class, 'chooseRosters']);
    Route::post('print/rosters', [PrintController::class, 'rosters']);
    Route::get('search', function() {
        return view('search');
    });

    Route::middleware(['admin'])->group(function(){
        Route::post('requests/{changeRequest}/approve', [ChangeRequestController::class, 'approveRequest']);
        Route::post('requests/{changeRequest}/unapprove', [ChangeRequestController::class, 'unapproveRequest']);
    });
    Route::post('requests/{request}/confirm', [ChangeRequestController::class, 'confirmRequest']);
    Route::post('requests/{request}/waitlist', [ChangeRequestController::class, 'waitRequest']);

    Route::get('search', 'App\Http\Controllers\ScoutController@search')->name('search');

    Route::get('pi', function () {
        return view('pi')->with('programs', \App\Models\Program::all());
    });

    Route::post('/scouts/{scout}/participation-requirements', [ScoutController::class, 'updateReqs']);
    Route::get('/participation-requirements/{subcamp}', [ParticipationRequirementController::class, 'required']);
    Route::post('/participation-requirements/{subcamp}', [ParticipationRequirementController::class, 'updateSubcamp']);
});
