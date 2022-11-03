<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceComparisionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_comparision', function (Blueprint $table) {
            $table->collation = 'utf8mb4_unicode_ci';
            $table->charset = 'utf8mb4';

            $table->integer('id', true);
            $table->string('job_id', 10)->index('job_id_idx');
            $table->string('vendor_gstin', 50)->nullable()->index('vendor_gstin_idx');
            $table->string('vendor_name', 50)->nullable();
            $table->string('purch_request_id', 50)->nullable();
            $table->string('purch_request_number', 50)->nullable();
            $table->string('purch_request_date', 50)->nullable();
            $table->decimal('purch_request_total_amount', 11)->nullable()->default(0);
            $table->decimal('purch_request_taxable_amount', 11)->nullable()->default(0);
            $table->string('purch_request_type', 50)->nullable();
            $table->string('purch_request_state', 50)->nullable();
            $table->decimal('purch_request_cgst', 11)->nullable()->default(0);
            $table->decimal('purch_request_sgst', 11)->nullable()->default(0);
            $table->decimal('purch_request_igst', 11)->nullable()->default(0);
            $table->string('gst_request_id', 50)->nullable();
            $table->string('gst_request_number', 50)->nullable();
            $table->string('gst_request_date', 50)->nullable();
            $table->decimal('gst_request_total_amount', 11)->nullable()->default(0);
            $table->decimal('gst_request_taxable_amount', 11)->nullable()->default(0);
            $table->string('gst_request_type', 50)->nullable();
            $table->string('gst_request_state', 50)->nullable();
            $table->decimal('gst_request_cgst', 11)->nullable()->default(0);
            $table->decimal('gst_request_sgst', 11)->nullable()->default(0);
            $table->decimal('gst_request_igst', 11)->nullable()->default(0);
            $table->string('status', 50)->nullable();
            $table->string('created_by', 10)->nullable();
            $table->string('last_update_by', 10)->nullable();
            $table->timestamp('last_update_date')->useCurrentOnUpdate()->useCurrent();
            $table->timestamp('created_date')->default('2014-01-01 00:00:00');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_comparision');
    }
}
