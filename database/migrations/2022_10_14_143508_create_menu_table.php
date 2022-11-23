<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu', function (Blueprint $table) {
            $table->collation = 'utf8_general_ci';
            $table->charset = 'utf8';

            $table->integer('id', true);
            $table->string('eng_title', 100)->charset('latin1')->collation('latin1_swedish_ci');
            $table->string('hindi_title', 100)->charset('latin1')->collation('latin1_swedish_ci')->nullable();
            $table->integer('parent_id');
            $table->integer('seq')->default(0);
            $table->string('icon', 100)->charset('latin1')->collation('latin1_swedish_ci')->nullable();
            $table->string('link', 100)->charset('latin1')->collation('latin1_swedish_ci')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('created_by', 10)->charset('latin1')->collation('latin1_swedish_ci');
            $table->timestamp('created_date')->default('2014-01-01 00:00:00');
            $table->string('last_update_by', 10)->charset('latin1')->collation('latin1_swedish_ci');
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
        Schema::dropIfExists('menu');
    }
}
