<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBenefitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('benefits', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('id', true);
            $table->string('title', 100);
            $table->string('short_description', 400);
            $table->longText('long_description');
            $table->longText('offer');
            $table->string('application_process', 1000);
            $table->string('logo', 100);
            $table->string('product_images', 1000);
            $table->char('value_type', 11);
            $table->decimal('offer_value', 11);
            $table->string('offer_value_text', 20);
            $table->enum('currency', ['inr', 'usd'])->default('inr');
            $table->string('email_copy', 1000)->nullable();
            $table->tinyInteger('is_active');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('category', 45);
            $table->tinyInteger('application_type');
            $table->string('coupon', 45)->nullable();
            $table->string('registration_link', 100)->nullable();
            $table->string('company_email', 250)->nullable();
            $table->tinyInteger('display_order');
            $table->enum('type', ['free', 'startup', 'growth'])->nullable();
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
        Schema::dropIfExists('benefits');
    }
}
