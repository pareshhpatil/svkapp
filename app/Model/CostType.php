<?php

namespace App\Model;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * @property mixed $id
 * @property mixed $name
 * @property mixed $abbrevation
 * @property mixed $merchant_id
 * @property mixed $created_by
 * @property mixed $last_update_by
 * @property false|mixed|string $created_date
 * @property false|mixed|string $last_update_date
 *
 */
class CostType extends ParentModel
{
    protected $table = 'cost_types';


    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'last_update_date';

    protected $fillable = [
        'name'
    ];
}