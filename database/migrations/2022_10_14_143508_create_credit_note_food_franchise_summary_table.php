<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditNoteFoodFranchiseSummaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_note_food_franchise_summary', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->increments('id');
            $table->integer('credit_note_id')->index('credit_note_idx');
            $table->decimal('gross_comm_percent', 5)->nullable()->default(0);
            $table->decimal('waiver_comm_percent', 5)->nullable()->default(0);
            $table->decimal('net_comm_percent', 5)->nullable()->default(0);
            $table->decimal('new_gross_comm_percent', 5)->nullable()->default(0);
            $table->decimal('new_waiver_comm_percent', 5)->nullable()->default(0);
            $table->decimal('new_net_comm_percent', 5)->nullable()->default(0);
            $table->decimal('gross_comm_amt', 11)->nullable()->default(0);
            $table->decimal('waiver_comm_amt', 11)->nullable()->default(0);
            $table->decimal('net_comm_amt', 11)->nullable()->default(0);
            $table->decimal('new_gross_comm_amt', 11)->nullable()->default(0);
            $table->decimal('new_waiver_comm_amt', 11)->nullable()->default(0);
            $table->decimal('new_net_comm_amt', 11)->nullable()->default(0);
            $table->decimal('gross_bilable_sale', 11)->nullable()->default(0);
            $table->decimal('new_gross_bilable_sale', 11)->nullable()->default(0);
            $table->decimal('net_bilable_sale', 11)->nullable()->default(0);
            $table->decimal('new_net_bilable_sale', 11)->nullable()->default(0);
            $table->decimal('non_brand_gross_comm_percent', 5)->nullable()->default(0);
            $table->decimal('non_brand_waiver_comm_percent', 5)->nullable()->default(0);
            $table->decimal('non_brand_net_comm_percent', 5)->nullable()->default(0);
            $table->decimal('non_brand_new_gross_comm_percent', 5)->nullable()->default(0);
            $table->decimal('non_brand_new_waiver_comm_percent', 5)->nullable()->default(0);
            $table->decimal('non_brand_new_net_comm_percent', 5)->nullable()->default(0);
            $table->decimal('non_brand_gross_comm_amt', 11)->nullable()->default(0);
            $table->decimal('non_brand_waiver_comm_amt', 11)->nullable()->default(0);
            $table->decimal('non_brand_net_comm_amt', 11)->nullable()->default(0);
            $table->decimal('non_brand_new_gross_comm_amt', 11)->nullable()->default(0);
            $table->decimal('non_brand_new_waiver_comm_amt', 11)->nullable()->default(0);
            $table->decimal('non_brand_new_net_comm_amt', 11)->nullable()->default(0);
            $table->decimal('non_brand_gross_bilable_sale', 11)->nullable()->default(0);
            $table->decimal('non_brand_new_gross_bilable_sale', 11)->nullable()->default(0);
            $table->decimal('non_brand_net_bilable_sale', 11)->nullable()->default(0);
            $table->decimal('non_brand_new_net_bilable_sale', 11)->nullable()->default(0);
            $table->decimal('penalty', 11)->nullable()->default(0);
            $table->decimal('new_penalty', 11)->nullable()->default(0);
            $table->decimal('totalcost', 11)->nullable()->default(0);
            $table->decimal('new_totalcost', 11)->nullable()->default(0);
            $table->decimal('previous_dues', 11)->nullable()->default(0);
            $table->decimal('new_previous_dues', 11)->nullable()->default(0);
            $table->string('bill_period', 30)->nullable();
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
        Schema::dropIfExists('credit_note_food_franchise_summary');
    }
}
