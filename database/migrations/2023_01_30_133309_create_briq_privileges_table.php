<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Constants\Models\ITable;

class CreateBriqPrivilegesTable extends Migration
{
    /**
     * Schema table name to migrate
     *
     * @var string
     */
    public $setSchemaTable = ITable::BRIQ_PRIVILEGES;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->setSchemaTable)) {
            return;
        }

        Schema::create($this->setSchemaTable, function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('type_label');
            $table->string('type_id');
            $table->char('user_id', 10);
            $table->char('merchant_id', 10)->nullable();
            $table->string('access')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['type'], 'type_index');
            $table->index(['type_id'], 'type_id_index');
            $table->index(['user_id'], 'user_id_index');
            $table->index(['merchant_id'], 'merchant_id_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->setSchemaTable);
    }
}
