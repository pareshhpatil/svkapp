<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStagingOfflineResponseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staging_offline_response', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('id', true);
            $table->char('payment_request_id', 10)->charset('utf8')->collation('utf8_general_ci')->index('payment_request_idx');
            $table->string('merchant_id', 10)->nullable()->index('merchant_idx');
            $table->tinyInteger('offline_response_type');
            $table->date('settlement_date');
            $table->string('bank_transaction_no', 20)->nullable();
            $table->string('bank_name', 100)->nullable();
            $table->decimal('amount', 11);
            $table->string('cheque_no', 20)->nullable();
            $table->string('cheque_status', 10)->nullable();
            $table->string('cash_paid_to', 100)->nullable();
            $table->string('narrative')->nullable();
            $table->boolean('notify_patron')->default(true);
            $table->integer('bulk_id')->default(0);
            $table->integer('is_active')->default(1);
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
        Schema::dropIfExists('staging_offline_response');
    }
}
