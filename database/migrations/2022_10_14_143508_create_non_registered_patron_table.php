<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNonRegisteredPatronTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('non_registered_patron', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->char('patron_id', 10)->primary();
            $table->string('first_name', 50)->nullable();
            $table->string('last_name', 50)->nullable();
            $table->string('email_id', 254);
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('city', 45)->nullable();
            $table->integer('zipcode')->nullable();
            $table->string('state', 45)->nullable();
            $table->smallInteger('mob_country_code')->nullable();
            $table->bigInteger('mobile_no')->nullable();
            $table->boolean('is_registered')->default(false);
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
        Schema::dropIfExists('non_registered_patron');
    }
}
