<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePreferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('preferences', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->string('user_id', 10)->primary();
            $table->boolean('send_sms')->nullable()->default(true);
            $table->boolean('send_email')->nullable()->default(true);
            $table->boolean('send_app')->nullable()->default(true);
            $table->timestamp('created_date')->nullable()->default('2014-01-01 00:00:00');
            $table->timestamp('last_update_date')->useCurrentOnUpdate()->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('preferences');
    }
}
