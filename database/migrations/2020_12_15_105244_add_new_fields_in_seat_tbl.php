<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsInSeatTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('seats', function (Blueprint $table) {
            $table->integer('book_within')->unsigned()->nullable();;
            $table->tinyInteger('seat_clean')->default(1);
            $table->time('required_after_time')->nullable();
            $table->tinyInteger('required_after_checkout')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('seats', function (Blueprint $table) {
            $table->dropColumn(['book_within', 'seat_clean', 'required_after_checkout', 'required_after_time']);
        });
    }
}
