<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePreferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('preferences', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('scout_id');
            $table->foreign('scout_id')->references('id')->on('scouts');

            $table->integer('program_id');
            $table->foreign('program_id')->references('id')->on('programs');

            $table->integer('rank')->default('10');

            $table->boolean('satisfied')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('preferences');
    }
}
