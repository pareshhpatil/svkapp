<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_template', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->char('template_id', 10)->primary();
            $table->char('merchant_id', 10)->nullable()->index('merchant_id_idx');
            $table->char('user_id', 10)->index('user_id_idx');
            $table->string('template_name', 250);
            $table->string('template_type', 50)->nullable();
            $table->string('particular_column', 5000)->nullable();
            $table->string('default_particular', 500)->nullable();
            $table->string('particular_values', 500)->nullable();
            $table->string('default_tax', 100)->nullable();
            $table->string('particular_total', 45)->nullable()->default('Particular total');
            $table->string('tax_total', 45)->nullable()->default('Tax total');
            $table->longText('plugin')->nullable();
            $table->longText('tnc')->nullable();
            $table->integer('profile_id')->default(0);
            $table->longText('properties')->nullable();
            $table->boolean('hide_invoice_summary')->default(false);
            $table->boolean('is_active')->default(true);
            $table->string('image_path', 250)->nullable();
            $table->string('banner_path', 250)->nullable();
            $table->string('invoice_title', 100)->default('Performa Invoice');
            $table->string('created_by', 10);
            $table->timestamp('created_date')->default('2014-01-01 00:00:00');
            $table->string('last_update_by', 10);
            $table->timestamp('last_update_date')->useCurrentOnUpdate()->useCurrent();
            $table->string('design_name', 45)->nullable();
            $table->string('design_color', 45)->nullable();
            $table->string('footer_note', 250)->nullable();
            $table->text('setting')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_template');
    }
}
