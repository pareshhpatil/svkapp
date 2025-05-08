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
    public function getTableRow($table, $where, $value, $active = 0, $param = [])
    {

        $retObj = DB::table($table)
            ->select(DB::raw('*'))
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

    public function getTableCount($table, $where, $value, $active = 0, $param = [])
    {

        $retObj = DB::table($table)
            ->select(DB::raw('*'))
            ->where($where, $value);
        if ($active == 1) {
            $retObj->where('is_active', 1);
        }
        if (!empty($param)) {
            foreach ($param as $k => $v) {
                $retObj->where($k, $v);
            }
        }
        return $retObj->count();
    }

    public function getTableSum($table, $where, $value, $active = 0, $param = [], $sum_column)
    {

        $retObj = DB::table($table)
            ->select(DB::raw('*'))
            ->where($where, $value);
        if ($active == 1) {
            $retObj->where('is_active', 1);
        }
        if (!empty($param)) {
            foreach ($param as $k => $v) {
                $retObj->where($k, $v);
            }
        }
        return $retObj->sum($sum_column);
    }

    public function getRowArray($table, $where, $value, $active = 0, $param = [])
    {

        $retObj = DB::table($table)
            ->select(DB::raw('*'))
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
            return json_decode(json_encode($array), 1);
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

    public function updateArray($table, $where, $whvalue, $array)
    {
        DB::table($table)
            ->where($where, $whvalue)
            ->update($array);
    }

    public function getTableList($table, $where, $value, $col = '*')
    {

        $retObj = DB::table($table)
            ->select(DB::raw($col))
            ->where('is_active', 1)
            ->where($where, $value)
            ->get();
        return $retObj;
    }

    public function getTableListInArray($table, $where, $value, $col = '*')
    {

        $retObj = DB::table($table)
            ->select(DB::raw($col))
            ->where('is_active', 1)
            ->whereIn($where, $value)
            ->get();
        return $retObj;
    }

    public function getTableListOrderby($table, $where, $value, $orderby = 'desc', $idcol = 'id', $col = '*')
    {

        $retObj = DB::table($table)
            ->select(DB::raw($col))
            ->where('is_active', 1)
            ->where($where, $value)
            ->orderBy($idcol, $orderby)
            ->get();
        return $retObj;
    }

    public function updateWhereArray($table, $param, $update_array)
    {
        $retObj = DB::table($table);
        foreach ($param as $k => $v) {
            $retObj->where($k, $v);
        }
        $retObj->update($update_array);
    }

    public function getList($table, $param = [], $col = '*', $limit = 0, $orderby = '')
    {
        $retObj = DB::table($table)
            ->select(DB::raw($col));
        if (!empty($param)) {
            foreach ($param as $k => $v) {
                $retObj->where($k, $v);
            }
        }
        if ($limit > 0) {
            $retObj->limit($limit);
        }
        if ($orderby != '') {
            $retObj->orderBy($orderby, 'desc');
        }
        $retObj = $retObj->get();
        if (!empty($retObj)) {
            return $retObj;
        } else {
            return false;
        }
    }

    public function updateTable($table, $where, $whvalue, $col, $val)
    {
        $result = DB::table($table)
            ->where($where, $whvalue)
            ->update([
                $col => $val
            ]);
        return $result;
    }

    public function updateTableData($table, $where, $whvalue, $array)
    {
        DB::table($table)
            ->where($where, $whvalue)
            ->update($array);
    }



    public function isExistData($table, $key, $value)
    {

        $retObj = DB::table($table)
            ->select(DB::raw('*'))
            ->where($key, $value)
            ->where('is_active', 1)
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
        $id = DB::table($table)->insertGetId(
            $array
        );
        return $id;
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

    public function insertTable($table_name, $array, $created_by = '0')
    {
        $array['created_by'] = $created_by;
        $array['last_update_by'] = $created_by;
        $array['created_date'] = date('Y-m-d H:i:s');
        $id = DB::table($table_name)->insertGetId(
            $array
        );
        return $id;
    }
}
