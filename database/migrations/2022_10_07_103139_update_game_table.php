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
		Schema::table('games', function (Blueprint $table) {
			    $table->float('t1_spread')->after('team_2_yds')->default(0.00);
			    $table->float('t1_money_line')->after('team_2_yds')->default(0.00);
			    $table->float('t1_over_under')->after('team_2_yds')->default(0.00);
			    $table->float('t1_probability')->after('team_2_yds')->default(0.00);
                $table->float('t2_spread')->after('team_2_yds')->default(0.00);
                $table->float('t2_money_line')->after('team_2_yds')->default(0.00);
                $table->float('t2_over_under')->after('team_2_yds')->default(0.00);
                $table->float('t2_probability')->after('team_2_yds')->default(0.00);
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('games');
    }
};
