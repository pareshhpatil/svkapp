<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantSecurityKeyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_security_key', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('key_id', true);
            $table->string('merchant_id', 10);
            $table->string('user_id', 10);
            $table->string('access_key_id', 45);
            $table->string('secret_access_key', 45);
            $table->string('cashfree_client_id', 45)->nullable();
            $table->string('cashfree_client_secret', 45)->nullable();
            $table->string('payout_client_id', 45)->nullable();
            $table->string('payout_secret_id', 45)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('created_date')->default('2014-01-01 00:00:00');
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
        Schema::dropIfExists('merchant_security_key');
    }
}
