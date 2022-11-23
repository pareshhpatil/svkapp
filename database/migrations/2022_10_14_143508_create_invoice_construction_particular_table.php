<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceConstructionParticularTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_construction_particular', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('id', true);
            $table->char('payment_request_id', 10)->index('payment_request_id_idx');
            $table->string('bill_code', 100)->nullable();
            $table->string('description', 500)->nullable();
            $table->string('bill_type', 100)->nullable();
            $table->decimal('original_contract_amount', 11)->default(0);
            $table->decimal('approved_change_order_amount', 11)->default(0);
            $table->decimal('current_contract_amount', 11)->default(0);
            $table->decimal('previously_billed_percent', 11)->default(0);
            $table->decimal('previously_billed_amount', 11)->default(0);
            $table->decimal('current_billed_percent', 11)->default(0);
            $table->decimal('current_billed_amount', 11)->default(0);
            $table->decimal('total_billed', 11)->default(0);
            $table->decimal('retainage_percent', 11)->default(0);
            $table->decimal('retainage_amount_previously_withheld', 11)->default(0);
            $table->decimal('retainage_amount_for_this_draw', 11)->default(0);
            $table->decimal('retainage_release_amount', 11)->default(0);
            $table->decimal('total_outstanding_retainage', 11)->default(0);
            $table->string('project', 100)->nullable();
            $table->string('cost_code', 100)->nullable();
            $table->string('cost_type', 100)->nullable();
            $table->string('group_code1', 100)->nullable();
            $table->string('group_code2', 100)->nullable();
            $table->string('group_code3', 100)->nullable();
            $table->string('group_code4', 100)->nullable();
            $table->string('group_code5', 100)->nullable();
            $table->string('calculated_perc', 45)->nullable();
            $table->string('calculated_row', 250)->nullable();
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
        Schema::dropIfExists('invoice_construction_particular');
    }
}
