<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChangeRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('change_requests', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('scout_id');
            $table->foreign('scout_id')->references('id')->on('scouts');
            $table->integer('program_id');
            $table->foreign('program_id')->references('id')->on('programs');
            $table->integer('session_id')->nullable();
            $table->foreign('session_id')->references('id')->on('sessions');
            $table->string('action');
            $table->string('status');
            $table->string('notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('change_requests');
    }
}
