<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Scout;
use App\Models\Preference;
use App\Models\Program;




class AdminController extends Controller
{

    public function import_data() {
        $inputFileName = '/home/isaac/importData.xlsx'; // todo
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
        $scouts = \App\Models\Scout::orderByDesc('age', 'rank')->get();
        $still_filling = true;
        while($still_filling){
            foreach($scouts as $scout) {

                //Find the first unsatisfied preference, or skip scout
                $preference = Preference::where('scout_id', $scout->id)->where('satisfied', false)->firstOr(function(){
                    echo "continue";
                    //continue;
                    //TODO: continue errors. Not in loop? 
                });

                //Check eligibility for scout. If not eligible, skip preference
                if($scout->isNotElligible($preference->program_id->min_scout_age)){//Is this how table relationships work??
                    $preference->satisfied = true;
                    $preference->save();
                    $preference = Preference::where('scout_id', $scout->id)->where('satisfied', false)->firstOr(function(){
                        echo "continue";
                        //continue
                        //TODO: continue errors. Not in loop? 
                    });
                }
                
                //
                $session = Session::where('program_id', $preference->program_id)->where('full', false)->firstOr(function(){
                    $session = new Session;
                    $session->program_id = $preference->program_id;
                    $session->session_time = newSessionTime($preference->program_id);//TODO: newSessionTime
                    $session->save();
                });
                

            }
            $still_filling = false; //figure out how to do this elegantly
        } 
    }
}
