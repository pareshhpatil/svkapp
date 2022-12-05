<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventPackageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_package', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('package_id', true);
            $table->string('category_name', 45)->nullable();
            $table->string('package_name', 250);
            $table->boolean('package_type')->default(true);
            $table->longText('occurence');
            $table->longText('package_description')->nullable();
            $table->string('event_request_id', 10)->index('event_id');
            $table->integer('seats_available')->default(0);
            $table->integer('min_seat')->default(1);
            $table->integer('max_seat')->default(1);
            $table->decimal('price', 11)->default(0);
            $table->string('tax_text', 45)->nullable()->default('Service charge & Tax');
            $table->decimal('tax', 11)->default(0);
            $table->decimal('min_price', 11)->default(0);
            $table->decimal('max_price', 11)->default(0);
            $table->integer('coupon_code')->nullable()->default(0);
            $table->boolean('is_flexible')->default(false);
            $table->boolean('sold_out')->default(false);
            $table->string('currency_price', 500)->nullable();
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
        Schema::dropIfExists('event_package');
    }
}
