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
		 Schema::table('packages', function (Blueprint $table) {
			   $table->string('subscription_price')->after('price')->default(0);
               $table->string('subscription_id')->after('stripe_id')->nullable()->unique;
               $table->string('status')->after('detail')->default('Active');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('packages');
    }
};
