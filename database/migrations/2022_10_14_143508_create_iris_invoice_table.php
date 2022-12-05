<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIrisInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('iris_invoice', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('id', true);
            $table->char('merchant_id', 10)->nullable()->index('merchant_idx');
            $table->char('payment_request_id', 10)->nullable();
            $table->string('invTyp', 10)->nullable();
            $table->string('splyTy', 10);
            $table->char('dst', 2)->nullable();
            $table->string('refnum', 100)->nullable();
            $table->date('pdt')->nullable();
            $table->char('ctpy', 2)->nullable();
            $table->char('cptycde', 10)->nullable();
            $table->char('rtpy', 2)->nullable();
            $table->string('ctin', 15)->nullable();
            $table->string('cname', 90)->nullable();
            $table->string('ntNum', 45)->nullable();
            $table->date('ntDt')->nullable();
            $table->string('inum', 16)->nullable()->index('inum_idx');
            $table->date('idt')->nullable();
            $table->decimal('val', 15)->default(0);
            $table->char('pos', 3)->default('0');
            $table->char('rchrg', 2)->nullable();
            $table->string('fy', 10)->nullable();
            $table->char('dty', 3)->nullable();
            $table->string('rsn', 38)->nullable();
            $table->char('pgst', 2)->nullable();
            $table->char('prs', 2)->nullable();
            $table->string('odnum', 30)->nullable();
            $table->string('gen2', 30)->nullable();
            $table->string('gen7', 30)->nullable();
            $table->string('gen8', 30)->nullable();
            $table->string('gen10', 30)->nullable();
            $table->string('gen11', 30)->nullable();
            $table->string('gen12', 30)->nullable();
            $table->string('gen13', 30)->nullable();
            $table->string('gstin', 15)->nullable();
            $table->string('fp', 10)->nullable();
            $table->string('ft', 15)->nullable();
            $table->string('order_id', 100)->nullable();
            $table->date('order_date')->nullable();
            $table->string('address', 250)->nullable();
            $table->string('payment_mode', 20)->nullable();
            $table->string('source', 45)->nullable();
            $table->integer('bulk_id')->default(0);
            $table->boolean('status')->default(false);
            $table->boolean('is_active')->default(true);
            $table->string('created_by', 10);
            $table->timestamp('created_date')->default('2014-01-01 00:00:00');
            $table->string('last_update_by', 10);
            $table->timestamp('last_update_date')->useCurrentOnUpdate()->useCurrent();

            $table->index(['merchant_id', 'gstin'], 'comp_mid_gstn');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('iris_invoice');
    }
}
