<?php

namespace App\Model;

use Illuminate\Support\Facades\DB;

class CostType extends ParentModel
{
    protected $table = 'cost_types';

    public function getCostTypeList()
    {
        return self::query()
            ->get(['id', 'name', 'abbrevation']);
    }
}