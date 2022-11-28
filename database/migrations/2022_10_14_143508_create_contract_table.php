<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contract', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('contract_id', true);
            $table->string('contract_code', 45)->nullable();
            $table->char('merchant_id', 10)->nullable()->index('IDX_PAYMENT_REQUEST_USER_ID');
            $table->integer('customer_id')->index('IDX_PAYMENT_REQUEST_PATRON_ID');
            $table->integer('project_id')->index('IDX_PAYMENT_REQUEST_PORJECT_ID');
            $table->decimal('contract_amount', 11)->default(0);
            $table->date('contract_date');
            $table->date('bill_date');
            $table->string('billing_frequency', 20);
            $table->longText('particulars');
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
        Schema::dropIfExists('contract');
    }
}
