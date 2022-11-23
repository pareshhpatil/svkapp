<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormBuilderTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_builder_transaction', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('id', true);
            $table->char('merchant_id', 10)->index('merchant_idx');
            $table->boolean('invoice_enable')->default(true);
            $table->integer('request_id')->default(0);
            $table->char('transaction_id', 10)->nullable()->index('transaction_idx');
            $table->char('payment_request_id', 10)->nullable();
            $table->decimal('amount', 11)->default(0);
            $table->longText('json')->nullable();
            $table->boolean('status')->default(false);
            $table->decimal('zip_size', 5)->default(0);
            $table->string('zip_file_path', 500)->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('created_by', 10);
            $table->timestamp('created_date')->default('2014-01-01 00:00:00')->index('created_date_idx');
            $table->string('last_update_by', 10);
            $table->timestamp('last_update_date')->useCurrentOnUpdate()->useCurrent();

            $table->index(['merchant_id'], 'frm_builder_mid_idx');
            $table->index(['transaction_id'], 'frm_builder_tid_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_builder_transaction');
    }
}
