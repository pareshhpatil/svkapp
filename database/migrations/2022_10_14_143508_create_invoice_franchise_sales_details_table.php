<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceFranchiseSalesDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_franchise_sales_details', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('id', true);
            $table->integer('store_id')->nullable();
            $table->date('date')->nullable();
            $table->integer('invoice_id')->nullable();
            $table->decimal('base_price', 11)->nullable();
            $table->decimal('tax', 11)->nullable();
            $table->decimal('total_price', 11)->nullable();
            $table->decimal('non_brand_base_price', 11)->nullable();
            $table->decimal('non_brand_tax', 11)->nullable();
            $table->decimal('non_brand_total_price', 11)->nullable();
            $table->decimal('base_price_online', 11)->nullable();
            $table->decimal('tax_online', 11)->nullable();
            $table->decimal('total_price_online', 11)->nullable();
            $table->decimal('non_brand_base_price_online', 11)->nullable();
            $table->decimal('non_brand_tax_online', 11)->nullable();
            $table->decimal('non_brand_total_price_online', 11)->nullable();
            $table->decimal('non_brand_delivery_partner_commission', 11)->nullable();
            $table->decimal('delivery_partner_commission', 11)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_franchise_sales_details');
    }
}
