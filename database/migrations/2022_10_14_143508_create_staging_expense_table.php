<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStagingExpenseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staging_expense', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('expense_id', true);
            $table->boolean('type')->default(true);
            $table->char('merchant_id', 10)->index('merchant_id_idx');
            $table->integer('vendor_id');
            $table->integer('category_id');
            $table->integer('department_id');
            $table->string('expense_no', 45);
            $table->string('invoice_no', 45);
            $table->date('bill_date')->default('2014-01-01');
            $table->date('due_date')->default('2014-01-01');
            $table->decimal('tds', 3)->default(0);
            $table->decimal('discount', 11)->default(0);
            $table->decimal('adjustment', 11)->default(0);
            $table->string('payment_status', 20)->default('0');
            $table->string('payment_mode', 20)->default('0');
            $table->decimal('base_amount', 11)->default(0);
            $table->decimal('cgst_amount', 11)->default(0);
            $table->decimal('sgst_amount', 11)->default(0);
            $table->decimal('igst_amount', 11)->default(0);
            $table->decimal('total_amount', 11)->default(0);
            $table->boolean('notify')->default(true);
            $table->string('narrative', 250);
            $table->string('file_path', 250)->nullable();
            $table->integer('bulk_id')->default(0);
            $table->char('payment_request_id', 10)->nullable();
            $table->boolean('transfer_id')->default(false);
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
        Schema::dropIfExists('staging_expense');
    }
}
