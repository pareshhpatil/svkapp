<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_translations', function (Blueprint $table) {
            $table->collation = 'utf8mb4_unicode_ci';
            $table->charset = 'utf8mb4';

            $table->increments('id');
            $table->string('table_name', 191);
            $table->string('column_name', 191);
            $table->unsignedInteger('foreign_key');
            $table->string('locale', 191);
            $table->text('value');
            $table->timestamps();

            $table->unique(['table_name', 'column_name', 'foreign_key', 'locale'], 'translations_table_name_column_name_foreign_key_locale_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_translations');
    }
}
