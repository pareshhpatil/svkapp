<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRuleEngineColumnInBriqPrivilegesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('briq_privileges', function (Blueprint $table) {
            $table->text('rule_engine_query')
                ->after('access')
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('briq_privileges', function (Blueprint $table) {
            $table->dropColumn('rule_engine_query');
        });
    }
}
