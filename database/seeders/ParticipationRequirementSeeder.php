<?php

namespace Database\Seeders;

use App\Models\ParticipationRequirement;
use App\Models\Program;
use Illuminate\Database\Seeder;

class ParticipationRequirementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
        - ASI (ATV Safety Institute) waiver - required for ATVs
        - ATV certification - required for ATVs
        - Swimmer tag - HF ADC ski
        */
        $asi = new ParticipationRequirement();
        $asi->name = "ASI waiver";
        $asi->save();
        $atvcert = new ParticipationRequirement();
        $atvcert->name = "ATV certification";
        $atvcert->save();
        $swimmer = new ParticipationRequirement();
        $swimmer->name = "Swimmer tag";
        $swimmer->save();

        $atvProgram = Program::where('name', 'ATV Training Course')->first();
        $atvProgram->participationRequirements()->attach([$asi->id, $atvcert->id]);

        foreach (["Huck Finn Overnight", "Adventure Cove", "Waterski"] as $programName) {
            $program = Program::where('name', $programName)->first();
            $program->participationRequirements()->attach($swimmer->id);
        }
    }
}
