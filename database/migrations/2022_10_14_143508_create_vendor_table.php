<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('vendor_id', true);
            $table->string('merchant_id', 10)->index('merchant_idx');
            $table->string('pg_vendor_id', 10)->nullable();
            $table->string('vendor_code', 45)->nullable();
            $table->string('vendor_name', 100);
            $table->integer('category_id')->nullable()->default(0);
            $table->integer('sub_category_id')->nullable();
            $table->string('products', 45)->nullable();
            $table->decimal('commission_percentage', 5)->default(0);
            $table->decimal('commision_amount', 11)->default(0);
            $table->boolean('online_pg_settlement')->default(false);
            $table->boolean('commision_type')->default(false);
            $table->boolean('settlement_type')->default(false);
            $table->string('email_id', 254);
            $table->string('mobile', 13);
            $table->string('pan', 20)->nullable();
            $table->string('adhar_card', 20)->nullable();
            $table->string('gst_number', 20)->nullable();
            $table->string('address', 250)->nullable();
            $table->string('city', 45)->nullable();
            $table->string('state', 45)->nullable();
            $table->string('zipcode', 10)->nullable();
            $table->string('bank_holder_name', 100);
            $table->string('bank_account_no', 20);
            $table->string('bank_name', 45)->nullable();
            $table->string('account_type', 20)->nullable();
            $table->string('ifsc_code', 20)->nullable();
            $table->boolean('status')->default(false);
            $table->integer('bulk_id')->default(0);
            $table->char('vendor_merchant_id', 10)->nullable();
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
        Schema::dropIfExists('vendor');
    }
}
