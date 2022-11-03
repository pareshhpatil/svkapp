<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEinvoiceRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('einvoice_request', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('id', true);
            $table->char('merchant_id', 10)->nullable()->index('merchant_idx');
            $table->char('payment_request_id', 10)->nullable();
            $table->string('invoice_number', 45)->nullable();
            $table->date('invoice_date');
            $table->string('merchant_gst', 20)->nullable();
            $table->string('client_name', 100)->nullable();
            $table->string('client_gst', 20)->nullable();
            $table->string('source', 45)->nullable();
            $table->string('ack_no', 45)->nullable();
            $table->dateTime('ack_date')->nullable();
            $table->string('irn', 250)->nullable();
            $table->longText('qr_code')->nullable();
            $table->string('error', 500)->nullable();
            $table->boolean('status')->default(false);
            $table->longText('request_json')->nullable();
            $table->boolean('notify')->default(true);
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
        Schema::dropIfExists('einvoice_request');
    }
}
