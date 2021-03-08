<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Scout;
use App\Models\Preference;
use App\Models\Program;
use App\Models\Session;

class AdminController extends Controller
{

    public function import_data() {
        $inputFileName = '/home/isaac/importData.xlsx'; // TODO
        $spreadsheet = IOFactory::load($inputFileName);
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        $sheetData = array_slice($sheetData, 3);
        foreach($sheetData as $row){
            if ($row['A'] == null) { // Name should never be empty, so this is an empty row. Skip it.
                continue;
            }
            echo "creating... " . implode(", ", $row);
            $scout = Scout::where('first_name', $row['B'])->where('last_name', $row['C'])->where('unit', $row['J'])->firstOr(function() use ($row) {
                $scout = new Scout;
                $scout->first_name = $row['B'];
                $scout->last_name = $row['C'];
                if ($row['D'] != null)
                    $scout->rank = $row['D'];
                if ($row['F'] != null)
                    $scout->age = $row['F'];
                if ($row['G'] != null)
                    $scout->grade = $row['G'];
                if ($row['H'] != null)
                    $scout->years_at_camp = $row['H'];
                $scout->unit = $row['J'];
                $scout->site = $row['K'];
                $scout->save();
                return $scout;
            });
            
            //create preference
            preg_match('/(.*) \((\d*)(?:st|nd|rd|th) Pref\)/', $row['A'],  $regex_result);
            $program = Program::where('name', $regex_result[1])->first();
            if ($program == null) {
                echo "trying to find nonexistent program " . $regex_result[1];
            } else {
                $preference = new Preference;
                $preference->program_id = $program->id;
                $preference->rank = $regex_result[2];
                $preference->scout_id = $scout->id;
                $preference->save();
            }
        }
    }

    /* plan_week */
    public function plan_week() {
        $scouts = Scout::orderByDesc('age', 'rank')->get();
        $still_filling = true;
        while($still_filling){
            $still_filling = false; 
            foreach($scouts as $scout) {
                if (put_scout_in_session($scout)) {
                    $still_filling = true;
                }
            }
        }
    }

    /**
     *  Place a scout into the highest choice program that works out
     */
    private function put_scout_in_session(Scout $scout) {
        foreach($scout->preferences as $preference){
            //If satisfied, skip preference
            if($preference->satisfied == true)
                continue;
            
            //Check eligibility for scout. If not eligible, skip preference
            if($scout->age < $preference->program->min_scout_age){
                continue;
            }

            $sessions = Session::where('program_id', $preference->program)
                ->where('full', false) // Ignore full sessions
                ->withCount('scouts')->orderByDesc('scouts_count') // Starting with the session that's closest to full
                ->get();
            foreach ($sessions as $session) {
                // check if scout has conflicts
                $scout_has_conflict = false;
                foreach ($scout->sessions as $potentialConflict) {
                    if ($session->start_time == $potentialConflict->start_time || $session->end_time == $potentialConflict->end_time) { //4
                        $scout_has_conflict = true;
                    }else if ($session->start_time < $potentialConflict->start_time && $session->end_time > $potentialConflict->start_time) { //1
                        $scout_has_conflict = true;
                    }else if ($session->start_time > $potentialConflict->start_time && $session->end_time < $potentialConflict->end_time) {//2
                        $scout_has_conflict = true;
                    }else if ($session->start_time < $potentialConflict->end_time && $session->end_time > $potentialConflict->end_time) {//3
                        $scout_has_conflict = true;
                    }else if ($session->start_time < $potentialConflict->start_time && $session->end_time > $potentialConflict->end_time) {//5
                        $scout_has_conflict = true;
                    }
                }
                if ($scout_has_conflict) {
                    continue; // try the next session time
                }

                // Assign scout to session
                $session->scouts()->attach($scout->id);
                $preference->satisfied = true;
                $scoutAssignedToSession = true;
                break;
            }
        }

        return $scoutAssignedToSession; // Did we change anything for this scout?
    }
}
