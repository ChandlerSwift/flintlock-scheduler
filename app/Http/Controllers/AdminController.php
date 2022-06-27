<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Scout;
use App\Models\Preference;
use App\Models\Program;
use App\Models\Session;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{

    public function import_data() {

        //Artisan::call('migrate', ['--seed' => true]);
        $inputFileName = '/home/Isaac/importData.xlsx'; // TODO
        $spreadsheet = IOFactory::load($inputFileName);
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        foreach($sheetData as $row){
            if ($row['C'] == null) { // Name should never be empty, so this is an empty row. Skip it.
                continue;
            }
            if ($row['C'] == 'First Name') { // Don't count the first line
                continue;
            }

            if (Scout::where('first_name', $row['C'])->where('last_name', $row['D'])->where('unit', $row['E'])->first()) {
                continue;
            }
            $scout = new Scout;
            $scout->first_name = $row['C'];
            $scout->last_name = $row['D'];
            if ($row['G'] != null)
                if ($row['G'] == 'Scout')
                    $scout->rank = 0;
                else if ($row['G'] == 'Tenderfoot')
                    $scout->rank = 1;
                else if ($row['G'] == 'Second Class')
                    $scout->rank = 2;
                else if ($row['G'] == 'First Class')
                    $scout->rank = 3;
                else if ($row['G'] == 'Star')
                    $scout->rank = 4;
                else if ($row['G'] == 'Life')
                    $scout->rank = 5;
                else if ($row['G'] == 'Eagle')
                    $scout->rank = 6;
                else
                    Log::warning("Unknown rank for scout " . $row['C'] . " " . $row['D'] . ", troop " . $row['E'] . " (setting to Scout)");
            if ($row['H'] != null)
                $scout->age = $row['H'];
            else   
                $scout->age = '10';
            if ($row['E'] != null)
            $scout->unit = $row['E'];
            else
                $scout->unit = '0000';
            if ($row['A'] != null)
                $scout->subcamp = $row['A'];
            if ($row['J'] != null)
                $scout->site = $row['J'];
            if ($row['F'] != null)
                $scout->gender = $row['F'];
            else
                $scout->gender = '0';
            $scout->save();
            
            $row_indices = ["K", "L", "M", "N", "O", "P", "Q"];
            for ($i = 0; $i < 7; $i++) {
                if ($row[$row_indices[$i]] != "") {
                    $program = Program::where('name', $row[$row_indices[$i]])->first();//TODO change program values
                    if ($program == null) {
                        Log::warning("trying to find nonexistent program " . $row[$row_indices[$i]]);
                    } else {
                        $preference = new Preference;
                        $preference->program_id = $program->id;
                        $preference->rank = $i + 1;
                        $preference->scout_id = $scout->id;
                        $preference->save();
                    }
                }
            }
        }
        //$request->session()->flash('status', 'Import data was successful!');
        return back();
    }

    /* plan_week */
    public function plan_week() {//Request $request
        $scouts = Scout::orderByDesc('age', 'rank')->get();
        $still_filling = true;
        echo "Adding scouts to session...";
        $i=0; 
        while($still_filling){
            echo $i++;
            $still_filling = false;
            foreach($scouts as $scout) {
                if ($this->put_scout_in_session($scout)) {
                    $still_filling = true;
                }
            }
        }

        /* $testSubcamps = ['Buckskin', 'Ten Chiefs', 'Voyageur'];
        $afternoonA = 'Blank';
        $afternoonB = 'Blank';
        $eveningFirst;
        $maxa = 0;  
        $maxb = 0;
        $maxe = 0;
        foreach ($testSubcamps as $testSubcamp) {
            
            $counta = 0;
            $counte = 0;
            $preferences = Preference::whereHas('scout', function($q) use($testSubcamp){

                $q->where('subcamp', '=', $testSubcamp);
            
            })->get();
            
            foreach ($preferences as $preference){
                
                if($preference->satisfied)
                    continue;
                if($preference->program->id == 1 || $preference->program->id == 3 || $preference->program->id == 3)
                    $counte += 1;
                if($preference->program->id == 4 
                || $preference->program->id == 5 
                || $preference->program->id == 6 
                || $preference->program->id == 7 
                || $preference->program->id == 8
                || $preference->program->id == 9){
                    $counta += 1;
                }
            }
            if($counta > $maxa && $counta > $maxb){
                $maxb = $maxa;
                $maxa = $counta;
                $afternoonB = $afternoonA;
                $afternoonA = $testSubcamp;
                echo "Replaced A slot..." . $afternoonA . "...";
            }elseif($counta > $maxb){
                $maxb = $counta;
                $afternoonB = $testSubcamp;
                echo "Replaced B slot..." . $afternoonB . "...";
            }
            if($counte > $maxe){
                $maxe = $counte;
                $eveningFirst = $testSubcamp;
                echo "Replaced E slot..." . $eveningFirst . "...";
            }
        }
        $eSessions = Session::where('subcamp', 'anyE')->get();
        foreach ($eSessions as $session){
                $session->subcamp = $eveningFirst;
                $session->save();
                echo "E...";
        }
        $aSessions = Session::where('subcamp', 'anyA')->get();;
        foreach ($aSessions as $session){
            if ($afternoonB == $eveningFirst){
                $session->subcamp = $afternoonA;
                $session->save();
                echo "A...";
            }else{
                $session->subcamp = $afternoonB;
                $session->save();
                echo "B...";
            }
        }
        $bSessions = Session::where('subcamp', 'anyB')->get();;
        foreach ($bSessions as $session){
            if ($afternoonB == $eveningFirst){
                $session->subcamp = $afternoonB;
                $session->save();
                echo "A...";
            }else{
                $session->subcamp = $afternoonA;
                $session->save();
                echo "B...";
            }
        }

        $this->clearSessions();

        $scouts = Scout::orderByDesc('age', 'rank')->get();
        $still_filling = true;
        echo "Adding scouts to session...";
        $i=0;
        while($still_filling){
            echo $i++;
            $still_filling = false;
            foreach($scouts as $scout) {
                if ($this->put_scout_in_session($scout)) {
                    $still_filling = true;
                }
            }
        } */

         /* foreach ($session->where('subcamp', 'any') as $session) {
            $max_scouts_placed = 0;
            
            foreach ($testSubcamps as $testSubcamp) {
                timeslot->sessions->subcamp = $testSubcamp;
                $scouts_placed = 0;
                while($still_filling){
                    $still_filling = false;
                    foreach($scouts as $scout) {
                        if ($this->put_scout_in_session($scout)) {
                            scouts_placed += 1;
                            $still_filling = true;
                        }
                    }
                }
                reset_timeslot(timeslot)
                if ($scouts_placed > $max_scouts_placed) {
                    $max_scouts_placed = $scouts_placed;
                    $bestSubcamp = $testSubcamp;
                }
            } 
            // then set subcamp = best_subcamp and really schedule
        } */
        
        //$request->session()->flash('status', 'Plan week was successful!');
        return back();
    }

    /**
     *  Place a scout into the highest choice program that works out
     */
    private function put_scout_in_session(Scout $scout) {
        $scoutAssignedToSession = false;
        foreach($scout->preferences as $preference){

            //If satisfied, skip preference
            if($preference->satisfied) {
                Log::debug("This scout's preference was already satisfied");
                continue;
            }

            //Check eligibility for scout. If not eligible, skip preference
            if($scout->age < $preference->program->min_scout_age){
                Log::debug($scout->first_name . " " . $scout->last_name . " is not old enough (need " . $preference->program->min_scout_age . ", got " . $scout->age . ")");
                continue;
            }

            $sessions = Session::where('program_id', $preference->program_id)
                ->withCount('scouts')->orderByDesc('scouts_count') // Starting with the session that's closest to full
                ->get()->where('full', false); // Ignore full sessions;
            foreach ($sessions as $session) {
                
                /* if ($scout->subcamp != $session->subcamp)//Ignore different subcamp slots
                    continue;
 */
                $scout_has_conflict = false;
                $conflict = null;
                foreach ($scout->sessions as $potentialConflict) {
                    Log::debug("About to check for overlaps...");
                    if ($potentialConflict->overlaps($session)) {
                        $scout_has_conflict = true; 
                        $conflict = $potentialConflict;
                    }
                }
                if ($scout_has_conflict) {
                    Log::debug("The scout has a conflict (" . $conflict->program->name . ")");
                    continue; // try the next session time
                }

                // Assign scout to session
                $session->scouts()->attach($scout->id);
                $scout->refresh(); // Invalidate the cache
                $scoutAssignedToSession = true;
                break;
            }
        }

        return $scoutAssignedToSession; // Did we change anything for this scout?
    }

    public function clearSessions() {
        foreach(\App\Models\Scout::all() as $scout) {
            $scout->sessions()->detach();
        }
    }

    public function dropSessions($session, $scout){
        $session->scouts()->detatch($scout->id);
        //confirmation message
    }



    public function getStats(Request $request) {
        $p = Preference::all();
        return view('stats')
            ->with('subcamps', $p->groupBy(function($item, $key) {
                return $item->scout->subcamp;
            }))
            ;
    }

}
