<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceTemplateMandatoryFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_template_mandatory_fields', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->tinyInteger('id', true);
            $table->string('column_name', 50);
            $table->string('system_col_name', 45)->nullable();
            $table->string('help_text', 200)->nullable();
            $table->string('datatype', 45)->nullable();
            $table->string('type', 45)->nullable();
            $table->boolean('is_default')->default(false);
            $table->boolean('is_mandatory')->default(false);
            $table->integer('seq')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_template_mandatory_fields');
    }
}
