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
        $output = "Adding scouts to session...";
        while($still_filling){
            $still_filling = false; 
            foreach($scouts as $scout) {
                Log::warning("Outer Scheduling for " . $scout->first_name . "...");
                if ($this->put_scout_in_session($scout)) {
                    $output .= "Added " . $scout->name . " to session\n";
                    $still_filling = true;
                }
            }
        }
        return $output;
    }

    /**
     *  Place a scout into the highest choice program that works out
     */
    private function put_scout_in_session(Scout $scout) {
        Log::warning("Inner Scheduling for " . $scout->first_name . "...");
        $scoutAssignedToSession = false;
        foreach($scout->preferences as $preference){
            Log::warning("Scheduling for " . $scout->first_name . " on preference " . $preference->program->name);

            //If satisfied, skip preference
            if($preference->satisfied) {
                Log::warning("This scout's preference was already satisfied");
                continue;
            }
            
            //Check eligibility for scout. If not eligible, skip preference
            if($scout->age < $preference->program->min_scout_age){
                Log::warning("This scout's not old enough");
                continue;
            }

            $sessions = Session::where('program_id', $preference->program_id)
                ->withCount('scouts')->orderByDesc('scouts_count') // Starting with the session that's closest to full
                ->get()->where('full', false); // Ignore full sessions;
            Log::warning($sessions);
            foreach ($sessions as $session) {
                // check if scout has conflicts
                $scout_has_conflict = false;
                foreach ($scout->sessions as $potentialConflict) {
                    if ($potentialConflict->overlaps($session))
                        $scout_has_conflict = true;
                }
                if ($scout_has_conflict) {
                    Log::warning("The scout has a conflict");
                    continue; // try the next session time
                }

                // Assign scout to session
                $session->scouts()->attach($scout->id);
                $preference->satisfied = true;
                $scoutAssignedToSession = true;
                Log::warning("Worked");
                break;
            }
        }

        return $scoutAssignedToSession; // Did we change anything for this scout?
    }
}
