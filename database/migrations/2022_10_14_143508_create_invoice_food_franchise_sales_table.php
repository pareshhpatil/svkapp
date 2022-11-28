<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceFoodFranchiseSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_food_franchise_sales', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->increments('id');
            $table->integer('customer_id')->index('customer_idx');
            $table->char('payment_request_id', 10)->nullable()->index('payment_request_idx');
            $table->date('date')->nullable();
            $table->decimal('gross_sale', 11)->nullable()->default(0);
            $table->decimal('tax', 11)->nullable()->default(0);
            $table->decimal('billable_sale', 11)->nullable()->default(0);
            $table->decimal('non_brand_gross_sale', 11)->default(0);
            $table->decimal('non_brand_tax', 11)->default(0);
            $table->decimal('non_brand_billable_sale', 11)->default(0);
            $table->decimal('gross_sale_online', 11)->nullable()->default(0);
            $table->decimal('tax_online', 11)->nullable()->default(0);
            $table->decimal('billable_sale_online', 11)->nullable()->default(0);
            $table->decimal('non_brand_gross_sale_online', 11)->default(0);
            $table->decimal('non_brand_tax_online', 11)->default(0);
            $table->decimal('non_brand_billable_sale_online', 11)->default(0);
            $table->decimal('delivery_partner_commission', 11)->default(0);
            $table->decimal('non_brand_delivery_partner_commission', 11)->default(0);
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
        Schema::dropIfExists('invoice_food_franchise_sales');
    }
}
