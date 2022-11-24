<?php

namespace App\Model;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * @property mixed $id
 * @property mixed $name
 * @property mixed $abbrevation
 * @property mixed $created_by
 * @property mixed $last_update_by
 * @property false|mixed|string $created_date
 *
 */
class CostType extends ParentModel
{
    protected $table = 'cost_types';

    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'last_update_date';

    public function getCostTypeList()
    {
        return self::query()
            ->get(['id', 'name', 'abbrevation']);
    }

    public function saveCostType($request, $user_id)
    {
        DB::table($this->table)->insertGetId([
            'name' => $request->get('name'),
            'abbrevation' => $request->get('abbrevation'),
            'created_by' => $user_id,
            'last_update_by' => $user_id,
            'created_date' => date('Y-m-d H:i:s')
        ]);
    }

    public function updateCostType($id, $request, $user_id)
    {

        DB::table($this->table)->where('id', $id)
            ->update([
                'name' => $request->get('name'),
                'abbrevation' => $request->get('abbrevation'),
                'created_by' => $user_id,
                'last_update_by' => $user_id,
                'created_date' => date('Y-m-d H:i:s')
            ]);
    }
}