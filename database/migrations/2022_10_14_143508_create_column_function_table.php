<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateColumnFunctionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('column_function', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('function_id', true);
            $table->string('function_name', 45)->nullable();
            $table->string('column_name', 45)->nullable();
            $table->string('mapping', 100)->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('datatype', 20)->nullable();
            $table->string('save_table_name', 45)->nullable();
            $table->string('php_class', 45)->nullable();
            $table->string('js_function', 45)->nullable();
            $table->string('created_by', 10)->nullable();
            $table->timestamp('created_date')->nullable()->default('2014-01-01 00:00:00');
            $table->string('last_update_by', 10)->nullable();
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
        Schema::dropIfExists('column_function');
    }
}
