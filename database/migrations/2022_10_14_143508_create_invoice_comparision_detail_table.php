<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceComparisionDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_comparision_detail', function (Blueprint $table) {
            $table->collation = 'utf8mb4_unicode_ci';
            $table->charset = 'utf8mb4';

            $table->integer('id', true);
            $table->string('gstr_2a_table_id', 20)->nullable();
            $table->string('purch_item_number', 20)->nullable();
            $table->decimal('purch_item_taxable_value', 11)->nullable()->default(0);
            $table->decimal('purch_item_tax_rate', 11)->nullable()->default(0);
            $table->decimal('purch_item_cgst', 11)->nullable()->default(0);
            $table->decimal('purch_item_sgst', 11)->nullable()->default(0);
            $table->decimal('purch_item_igst', 11)->nullable()->default(0);
            $table->string('gst_item_number', 10)->nullable();
            $table->decimal('gst_item_taxable_value', 11)->nullable()->default(0);
            $table->decimal('gst_item_tax_rate', 11)->nullable()->default(0);
            $table->decimal('gst_item_cgst', 11)->nullable()->default(0);
            $table->decimal('gst_item_sgst', 11)->nullable()->default(0);
            $table->decimal('gst_item_igst', 11)->nullable()->default(0);
            $table->string('created_by', 20)->nullable();
            $table->string('last_update_by', 20)->nullable();
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
        Schema::dropIfExists('invoice_comparision_detail');
    }
}
