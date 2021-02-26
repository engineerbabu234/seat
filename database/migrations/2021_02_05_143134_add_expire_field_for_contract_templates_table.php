<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExpireFieldForContractTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contract_templates', function (Blueprint $table) {
            $table->string('expired_option')->nullable()->after("contract_description");
            $table->string('expired_value')->nullable()->after("expired_option");
            $table->date('start_date')->nullable()->after("expired_value");
            $table->date('expired_date')->nullable()->after("start_date");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contract_templates', function (Blueprint $table) {
            $table->dropColumn(['username']);
            $table->dropColumn(['password']);
            $table->dropColumn(['integrator_key']);
            $table->dropColumn(['host']);
        });
    }
}
