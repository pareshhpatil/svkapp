<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromotionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotion', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('id', true);
            $table->string('name', 100)->nullable();
            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
            $table->longText('invoice_content')->nullable();
            $table->longText('mail_content')->nullable();
            $table->string('banner', 100)->nullable();
            $table->longText('description')->nullable();
            $table->longText('terms')->nullable();
            $table->boolean('is_active')->nullable();
            $table->string('created_by', 10)->nullable();
            $table->timestamp('created_date')->nullable()->default('2014-01-01 00:00:00');
            $table->string('updated_by', 10)->nullable();
            $table->timestamp('updated_date')->useCurrentOnUpdate()->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promotion');
    }
}
