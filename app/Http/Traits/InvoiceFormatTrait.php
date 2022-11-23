<?php

namespace App\Http\Traits;

use App\Libraries\Helpers;

trait InvoiceFormatTrait
{

    public function setMetadata($rows)
    {
        $data = array();
        if (!empty($rows)) {
            foreach ($rows as $row) {
                $data[$row->type][] = $row;
            }
        }
        return $data;
    }

    public function setCreateFunction($rows)
    {
        $values = [];
        foreach ($rows as $key => $row) {
            if ($row->save_table_name == 'request') {
                switch ($row->column_position) {
                    case 4:
                        $rows[$key]->value = date('M-Y') . ' Bill';
                        break;
                    case 5:
                        $rows[$key]->html_id = 'bill_date';
                        break;
                    case 6:
                        $rows[$key]->html_id = 'due_date';
                        break;
                }
            }
            $html_id = "";
            if ($row->function_id > 0) {
                switch ($row->function_id) {
                    case 4:
                        $html_id = 'previous_due';
                        $rows[$key]->script = '$("#previous_due").change(function() {
                            calculategrandtotal(); });';
                        break;
                    case 5:
                        $rows[$key]->value = date('d M Y');
                        break;
                    case 6:
                        $rows[$key]->value = date('01 M Y');
                        break;
                    case 7:
                        $html_id = 'add_days';
                        $rows[$key]->script = '$("#' . $row->param . '").change(function() {
                            var req_date = document.getElementById(' . "'" . $row->param . "'" . ').value;
                                 if(req_date=="")
                                    {
                                    fulldate="";
                                    }else{
                            var d = new Date(req_date);
                            var datenew = new Date(d.getTime() + ' . $row->param_value . ' * 24 * 60 * 60 * 1000);
                            var day = datenew.getDate();
                            var month = datenew.getMonth() + 1;
                            var year = datenew.getFullYear();
                            ' . '
                            var fulldate = ("0" + day).slice(-2) + " " + monthNames[month] + " " + year;
                            }
                            try{
                            document.getElementById("add_days").value = fulldate;
                            } catch(o) {
                                document.getElementById("due_date").value = fulldate;
                            }
                            });';
                        if (isset($values[$row->param])) {
                            $date = strtotime($values[$row->param] . $row->param_value . ' days');
                            $rows[$key]->value = date('d M Y', $date);
                        }
                        break;
                    case 8:
                        $rows[$key]->value = date('t M Y');
                        break;
                    case 9:
                        $html_id = 'invoice_number';
                        if ($row->param == 'system_generated') {
                            $rows[$key]->script = '$("#invoice_number").attr' . "('readonly','true');";
                            $rows[$key]->value = 'System generated' . $row->param_value;
                        }
                        break;
                    case 10:
                        break;
                    case 11:
                        break;
                    case 12:
                        $html_id = 'adjustment';
                        $rows[$key]->script = '$("#adjustment").change(function() {
                            calculategrandtotal(); });';
                        break;
                    case 13:
                        $html_id = 'particulartotal';
                        $rows[$key]->script = '$("#particulartotal").change(function() {
                            calculategrandtotal(); });';
                        break;
                    case 14:
                        $html_id = 'discount';
                        $rows[$key]->script = '$("#discount").change(function() {
                            calculategrandtotal(); });';
                        break;
                }
                if (!isset($rows[$key]->html_id)) {
                    $rows[$key]->html_id = $html_id;
                }
                if (isset($rows[$key]->html_id) && isset($rows[$key]->value)) {
                    $values[$rows[$key]->html_id] = $rows[$key]->value;
                }
            }
        }
        return $rows;
    }
    public function setUpdateFunction($rows, $info)
    {
        $values = [];
        foreach ($rows as $key => $row) {
            if ($row->save_table_name == 'request') {
                switch ($row->column_position) {
                    case 4:
                        $rows[$key]->value = $info->cycle_name;
                        break;
                    case 5:
                        $rows[$key]->value = Helpers::htmlDate($info->bill_date);
                        break;
                    case 6:
                        $rows[$key]->value = Helpers::htmlDate($info->due_date);
                        break;
                }
            }
            $html_id = "";
            if ($row->function_id > 0) {
                switch ($row->function_id) {
                    case 4:
                        $html_id = 'previous_due';
                        $rows[$key]->script = '$("#previous_due").change(function() {
                            calculategrandtotal(); });';
                        break;
                    case 12:
                        $html_id = 'adjustment';
                        $rows[$key]->script = '$("#adjustment").change(function() {
                            calculategrandtotal(); });';
                        break;
                    case 9:
                        $html_id = 'invoice_number';
                        if ($row->param == 'system_generated') {
                            $rows[$key]->script = '$("#invoice_number").attr' . "('readonly','true');";
                        }
                        $rows[$key]->value = $info->invoice_number;
                        break;

                    case 13:
                        $html_id = 'particulartotal';
                        $rows[$key]->script = '$("#particulartotal").change(function() {
                            calculategrandtotal(); });';
                        break;
                    case 14:
                        $html_id = 'discount';
                        $rows[$key]->script = '$("#discount").change(function() {
                            calculategrandtotal(); });';
                        break;
                    case 1:
                        $rows[$key]->value = Helpers::htmlDate($rows[$key]->value);
                        break;
                }
                if (!isset($rows[$key]->html_id)) {
                    $rows[$key]->html_id = $html_id;
                }
                if (isset($rows[$key]->html_id) && isset($rows[$key]->value)) {
                    $values[$rows[$key]->html_id] = $rows[$key]->value;
                }
            }
        }
        return $rows;
    }
}
