<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditNoteFoodFranchiseSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_note_food_franchise_sales', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->increments('id');
            $table->integer('credit_note_id')->index('credit_note_id');
            $table->date('date')->nullable();
            $table->decimal('inv_gross_sale', 11)->nullable()->default(0);
            $table->decimal('credit_gross_sale', 11)->nullable()->default(0);
            $table->decimal('non_brand_inv_gross_sale', 11)->default(0);
            $table->decimal('non_brand_credit_gross_sale', 11)->default(0);
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
        Schema::dropIfExists('credit_note_food_franchise_sales');
    }
}
