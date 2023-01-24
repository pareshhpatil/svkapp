<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Constants\Models\ITable;

class CreateBriqPermissionsTables extends Migration
{
    /**
     * Schema table name to migrate
     *
     * @var string
     */
    public $setSchemaTable = ITable::BRIQ_PERMISSIONS;

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
            $table->string('name');
            $table->string('slug');
            $table->string('group')
                ->nullable();
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
