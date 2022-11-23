<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIrisR1UploadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('iris_r1_upload', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('upload_id', true);
            $table->string('merchant_id', 10)->index('merchant_id_idx');
            $table->boolean('status')->default(false);
            $table->integer('total_invoices')->default(0);
            $table->string('fp', 10);
            $table->char('type', 3)->default('ALL');
            $table->string('gstin', 20);
            $table->longText('invoice_ids')->nullable();
            $table->longText('error_json')->nullable();
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
        Schema::dropIfExists('iris_r1_upload');
    }
}
