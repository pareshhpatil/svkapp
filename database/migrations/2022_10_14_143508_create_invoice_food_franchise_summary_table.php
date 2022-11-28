<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceFoodFranchiseSummaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_food_franchise_summary', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->increments('id');
            $table->char('payment_request_id', 10)->index('payment_request_idx');
            $table->decimal('commision_fee_percent', 5)->nullable()->default(0);
            $table->decimal('commision_waiver_percent', 5)->nullable()->default(0);
            $table->decimal('commision_net_percent', 5)->nullable()->default(0);
            $table->decimal('gross_sale', 11)->nullable()->default(0);
            $table->decimal('net_sale', 11)->nullable()->default(0);
            $table->decimal('gross_fee', 11)->nullable()->default(0);
            $table->decimal('waiver_fee', 11)->nullable()->default(0);
            $table->decimal('net_fee', 11)->nullable()->default(0);
            $table->decimal('non_brand_commision_fee_percent', 5)->nullable()->default(0);
            $table->decimal('non_brand_commision_waiver_percent', 5)->nullable()->default(0);
            $table->decimal('non_brand_commision_net_percent', 5)->nullable()->default(0);
            $table->decimal('non_brand_gross_sale', 11)->nullable()->default(0);
            $table->decimal('non_brand_net_sale', 11)->nullable()->default(0);
            $table->decimal('non_brand_gross_fee', 11)->nullable()->default(0);
            $table->decimal('non_brand_waiver_fee', 11)->nullable()->default(0);
            $table->decimal('non_brand_net_fee', 11)->nullable()->default(0);
            $table->decimal('gross_sale_online', 11)->nullable()->default(0);
            $table->decimal('net_sale_online', 11)->nullable()->default(0);
            $table->decimal('non_brand_gross_sale_online', 11)->nullable()->default(0);
            $table->decimal('non_brand_net_sale_online', 11)->nullable()->default(0);
            $table->decimal('delivery_partner_commision', 11)->nullable()->default(0);
            $table->decimal('non_brand_delivery_partner_commision', 11)->nullable()->default(0);
            $table->decimal('penalty', 11)->nullable()->default(0);
            $table->string('bill_period', 30)->nullable();
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
        Schema::dropIfExists('invoice_food_franchise_summary');
    }
}
