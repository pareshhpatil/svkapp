<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantBankDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_bank_detail', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('bank_detail_id', true);
            $table->string('merchant_id', 10)->index('merchant_idx');
            $table->string('account_holder_name', 100)->nullable();
            $table->string('bank_account_name', 100)->nullable();
            $table->string('account_no', 20);
            $table->string('ifsc_code', 12);
            $table->integer('account_type');
            $table->string('bank_name', 100);
            $table->string('adhar_card', 150)->nullable();
            $table->string('pan_card', 150)->nullable();
            $table->string('cancelled_cheque', 150)->nullable();
            $table->string('gst_certificate', 150)->nullable();
            $table->string('address_proof', 500)->nullable();
            $table->string('company_incorporation_certificate', 150)->nullable();
            $table->string('business_reg_proof', 500)->nullable();
            $table->string('partnership_deed', 150)->nullable();
            $table->string('partner_pan_card', 500)->nullable();
            $table->string('company_moa', 500)->nullable();
            $table->string('company_aoa', 500)->nullable();
            $table->decimal('penny_amount', 11)->default(0);
            $table->boolean('penny_amount_tried')->default(false);
            $table->boolean('penny_amount_count')->default(true);
            $table->boolean('account_verify_count')->default(true);
            $table->boolean('gst_available')->default(false);
            $table->boolean('verification_status')->default(false);
            $table->string('stripe_account_id', 45)->nullable();
            $table->string('payoneer_account_id', 45)->nullable();
            $table->boolean('stripe_status')->default(false);
            $table->boolean('international_payment')->default(false);
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
        Schema::dropIfExists('merchant_bank_detail');
    }
}
