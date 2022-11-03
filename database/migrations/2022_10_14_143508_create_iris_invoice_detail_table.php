<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIrisInvoiceDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('iris_invoice_detail', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('id', true);
            $table->integer('invoice_id')->index('invoice_idx');
            $table->integer('num')->nullable();
            $table->decimal('sval', 11)->default(0);
            $table->char('ty', 2)->nullable();
            $table->string('hsnSc', 20)->nullable();
            $table->string('desc', 500)->nullable();
            $table->string('uqc', 30)->nullable();
            $table->string('elg', 45)->nullable();
            $table->string('txI', 45)->nullable();
            $table->char('txp', 10)->nullable();
            $table->string('qty', 20)->nullable();
            $table->decimal('txval', 11)->default(0);
            $table->decimal('irt', 11)->default(0);
            $table->decimal('iamt', 11)->default(0);
            $table->decimal('crt', 11)->default(0);
            $table->decimal('camt', 11)->default(0);
            $table->decimal('srt', 11)->default(0);
            $table->decimal('samt', 11)->default(0);
            $table->decimal('csrt', 11)->default(0);
            $table->decimal('csamt', 11)->default(0);
            $table->decimal('disc', 11)->default(0);
            $table->decimal('adval', 11)->default(0);
            $table->decimal('rt', 11)->default(0);
            $table->string('product_code', 45)->nullable();
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
        Schema::dropIfExists('iris_invoice_detail');
    }
}
