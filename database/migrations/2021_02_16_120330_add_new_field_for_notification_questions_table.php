<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldForNotificationQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notification_questions', function (Blueprint $table) {
            $table->string('repeat_option')->nullable()->after("repeat");
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
        Schema::table('notification_questions', function (Blueprint $table) {
            $table->dropColumn(['repeat_option']);
            $table->dropColumn(['repeat_value']);
        });
    }
}
