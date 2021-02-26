<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewSendstatusFieldsForUserQuestionsAnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_questions_ans', function (Blueprint $table) {
            $table->tinyInteger('question_status')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_questions_ans', function (Blueprint $table) {
            $table->dropColumn(['question_status']);
        });
    }
}
