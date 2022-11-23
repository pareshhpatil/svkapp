<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_product', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('product_id', true);
            $table->string('merchant_id', 10)->index('merchant_idx');
            $table->string('type', 20)->nullable();
            $table->string('goods_type', 20)->nullable()->default('simple');
            $table->integer('parent_id')->nullable()->default(0);
            $table->string('product_number', 45)->nullable();
            $table->string('product_code', 45)->nullable();
            $table->string('sac_code', 20)->nullable();
            $table->string('product_name', 500)->nullable();
            $table->string('description', 500)->nullable();
            $table->string('taxes', 45)->nullable();
            $table->integer('sub_category_id')->nullable();
            $table->integer('category_id')->nullable()->default(0);
            $table->integer('gst_percent')->default(0);
            $table->decimal('price', 11)->default(0);
            $table->decimal('mrp', 11)->nullable()->default(0);
            $table->string('unit_type', 45)->nullable();
            $table->integer('unit_type_id')->default(0);
            $table->string('product_image', 500)->nullable();
            $table->string('sku', 45)->nullable();
            $table->integer('vendor_id')->nullable()->default(0);
            $table->string('purchase_info', 100)->nullable();
            $table->decimal('purchase_cost', 11)->nullable()->default(0);
            $table->string('sale_info', 100)->nullable();
            $table->boolean('has_stock_keeping')->default(false);
            $table->decimal('available_stock', 11)->default(0);
            $table->decimal('minimum_order', 11)->default(0);
            $table->decimal('minimum_stock', 11)->default(0);
            $table->string('product_expiry_date', 45)->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('wc_post_id', 45)->nullable();
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
        Schema::dropIfExists('merchant_product');
    }
}
