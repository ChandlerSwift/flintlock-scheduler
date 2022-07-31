<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDefaultSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('default_sessions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('program_id')->constrained()->onDelete('cascade');
            $table->integer('start_seconds'); // Seconds after midnight on Sunday
            $table->integer('end_seconds'); // Seconds after midnight on Sunday
            $table->boolean('every_day')->default(false); // Tier 2 programs
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('default_sessions');
    }
}
