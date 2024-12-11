<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_report_summary', function (Blueprint $table) {
            $table->integer('user_id');
            $table->integer('season_id');
            $table->integer('week_number');
            $table->integer('team_1_id');
            $table->integer('team_2_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('test_report_summary');
    }
};
