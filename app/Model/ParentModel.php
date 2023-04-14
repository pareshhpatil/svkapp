<?php

namespace App\Model;

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

    public function getColumnValue($table, $where, $value, $column_name, $param = [],$orderby=null)
    {

        $retObj = DB::table($table)
            ->select(DB::raw($column_name . ' as value'))
            ->where($where, $value);
        if (!empty($param)) {
            foreach ($param as $k => $v) {
                $retObj->where($k, $v);
            }
        }
        if($orderby!=null)
        {
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

    public function getTableList($table, $where, $value)
    {

        $retObj = DB::table($table)
            ->select(DB::raw('*'))
            ->where('is_active', 1)
            ->where($where, $value)
            ->get();
        return $retObj;
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
    public function getFromTableName($table)
    {

        $retObj = DB::table($table)
            ->select(DB::raw('*'))
            ->where('is_active', 1)
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

    public function getConfigList($type)
    {

        $retObj = DB::table('config')
            ->select(DB::raw('*'))
            ->where('config_type', $type)
            ->get();
        return $retObj;
    }

    public function getMerchantCurrencyList($currency)
    {

        $retObj = DB::table('currency')
            ->select(DB::raw('*'))
            ->whereIn('code', $currency)
            ->get();
        return $retObj;
    }


    public function getConfigValue($type, $key)
    {
        $retObj = DB::table('config')
            ->select(DB::raw('config_value'))
            ->where('config_type', $type)
            ->where('config_key', $key)
            ->first();
        if (!empty($retObj)) {
            return $retObj->config_value;
        } else {
            return false;
        }
    }

    public function getMerchantData($merchant_id, $key)
    {

        $retObj = DB::table('merchant_config_data')
            ->select(DB::raw('value'))
            ->where('key', $key)
            ->where('is_active', 1)
            ->where('merchant_id', $merchant_id)
            ->first();
        if (!empty($retObj)) {
            return $retObj->value;
        } else {
            return false;
        }
    }


    public function getMerchantValues($merchant_id, $table)
    {
        $retObj = DB::table($table)
            ->select(DB::raw('*'))
            ->where('is_active', 1)
            ->where('merchant_id', $merchant_id)
            ->get();
        if ($retObj->isEmpty()) {
            return array();
        } else {
            return $retObj;
        }
    }

    public function getMerchantParentProducts($merchant_id, $table)
    {
        $retObj = DB::table($table)
            ->select(DB::raw('*'))
            ->where('is_active', 1)
            ->whereIn('type', ['Goods', 'Service'])
            ->where('parent_id', '!=', 0)
            ->where('merchant_id', $merchant_id)
            ->get();
        if ($retObj->isEmpty()) {
            return array();
        } else {
            return $retObj;
        }
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

    public function getSequenceId($type)
    {
        $retObj = DB::select("select generate_sequence('" . $type . "') as id;");
        return $retObj[0]->id;
    }
    public function querylist($query)
    {

        $retObj = DB::select($query);

        return $retObj;
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
