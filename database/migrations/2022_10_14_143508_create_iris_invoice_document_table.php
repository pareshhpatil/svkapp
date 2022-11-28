<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIrisInvoiceDocumentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('iris_invoice_document', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('id', true);
            $table->string('merchant_id', 10)->nullable();
            $table->tinyInteger('docNum')->default(0);
            $table->string('docTyp', 45)->default('');
            $table->integer('srNo')->nullable()->default(0);
            $table->string('from', 45)->default('');
            $table->string('to', 45)->default('');
            $table->integer('total_no')->default(0);
            $table->integer('cancel')->default(0);
            $table->integer('netIssue')->default(0);
            $table->string('gstin', 15)->nullable();
            $table->string('fp', 10)->nullable();
            $table->string('ft', 10)->nullable();
            $table->boolean('status')->default(false);
            $table->boolean('is_active')->default(true);
            $table->string('created_by', 10);
            $table->timestamp('created_date')->default('2014-01-01 00:00:00');
            $table->string('last_update_by', 10);
            $table->timestamp('last_update_date')->useCurrentOnUpdate()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('iris_invoice_document');
    }
}
