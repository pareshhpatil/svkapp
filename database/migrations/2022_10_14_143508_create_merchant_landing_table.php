<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantLandingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_landing', function (Blueprint $table) {
            $table->collation = 'utf8mb4_unicode_ci';
            $table->charset = 'utf8mb4';

            $table->integer('merchant_landing_id', true);
            $table->char('merchant_id', 10)->charset('latin1')->collation('latin1_swedish_ci')->nullable()->index('merchant_id_idx');
            $table->longText('overview')->nullable();
            $table->longText('terms_condition')->nullable();
            $table->longText('cancellation_policy')->nullable();
            $table->longText('about_us')->charset('latin1')->collation('latin1_swedish_ci')->nullable();
            $table->string('office_location')->charset('latin1')->collation('latin1_swedish_ci')->nullable();
            $table->string('contact_no', 13)->charset('latin1')->collation('latin1_swedish_ci')->nullable();
            $table->string('email_id', 250)->charset('latin1')->collation('latin1_swedish_ci')->nullable();
            $table->string('logo', 100)->charset('latin1')->collation('latin1_swedish_ci')->nullable();
            $table->string('banner', 100)->charset('latin1')->collation('latin1_swedish_ci')->nullable();
            $table->string('booking_background', 250)->charset('latin1')->collation('latin1_swedish_ci')->nullable();
            $table->string('booking_title', 250)->charset('latin1')->collation('latin1_swedish_ci')->nullable();
            $table->boolean('booking_hide_menu')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_complete_company_page')->default(false);
            $table->string('search_bill_text', 250)->nullable();
            $table->longText('pay_my_bill_text')->nullable();
            $table->longText('pay_my_bill_paragraph')->nullable();
            $table->longText('banner_text')->nullable();
            $table->longText('banner_paragraph')->nullable();
            $table->boolean('publishable')->default(true);
            $table->timestamp('created_date')->default('2014-01-01 00:00:00');
            $table->string('cf_response', 45)->charset('latin1')->collation('latin1_swedish_ci')->nullable()->default('0');
            $table->string('cf_id', 45)->charset('latin1')->collation('latin1_swedish_ci')->nullable();
            $table->string('last_update_by', 10)->charset('latin1')->collation('latin1_swedish_ci')->nullable();
            $table->timestamp('last_update_date')->useCurrentOnUpdate()->useCurrent();
            $table->string('created_by', 10)->charset('latin1')->collation('latin1_swedish_ci')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('merchant_landing');
    }
}
