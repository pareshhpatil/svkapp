<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnsubscribeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unsubscribe', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('id', true);
            $table->string('email', 250)->nullable()->index('email');
            $table->string('mobile', 15)->nullable();
            $table->string('full_name', 50)->nullable();
            $table->string('payment_request_id', 10)->nullable()->default('');
            $table->string('merchant_id', 10)->nullable()->default('');
            $table->boolean('reason_type')->nullable()->default(true);
            $table->string('reason', 500)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('created_date')->nullable()->default('2014-01-01 00:00:00');
            $table->timestamp('last_update_date')->useCurrentOnUpdate()->nullable()->useCurrent();

            $table->index(['email'], 'mobile');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('unsubscribe');
    }
}
