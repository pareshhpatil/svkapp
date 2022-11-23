<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStagingExpenseDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staging_expense_detail', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('id', true);
            $table->integer('expense_id')->index('expense_id_idx');
            $table->string('particular_name', 100);
            $table->integer('product_id')->default(0);
            $table->string('sac_code', 45)->nullable();
            $table->decimal('qty', 11)->nullable()->default(0);
            $table->decimal('rate', 11)->default(0);
            $table->decimal('sale_price', 11)->default(0);
            $table->decimal('amount', 11)->default(0);
            $table->integer('tax')->default(0);
            $table->decimal('cgst_amount', 11)->default(0);
            $table->decimal('sgst_amount', 11)->default(0);
            $table->decimal('igst_amount', 11)->default(0);
            $table->decimal('gst_percent', 11)->default(0);
            $table->decimal('total_value', 11)->default(0);
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
        Schema::dropIfExists('staging_expense_detail');
    }
}
