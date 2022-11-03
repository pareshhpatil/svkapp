<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->collation = 'latin1_swedish_ci';
            $table->charset = 'latin1';

            $table->integer('id', true);
            $table->string('user_id', 10)->index('user_id');
            $table->string('name', 45)->nullable();
            $table->string('email_id', 254);
            $table->string('password', 250)->nullable();
            $table->string('mob_country_code', 5)->nullable();
            $table->string('mobile_no', 14)->nullable();
            $table->string('first_name', 50)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('last_name', 50)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->tinyInteger('user_status');
            $table->tinyInteger('prev_status')->default(0);
            $table->string('group_id', 10);
            $table->integer('user_group_type');
            $table->integer('franchise_id')->default(0);
            $table->boolean('enable_ticket')->default(false);
            $table->boolean('login_type')->default(true);
            $table->integer('user_type');
            $table->string('customer_group', 100)->nullable();
            $table->integer('master_login_group_id')->default(0);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('partner_user_id', 20)->nullable();
            $table->boolean('registered_from')->default(false);
            $table->string('created_by', 10);
            $table->timestamp('created_date')->default('2014-01-01 00:00:00');
            $table->string('last_updated_by', 10);
            $table->timestamp('last_updated_date')->useCurrentOnUpdate()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user');
    }
}
