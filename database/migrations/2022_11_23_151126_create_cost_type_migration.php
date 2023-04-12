<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCostTypeMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cost_types', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('id', true);
            $table->string('name', 45);
            $table->string('abbrevation', 2);
            $table->char('merchant_id', 10)->index('merchant_cost_types_idx');
            $table->string('created_by', 10);
            $table->string('last_update_by', 10);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cost_types');
    }
}
