<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGstr2bReconJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gstr2b_recon_jobs', function (Blueprint $table) {
            $table->collation = 'utf8mb4_unicode_ci';
            $table->charset = 'utf8mb4';

            $table->integer('id', true);
            $table->string('merchant_id', 20)->nullable();
            $table->string('gstin', 50)->nullable();
            $table->string('gst_from_month', 20)->nullable();
            $table->string('gst_from_year', 20)->nullable();
            $table->string('gst_to_month', 20)->nullable();
            $table->string('gst_to_year', 20)->nullable();
            $table->string('expense_from_month', 20)->nullable();
            $table->string('expense_from_year', 20)->nullable();
            $table->string('expense_to_month', 20)->nullable();
            $table->string('expense_to_year', 20)->nullable();
            $table->string('gst2a_input_file', 500)->nullable();
            $table->string('expense_input_file', 500)->nullable();
            $table->enum('status', ['processing', 'processed', 'error'])->nullable()->default('processing');
            $table->string('created_by', 10)->nullable();
            $table->string('last_update_by', 10)->nullable();
            $table->timestamp('last_update_date')->useCurrentOnUpdate()->useCurrent();
            $table->timestamp('created_date')->default('2014-01-01 00:00:00');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gstr2b_recon_jobs');
    }
}
