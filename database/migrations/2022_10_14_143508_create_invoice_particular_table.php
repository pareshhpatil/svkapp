<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceParticularTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_particular', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('id', true);
            $table->char('payment_request_id', 10)->index('payment_request_id_idx');
            $table->integer('product_id')->default(0);
            $table->string('item', 500)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('annual_recurring_charges', 45)->nullable();
            $table->string('sac_code', 45)->nullable();
            $table->string('description', 500)->nullable();
            $table->decimal('qty', 11)->default(0);
            $table->string('unit_type', 45)->nullable();
            $table->decimal('rate', 11)->default(0);
            $table->decimal('mrp', 11)->default(0);
            $table->string('product_expiry_date', 45)->nullable();
            $table->string('product_number', 45)->nullable();
            $table->integer('gst')->default(0);
            $table->string('tax_amount', 45)->default('0.00');
            $table->decimal('discount_perc', 11)->default(0);
            $table->decimal('discount', 11)->default(0);
            $table->decimal('total_amount', 11)->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('narrative', 250)->nullable();
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
        Schema::dropIfExists('invoice_particular');
    }
}
