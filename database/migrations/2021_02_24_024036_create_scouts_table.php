<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scouts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('first_name');
            $table->string('last_name');
            $table->integer('rank')->default(0);
            $table->integer('age')->default(10);
            $table->string('gender');
            $table->string('unit');
            $table->string('council');
            $table->string('site');
            $table->string('subcamp');
            $table->foreignId('week_id')->constrained()->onDelete('cascade');
            //Program Session Name,First Name,Last Name,Scout Rank Sort Order,Scout Rank,Age,Grade,Years At Camp,Unit Type Name,Unit Number,Sites
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scouts');
    }
}
