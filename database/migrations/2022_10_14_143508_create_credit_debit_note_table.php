<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditDebitNoteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_debit_note', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('id', true);
            $table->boolean('type')->default(true);
            $table->char('merchant_id', 10)->index('merchant_id_idx');
            $table->integer('customer_id')->default(0);
            $table->integer('vendor_id')->default(0);
            $table->string('credit_debit_no', 45);
            $table->date('date')->default('2014-01-01');
            $table->string('invoice_no', 45);
            $table->date('bill_date')->default('2014-01-01');
            $table->date('due_date')->default('2014-01-01');
            $table->decimal('base_amount', 11)->default(0);
            $table->decimal('cgst_amount', 11)->default(0);
            $table->decimal('sgst_amount', 11)->default(0);
            $table->decimal('igst_amount', 11)->default(0);
            $table->decimal('total_amount', 11)->default(0);
            $table->boolean('status')->default(false);
            $table->boolean('notify')->default(true);
            $table->longText('narrative')->nullable();
            $table->string('file_path', 200)->nullable();
            $table->integer('bulk_id')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('credit_note_type')->default(true);
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
        Schema::dropIfExists('credit_debit_note');
    }
}
