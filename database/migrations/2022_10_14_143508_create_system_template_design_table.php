<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemTemplateDesignTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_template_design', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('id', true);
            $table->string('design_name', 100);
            $table->string('title', 100);
            $table->string('description', 500);
            $table->text('image');
            $table->string('color', 50);
            $table->integer('is_active');
            $table->integer('sequence');
            $table->dateTime('created_date')->default('2014-01-01 00:00:00');
            $table->timestamp('last_update_date')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_template_design');
    }
}
