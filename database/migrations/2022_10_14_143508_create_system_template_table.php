<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_template', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->char('system_template_id', 10)->primary();
            $table->string('type', 20)->default('');
            $table->string('template_name', 45);
            $table->string('template_type', 50)->nullable();
            $table->longText('template_description')->nullable();
            $table->string('thumbnail', 100)->nullable();
            $table->longText('particular_column')->nullable();
            $table->longText('plugin')->nullable();
            $table->tinyInteger('order')->default(0);
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
        Schema::dropIfExists('system_template');
    }
}
