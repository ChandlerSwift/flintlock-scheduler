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
            $table->integer('rank')->default(1);
            $table->integer('age')->default(10);
            $table->integer('grade')->nullable();
            $table->integer('years_at_camp')->default(0);
            $table->string('unit');
            $table->string('site');
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
