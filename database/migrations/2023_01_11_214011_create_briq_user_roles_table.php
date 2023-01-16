<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Constants\Models\ITable;

class CreateBriqUserRolesTable extends Migration
{
    /**
     * Schema table name to migrate
     *
     * @var string
     */
    public $setSchemaTable = ITable::BRIQ_USER_ROLES;
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
            $table->string('user_id', 10);
            $table->unsignedInteger('role_id');
            $table->string('role_name');
            $table->string('created_by', 10)
                  ->nullable();
            $table->string('updated_by', 10)
                  ->nullable();
            $table->index(['role_id'], 'role_id_index');
            $table->index(['user_id'], 'user_id_index');
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
        Schema::dropIfExists($this->setSchemaTable);
    }
}
