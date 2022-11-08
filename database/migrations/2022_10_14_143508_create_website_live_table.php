<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebsiteLiveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('website_live', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('id', true);
            $table->string('merchant_id', 10);
            $table->string('merchant_domain', 100)->nullable();
            $table->string('txt_text', 100)->nullable();
            $table->string('domain_cname', 100)->nullable();
            $table->longText('json')->nullable();
            $table->boolean('status')->default(false);
            $table->boolean('is_active')->default(true);
            $table->string('created_by', 10);
            $table->timestamp('created_date')->default('2014-01-01 00:00:00');
            $table->string('last_update_by', 45);
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
        Schema::dropIfExists('website_live');
    }
}