<?php

namespace App\Models;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of payout
 *
 * @author Paresh
 */

use Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Exception;

class ParentModel extends Model
{
    public function getTableRow($table, $where, $value, $active = 0, $param = [], $select = '*')
    {

        $retObj = DB::table($table)
            ->select(DB::raw($select))
            ->where($where, $value);
        if ($active == 1) {
            $retObj->where('is_active', 1);
        }
        if (!empty($param)) {
            foreach ($param as $k => $v) {
                $retObj->where($k, $v);
            }
        }
        $array = $retObj->first();
        if (!empty($array)) {
            return $array;
        } else {
            return false;
        }
    }

    public function getColumnValue($table, $where, $value, $column_name, $param = [], $orderby = null)
    {

        $retObj = DB::table($table)
            ->select(DB::raw($column_name . ' as value'))
            ->where($where, $value);
        if (!empty($param)) {
            foreach ($param as $k => $v) {
                $retObj->where($k, $v);
            }
        }
        if ($orderby != null) {
            $retObj->orderByDesc($orderby);
        }
        $array = $retObj->first();
        if (!empty($array)) {
            return $array->value;
        } else {
            return false;
        }
    }
    public function getColumnValueWithAllRow($table, $where, $value, $column_name)
    {

        $retObj = DB::table($table)
            ->select(DB::raw($column_name . ' as value'))
            ->where($where, $value)
            ->get();
        if (!empty($retObj)) {
            return $retObj;
        } else {
            return false;
        }
    }
    public function getColumnValueExtraWhere($table, $where, $value, $column_name, $active, $status)
    {

        $retObj = DB::table($table)
            ->select(DB::raw($column_name . ' as value'))
            ->where($where, $value)
            ->where('is_active', $active)
            ->where('status', $status)

            ->first();
        if (!empty($retObj)) {
            return $retObj->value;
        } else {
            return false;
        }
    }

    public function getTableList($table, $where, $value, $array = 0, $project_ids = [], $param = [], $select = '*')
    {

        $retObj = DB::table($table)
            ->select(DB::raw($select))
            ->where('is_active', 1)
            ->where($where, $value);
        if (!empty($param)) {
            foreach ($param as $k => $v) {
                $retObj->where($k, $v);
            }
        }
        if (!empty($project_ids)) {
            $retObj->whereIn('project_id', $project_ids);
        }
        $retObja =  $retObj->get();
        if ($array == 1) {
            return json_decode(json_encode($retObja), 1);
        }
        return $retObja;
    }

    public function getTableListOrderby($table, $where, $value, $orderby)
    {

        $retObj = DB::table($table)
            ->select(DB::raw('*'))
            ->where('is_active', 1)
            ->where($where, $value)
            ->orderBy($orderby)
            ->get();
        return $retObj;
    }

    public function getTableListIn($table, $where, $value, $orderby)
    {

        $retObj = DB::table($table)
            ->select(DB::raw('*'))
            ->where('is_active', 1)
            ->whereIn($where, $value)
            ->orderBy($orderby)
            ->get();
        return $retObj;
    }

    public function getList($table, $param = [])
    {
        $retObj = DB::table($table)
            ->select(DB::raw('*'));
        if (!empty($param)) {
            foreach ($param as $k => $v) {
                $retObj->where($k, $v);
            }
        }
        return $retObj->get();
    }

    public function updateTable($table, $where, $whvalue, $col, $val)
    {
        DB::table($table)
            ->where($where, $whvalue)
            ->update([
                $col => $val
            ]);
    }

    public function updateWhereArray($table, $param, $update_array)
    {
        $retObj = DB::table($table);
        foreach ($param as $k => $v) {
            $retObj->where($k, $v);
        }
        $retObj->update($update_array);
    }

    public function updateArray($table, $where, $whvalue, $array)
    {
        DB::table($table)
            ->where($where, $whvalue)
            ->update($array);
    }




    public function isExistData($merchant_id, $table, $key, $value)
    {

        $retObj = DB::table($table)
            ->select(DB::raw('*'))
            ->where($key, $value)
            ->where('is_active', 1)
            ->where('merchant_id', $merchant_id)
            ->first();
        if (!empty($retObj)) {
            return true;
        } else {
            return false;
        }
    }


    public function querylist($query)
    {

        $retObj = DB::select($query);

        return $retObj;
    }

    public function saveTable($table, $array, $user_id = null)
    {
        $array['created_date'] = date('Y-m-d H:i:s');
        if ($user_id != null) {
            $array['created_by'] =  $user_id;
            $array['last_update_by'] =  $user_id;
        }
        return DB::table($table)->insertGetId(
            $array
        );
    }

    public function deleteTableRow($table, $column, $id, $merchant_id, $user_id)
    {

        DB::table($table)
            ->where($column, $id)
            ->where('merchant_id', $merchant_id)
            ->update([
                'is_active' => 0,
                'last_update_by' => $user_id
            ]);
    }
}
