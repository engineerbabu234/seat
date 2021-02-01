<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAssetsTypeFieldsSeatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('seats', function (Blueprint $table) {
            $table->tinyInteger('underground')->default(0);
            $table->tinyInteger('pole_information')->default(0);
            $table->tinyInteger('wheelchair_accessable')->default(0);
            $table->tinyInteger('parking_difficulty')->default(0);
            $table->tinyInteger('whiteboard_avaialble')->default(0);
            $table->tinyInteger('teleconference_screen')->default(0);
            $table->tinyInteger('is_white_board_interactive')->default(0);
            $table->tinyInteger('telephone')->default(0);
            $table->string('telephone_number')->nullable();
            $table->string('number_of_spare_power_sockets')->nullable();
            $table->tinyInteger('kanban_board')->default(0);
            $table->tinyInteger('whiteboard')->default(0);
            $table->tinyInteger('interactive_whiteboard')->default(0);
            $table->tinyInteger('standing_only')->default(0);
            $table->tinyInteger('telecomference_screen')->default(0);

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
            $table->dropColumn(['underground', 'pole_information', 'wheelchair_accessable', 'parking_difficulty', 'whiteboard_avaialble', 'teleconference_screen', 'is_white_board_interactive', 'telephone', 'telephone_number', 'number_of_spare_power_sockets', 'kanban_board', 'whiteboard', 'interactive_whiteboard', 'standing_only', 'telecomference_screen']);
        });
    }
}
