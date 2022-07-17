<?php

namespace App\Http\Controllers;

use App\Models\Scout;
use App\Models\Session;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrintController extends Controller
{
    public function chooseRosters(Request $request) {
        return view('print.select_roster_time')
            ->with('start_times', Session::select('start_time')->distinct()->orderBy('start_time')->get()->pluck('start_time'));
    }

    public function rosters(Request $request) {
        $start_times = [];
        foreach($request->input('start_times') as $start_time) {
            array_push($start_times, Carbon::createFromTimestamp($start_time));
        }
        // dd($start_times);
        return view('print.rosters')
            ->with('sessions', Session::whereIn('start_time', $start_times)->get())
            ->with('scouts', Scout::all());
    }
    public function units(Request $request) {
        $bus_times = [
            '13:00' => [
                'Buckskin' => '12:30 PM',
                'Ten Chiefs' => '12:40 PM',
                'Voyageur' => '12:50 PM',
            ],
            '17:30' => [
                'Buckskin' => '5:30 PM',
                'Ten Chiefs' => '5:40 PM',
                'Voyageur' => '5:50 PM',
            ],
            '19:00' => [
                'Buckskin' => '6:40 PM',
                'Ten Chiefs' => '6:30 PM',
                'Voyageur' => '6:20 PM',
            ],
        ];
        $troops = DB::table('scouts')->select('unit')->distinct()->get()->pluck('unit');
        return view('print.units')
            ->with('troops', $troops)
            ->with('scouts', Scout::all())
            ->with(compact('bus_times'));
    }
}
