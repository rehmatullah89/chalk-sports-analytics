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
			    $table->string('invoice_id')->after('s_payment_id')->default('');
			    $table->integer('package_id')->after('s_payment_id')->default(0);
			    $table->char('active', 1)->after('status')->default('Y');
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
