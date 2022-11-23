<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class InvoiceColumnMetadata extends Model
{

    protected $table = 'invoice_column_metadata';
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'last_update_date';

    public static function saveFunctionMapping($column_id, $function_id, $param, $value, $user_id)
    {
        self::deleteMapping($column_id);
        DB::table('column_function_mapping')->insertGetId(
            [
                'column_id' => $column_id,
                'function_id' => $function_id,
                'param' => $param,
                'value' => $value,
                'created_by' => $user_id,
                'last_update_by' => $user_id,
                'created_date' => date('Y-m-d H:i:s')
            ]
        );
    }

    public static function deleteMapping($id)
    {
        DB::table('column_function_mapping')
            ->where('column_id', $id)
            ->update([
                'is_active' => 0
            ]);
    }
}
