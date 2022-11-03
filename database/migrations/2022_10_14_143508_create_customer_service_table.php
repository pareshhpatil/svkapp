<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_service', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('id', true);
            $table->integer('customer_id')->index('customer_idx');
            $table->char('merchant_id', 10);
            $table->integer('service_type')->default(1);
            $table->string('name', 45);
            $table->string('narrative', 250);
            $table->decimal('cost', 11)->default(0);
            $table->boolean('status')->default(false);
            $table->date('expiry_date')->default('2014-01-01');
            $table->boolean('is_active')->default(true);
            $table->string('created_by', 10);
            $table->timestamp('created_date')->default('2014-01-01 00:00:00');
            $table->string('updated_by', 10);
            $table->timestamp('updated_date')->useCurrentOnUpdate()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_service');
    }
}
