<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemTemplateColumnMetadataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_template_column_metadata', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->bigIncrements('system_column_id');
            $table->char('system_template_id', 10);
            $table->string('column_datatype', 45);
            $table->string('system_col_name', 45)->nullable();
            $table->char('position', 1)->default('L');
            $table->mediumInteger('column_position');
            $table->string('column_name', 45);
            $table->string('default_column_value', 45)->nullable();
            $table->string('column_type', 45);
            $table->boolean('is_mandatory')->default(false);
            $table->boolean('is_delete_allow')->default(true);
            $table->integer('function_id')->default(0);
            $table->string('save_table_name', 20)->nullable()->default('metadata');
            $table->char('column_group_id', 10)->nullable();
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
        Schema::dropIfExists('system_template_column_metadata');
    }
}
