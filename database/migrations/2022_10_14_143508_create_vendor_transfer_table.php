<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorTransferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_transfer', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('transfer_id', true);
            $table->string('reference_id', 45)->nullable()->index('idx_payout_ref_id');
            $table->string('merchant_id', 10);
            $table->boolean('type')->default(false);
            $table->boolean('beneficiary_type')->default(true)->comment('1- Vendor
2- Franchise');
            $table->string('beneficiary_id', 45)->nullable();
            $table->integer('franchise_id')->default(0);
            $table->integer('vendor_id')->default(0);
            $table->integer('cashgram_id')->default(0);
            $table->decimal('amount', 11)->default(0);
            $table->string('narrative', 500)->nullable();
            $table->boolean('status')->default(false);
            $table->string('cashfree_transfer_id', 45)->default('0');
            $table->string('utr_number', 45)->nullable();
            $table->boolean('offline_response_type')->default(false);
            $table->date('transfer_date')->nullable();
            $table->string('bank_name', 100)->nullable();
            $table->string('bank_transaction_no', 45)->nullable();
            $table->string('cheque_no', 45)->nullable();
            $table->string('cash_paid_to', 100)->nullable();
            $table->string('mode', 20)->nullable();
            $table->integer('bulk_id')->default(0);
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
        Schema::dropIfExists('vendor_transfer');
    }
}
