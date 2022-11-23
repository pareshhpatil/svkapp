<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminDataTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_data_types', function (Blueprint $table) {
            $table->collation = 'utf8mb4_unicode_ci';
            $table->charset = 'utf8mb4';

            $table->increments('id');
            $table->string('name', 191)->unique('data_types_name_unique');
            $table->string('slug', 191)->unique('data_types_slug_unique');
            $table->string('display_name_singular', 191);
            $table->string('display_name_plural', 191);
            $table->string('icon', 191)->nullable();
            $table->string('model_name', 191)->nullable();
            $table->string('policy_name', 191)->nullable();
            $table->string('controller', 191)->nullable();
            $table->string('description', 191)->nullable();
            $table->boolean('generate_permissions')->default(false);
            $table->tinyInteger('server_side')->default(0);
            $table->text('details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_data_types');
    }
}
