<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashgramTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cashgram', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('cashgram_id', true);
            $table->char('merchant_id', 10)->index('merchant_idx');
            $table->decimal('amount', 11)->default(0);
            $table->string('narrative', 500);
            $table->boolean('status')->default(false);
            $table->string('name', 100)->nullable();
            $table->string('email_id', 250)->nullable();
            $table->string('mobile', 15)->nullable();
            $table->date('expiry_date')->nullable();
            $table->boolean('notify_customer')->default(true);
            $table->string('cashgram_link', 45)->nullable();
            $table->integer('reference_id')->default(0);
            $table->string('reason', 100)->nullable();
            $table->integer('franchise_id')->default(0);
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
        Schema::dropIfExists('cashgram');
    }
}
