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
        // TODO: omit empty rows
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
        $scouts = \App\Models\Scout::order_by_desc('age', 'rank');
        while($still_filling){
            foreach($scouts as $scouts) {
                // Try to insert scout into first preference
                // if that's full, try second, and so on.
                // Once we find one that works, mark that preference as satisfied
                //    and continue on to the next scout
            }
        } // TODO: end this somehow.
    }
}
