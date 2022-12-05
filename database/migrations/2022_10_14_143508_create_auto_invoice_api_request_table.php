<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAutoInvoiceApiRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auto_invoice_api_request', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('id', true);
            $table->string('transaction_id', 10)->nullable();
            $table->string('merchant_id', 10);
            $table->longText('api_request_json')->nullable();
            $table->boolean('status')->default(false)->comment('0 - initiated, 1 - success, 2 - failure');
            $table->longText('errors')->nullable();
            $table->char('payment_request_id', 10)->nullable();
            $table->timestamp('created_date')->default('2014-01-01 00:00:00');
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
        Schema::dropIfExists('auto_invoice_api_request');
    }
}
