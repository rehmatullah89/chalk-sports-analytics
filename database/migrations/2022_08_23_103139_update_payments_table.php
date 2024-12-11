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
		Schema::table('payments', function (Blueprint $table) {
			   $table->integer('package_id')->change();
			   $table->integer('amount')->change()->default(0);
			   $table->renameColumn('package_id', 'tax')->default(0);
               $table->string('status')->after('amount')->default('failed');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
