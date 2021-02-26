<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewNotifyFieldForUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger('reminder')->default(0);
            $table->string('repeat_option')->nullable()->after("reminder");
            $table->string('repeat_value')->nullable()->after("repeat_option");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['reminder']);
            $table->dropColumn(['repeat_option']);
            $table->dropColumn(['repeat_value']);
        });
    }
}
