<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBulkUploadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bulk_upload', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('bulk_upload_id', true);
            $table->boolean('type')->default(true);
            $table->char('merchant_id', 10)->index('merchant_id_idx');
            $table->string('merchant_filename', 100);
            $table->string('system_filename', 100);
            $table->boolean('status');
            $table->integer('total_rows')->default(0);
            $table->longText('error_json')->nullable();
            $table->longText('validate_value')->nullable();
            $table->string('sub_type', 20)->nullable();
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
        Schema::dropIfExists('bulk_upload');
    }
}
