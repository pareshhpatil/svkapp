<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Constants\Models\ITable;

class CreateBriqRoles extends Migration
{
    /**
     * Schema table name to migrate
     *
     * @var string
     */
    public $setSchemaTable = ITable::BRIQ_ROLES;

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
            $table->char('merchant_id', 10)->index('merchant_cost_types_idx');
            $table->string('name');
            $table->text('description')
                ->nullable();
            $table->string('created_by', 10);
            $table->string('last_updated_by', 10);
            $table->timestamps();
            $table->softDeletes();
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
