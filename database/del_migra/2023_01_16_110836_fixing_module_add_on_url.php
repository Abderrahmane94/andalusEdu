<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixingModuleAddOnUrl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\InfixModuleManager::whereNotNull('addon_url')->update([
            'addon_url' => 'https://aorasoft.com'
        ]);

        Schema::table('sm_backups', function (Blueprint $table) {           
            if (!Schema::hasColumn($table->getTable(), 'lang_type')) {
                $table->integer('lang_type')->nullable();
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

        Schema::table('sm_backups', function (Blueprint $table) {
            //
        });
    }
}
