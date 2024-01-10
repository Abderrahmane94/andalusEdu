<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCurrencyFormatToSmGeneralSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sm_general_settings', function (Blueprint $table) {
            if (!Schema::hasColumn($table->getTable(), 'currency_format')) {
                $table->string('currency_format')->nullable()->default('symbol_amount');
            }
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sm_general_settings', function (Blueprint $table) {
            $table->dropColumn('currency_format');
        });
    }
}
