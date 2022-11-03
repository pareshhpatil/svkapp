<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignAcquisitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_acquisitions', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('id', true);
            $table->integer('campaign_id')->default(0);
            $table->string('type', 20);
            $table->string('merchant_id', 10)->nullable();
            $table->string('company_name', 100);
            $table->string('email', 250);
            $table->string('mobile', 15);
            $table->string('utm_source', 45);
            $table->string('utm_campaign', 45);
            $table->string('utm_adgroup', 45);
            $table->string('utm_term', 45);
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
        Schema::dropIfExists('campaign_acquisitions');
    }
}
