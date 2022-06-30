<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParticipationRequirementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participation_requirements', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
        });
        Schema::create('participation_requirement_program', function (Blueprint $table) {
            $table->timestamps();
            $table->integer('participation_requirement_id');
            $table->integer('program_id');
        });
        Schema::create('participation_requirement_scout', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('participation_requirement_id');
            $table->integer('scout_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('participation_requirements');
        Schema::dropIfExists('participation_requirement_program');
        Schema::dropIfExists('participation_requirement_scout');
    }
}
