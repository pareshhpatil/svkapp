<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportHsnCodeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_hsn_code', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('id', true);
            $table->string('type', 45)->nullable();
            $table->string('code', 45)->nullable();
            $table->longText('description')->nullable();
            $table->string('gst', 45)->nullable();
            $table->string('chapter', 45)->nullable();
            $table->string('link', 250)->nullable();
            $table->timestamp('created_date')->useCurrentOnUpdate()->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('import_hsn_code');
    }
}
