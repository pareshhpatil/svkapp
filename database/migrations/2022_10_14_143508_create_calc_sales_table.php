<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalcSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calc_sales', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->dateTime('Date')->nullable();
            $table->integer('Invoice_No')->nullable();
            $table->string('Payment_Type', 17)->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->string('Order_Type', 16)->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->string('Item_Name', 41)->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->decimal('Price', 5)->nullable();
            $table->decimal('Qty', 3)->nullable();
            $table->decimal('Sub_Total', 5)->nullable();
            $table->decimal('Discount', 4)->nullable();
            $table->decimal('Tax', 4)->nullable();
            $table->decimal('Final_Total', 5)->nullable();
            $table->string('Status', 7)->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->integer('Table_No')->nullable();
            $table->string('Server_Name', 6)->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->integer('Covers')->nullable();
            $table->string('Variation', 6)->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->string('Category', 22)->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->string('HSN', 2)->charset('utf8')->collation('utf8_general_ci')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calc_sales');
    }
}
