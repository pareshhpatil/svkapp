<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('supplier_id', true);
            $table->char('merchant_id', 10)->nullable()->index('merchant_idx');
            $table->string('user_id', 10);
            $table->string('supplier_company_name', 100);
            $table->string('contact_person_name', 100);
            $table->string('contact_person_name2', 100);
            $table->string('email_id1', 254);
            $table->string('email_id2', 254)->nullable();
            $table->string('mob_country_code1', 6)->default('+91');
            $table->string('mobile1', 13);
            $table->string('mob_country_code2', 6)->nullable()->default('+91');
            $table->string('mobile2', 13)->nullable();
            $table->integer('industry_type');
            $table->string('company_website', 70)->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('created_by', 10);
            $table->timestamp('created_date')->default('2014-01-01 00:00:00');
            $table->string('last_updated_by', 10);
            $table->timestamp('last_updated_date')->default('2014-01-01 00:00:00');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supplier');
    }
}
