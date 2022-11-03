<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStagingBeneficiaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staging_beneficiary', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('beneficiary_id', true);
            $table->string('merchant_id', 10);
            $table->string('type', 10);
            $table->string('beneficiary_code', 45)->nullable();
            $table->string('name', 100);
            $table->string('email_id', 254);
            $table->string('mobile', 13);
            $table->string('address', 250)->nullable();
            $table->string('city', 45)->nullable();
            $table->string('state', 45)->nullable();
            $table->string('zipcode', 10)->nullable();
            $table->string('bank_account_no', 20)->nullable();
            $table->string('ifsc_code', 20)->nullable();
            $table->string('upi', 45)->nullable();
            $table->boolean('status')->default(false);
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
        Schema::dropIfExists('staging_beneficiary');
    }
}
