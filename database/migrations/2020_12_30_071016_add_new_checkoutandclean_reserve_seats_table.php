<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewCheckoutandcleanReserveSeatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reserve_seats', function (Blueprint $table) {
            $table->tinyInteger('checkout')->default(1);
            $table->time('checkout_time')->nullable();
            $table->tinyInteger('cleaning')->default(1);
            $table->time('clean_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reserve_seats', function (Blueprint $table) {
            $table->dropColumn(['checkout', 'checkout_time', 'cleaning', 'clean_time']);
        });
    }
}
