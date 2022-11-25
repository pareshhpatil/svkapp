<?php

namespace App\Model\Merchant;

use App\Constants\Models\ITable;
use App\Model\Base;
use App\Model\ParentModel;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property mixed $id
 * @property mixed $name
 * @property mixed $abbrevation
 * @property mixed $merchant_id
 * @property mixed $created_by
 * @property mixed $last_update_by
 * @property false|mixed|string $created_at
 * @property false|mixed|string $updated_at
 *
 */
class CostType extends Base
{
    use SoftDeletes;

    protected $table = ITable::COST_TYPES;

    protected $fillable = [
        'name',
        'abbrevation',
        'merchant_id',
        'created_by',
        'last_update_by'
    ];
}