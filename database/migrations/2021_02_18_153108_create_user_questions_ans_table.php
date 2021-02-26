<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserQuestionsAnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_questions_ans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('question_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->text('answer')->nullable();
            $table->tinyInteger('sent_status')->default(0);
            $table->tinyInteger('answer_status')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_questions_ans');
    }
}
