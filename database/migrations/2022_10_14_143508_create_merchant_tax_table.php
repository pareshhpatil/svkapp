<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantTaxTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_tax', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('tax_id', true);
            $table->string('merchant_id', 10)->index('merchant_idx');
            $table->string('type', 20)->nullable();
            $table->string('tax_name', 500)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable();
            $table->boolean('tax_type')->default(false);
            $table->decimal('percentage', 11)->default(0);
            $table->decimal('fix_amount', 11)->default(0);
            $table->string('description', 500)->nullable();
            $table->boolean('is_default')->nullable()->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('tax_calculated_on')->default(false);
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
        Schema::dropIfExists('merchant_tax');
    }
}
