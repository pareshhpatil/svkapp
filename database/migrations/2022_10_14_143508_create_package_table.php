<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('package', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('package_id', true);
            $table->string('package_name', 45);
            $table->boolean('type')->default(true);
            $table->string('package_description', 500);
            $table->integer('links');
            $table->decimal('cost_per_link', 10);
            $table->decimal('cost_per_month', 10);
            $table->decimal('package_cost', 10);
            $table->decimal('total_cost', 11);
            $table->integer('discount');
            $table->boolean('is_active');
            $table->boolean('is_visible');
            $table->integer('individual_invoice')->default(0);
            $table->integer('bulk_invoice')->default(0);
            $table->integer('total_invoices')->default(0);
            $table->integer('merchant_role')->default(0);
            $table->boolean('pg_integration')->default(false);
            $table->boolean('site_builder')->default(false);
            $table->boolean('brand_keyword')->default(false);
            $table->integer('free_sms')->default(0);
            $table->boolean('coupon')->default(false);
            $table->boolean('supplier')->default(false);
            $table->integer('duration')->default(0);
            $table->date('start_date');
            $table->date('end_date');
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
        Schema::dropIfExists('package');
    }
}
