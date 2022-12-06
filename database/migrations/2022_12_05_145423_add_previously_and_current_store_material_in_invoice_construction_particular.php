<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPreviouslyAndCurrentStoreMaterialInInvoiceConstructionParticular extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoice_construction_particular', function (Blueprint $table) {
            $table->double('current_stored_materials')
                ->after('total_outstanding_retainage');
            $table->double('previously_stored_materials')
                ->after('current_stored_materials');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoice_construction_particular', function (Blueprint $table) {
            //
            $table->dropColumn(['current_stored_materials', 'previously_stored_materials']);
        });
    }
}
