<?php

/**
 * Invoice controller class to handle create and update invoice for patron
 */
class BulkuploadWrapper
{

    protected $common = NULL;
    protected $encrypt = NULL;
    protected $session = NULL;
    public $bill_date = NULL;
    public $paid_date = NULL;

    function __construct($model)
    {
        $this->common = $model;
        $this->encrypt = new Encryption();
    }

    function getInvoiceExcelRows($worksheet, $templateinfo, $info, $template_id, $type, $start_int = 0, $customer_codes = null)
    {
        try {
            $highestRow = $worksheet->getHighestRow(); // e.g. 10
            $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'

            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
            $nrColumns = ord($highestColumn) - 64;
            $template_type = $info['template_type'];
            $particular = json_decode($info['default_particular'], 1);
            $particular_column = json_decode($info['particular_column'], 1);
            $properties_column = json_decode($info['properties'], 1);
            $plugin = json_decode($info['plugin'], 1);
            $tax = json_decode($info['default_tax'], 1);
            if (empty($particular)) {
                $particular = array('Particular');
            }
            $custint = 0;
            if ($info['profile_id'] > 0) {
                $merchant_state = $this->common->getRowValue('state', 'merchant_billing_profile', 'id', $info['profile_id']);
            } else {
                $merchant_state = $this->common->getRowValue('state', 'merchant_billing_profile', 'merchant_id', $info['merchant_id'], 1, " and is_default=1");
            }
            $currency_json = $this->common->getRowValue('currency', 'merchant_setting', 'merchant_id', $info['merchant_id']);
           // $product_taxation_value = $this->common->getRowValue('product_taxation', 'merchant_setting', 'merchant_id', $info['merchant_id']);
            $billing_profile = $this->common->getListValue('merchant_billing_profile', 'merchant_id', $info['merchant_id'], 1);

            $currency = json_decode($currency_json, 1);
            //require_once CONTROLLER . 'InvoiceWrapper.php';
            //$invoiceWrap = new InvoiceWrapper($this->common);
            $default_row = 2;

            $has_gst = 0;
            $travel_cols = array();
            if ($template_type == 'travel') {
                $particular = array();
                $travel_cols_names = array();
                foreach ($properties_column as $pck => $pcv) {
                    $travel_cols_names[$pcv['title']] = $pck;
                }
                $default_row = 3;
                for ($col = 0; $col < $highestColumnIndex; ++$col) {
                    $cell = $worksheet->getCellByColumnAndRow($col, 1);
                    $val = $cell->getValue();
                    if ($val != '') {
                        if ($travel_cols_names[$val] == 'vehicle_section') {
                            $particular[] = 'particular';
                        } else {
                            if (isset($travel_cols[$val])) {
                                $travel_cols[$val] = $travel_cols[$val] + 1;
                            } else {
                                $travel_cols[$val] = 1;
                            }
                        }
                    }
                }
            }

            for ($rowno = $default_row; $rowno <= $highestRow; ++$rowno) {
                $gst_tax_row = [];
                $cell = $worksheet->getCellByColumnAndRow(0, $rowno);
                for ($col = 0; $col < $highestColumnIndex; ++$col) {
                    $cell = $worksheet->getCellByColumnAndRow($col, $rowno);
                    $val = $cell->getFormattedValue();
                    $getcolumnvalue[$rowno][] = $val;
                    $getrowvalue[$rowno][] = $cell->getValue();;
                }
                $post_row = array();
                $post_row['particular_column'] = $particular_column;
                $int = $start_int;
                $total_particular = 0;
                $total_tax = 0;
                $previous_dues = 0;
                $travel_particular = array();
                
                if ($start_int == 0) {
                    $post_row['customer_code'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                } else {

                    if ($customer_codes != null) {
                        $post_row['customer_code'] = $customer_codes[$custint];
                        $custint++;
                    } else {
                        $post_row['customer_code'] = 'NULL';
                    }
                }

                if ($type == 2) {
                    $estimate_title = (string) $getcolumnvalue[$rowno][$int];
                    $post_row['estimate_title'] = $estimate_title;
                    $int++;
                    $auto_generate_invoice = (string) $getcolumnvalue[$rowno][$int];
                    $post_row['auto_generate_invoice'] = (strtolower($auto_generate_invoice) == 'no') ? 0 : 1;
                    $int++;
                } else if ($type == 3 || $type == 4) {
                    if ($type == 4) {
                        $estimate_title = (string) $getcolumnvalue[$rowno][$int];
                        $post_row['estimate_title'] = $estimate_title;
                        $int++;
                        $auto_generate_invoice = (string) $getcolumnvalue[$rowno][$int];
                        $post_row['auto_generate_invoice'] = (strtolower($auto_generate_invoice) == 'no') ? 0 : 1;
                        $int++;
                    }

                    $mode = (string) $getcolumnvalue[$rowno][$int];
                    $post_row['mode'] = strtolower($mode);
                    $int++;
                    $repeat_evenry = (string) $getcolumnvalue[$rowno][$int];
                    $post_row['repeat_every'] = $repeat_evenry;
                    $int++;
                    $end_mode = (string) $getcolumnvalue[$rowno][$int];
                    $post_row['end_mode'] = strtolower($end_mode);
                    $int++;
                    $end_value = (string) $getcolumnvalue[$rowno][$int];
                    $post_row['end_value'] = $end_value;
                    $int++;
                } else {
                    $post_row['auto_generate_invoice'] = 1;
                    $post_row['estimate_title'] = '';
                }
                $post_row['type'] = $type;
                if ($plugin['has_franchise'] == 1) {
                    $franch_id = (string) $getcolumnvalue[$rowno][$int];
                    $post_row['franchise_id'] = ($franch_id > 0) ? $franch_id : '';
                    $int++;
                    $franchise_name_invoice = (string) $getcolumnvalue[$rowno][$int];
                    $post_row['franchise_name_invoice'] = ($franchise_name_invoice == 0) ? 0 : 1;
                    $int++;
                } else {
                    $post_row['franchise_id'] = '0';
                    $post_row['franchise_name_invoice'] = '1';
                }

                

                if ($plugin['has_vendor'] == 1) {
                    $vend_id = (string) $getcolumnvalue[$rowno][$int];
                    $post_row['vendor_id'] = ($vend_id > 0) ? $vend_id : '';
                    $int++;
                    $post_row['commission_type'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                    $post_row['commission_value'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                } else {
                    $post_row['vendor_id'] = '0';
                }

                foreach ($templateinfo['main_header'] as $row) {
                    if ($row['default_column_value'] == 'Custom') {
                        $post_row['mainvalues'][] = (string) $getcolumnvalue[$rowno][$int];
                        $post_row['mainids'][] = $row['column_id'];
                        $int = $int + 1;
                    }
                }
                foreach ($templateinfo['header'] as $row) {
                    $exit = 0;
                    if ($row['function_id'] == 9) {
                        $mapping_details = $this->common->getfunctionMappingDetails($row['column_id'], $row['function_id']);
                        if ($mapping_details['param'] == 'system_generated') {
                            $post_row['values'][] = '';
                            $post_row['ids'][] = $row['column_id'];
                            $post_row['datatypes'][] = $row['datatype'];
                            $post_row['column_name'][] = $row['column_name'];
                            $post_row['col_position'][] = $row['column_position'];
                            $post_row['col_type'][] = 'H';
                            $exit = 1;
                        }
                    }
                    if ($row['function_id'] == 4) {
                        $mapping_details = $this->common->getfunctionMappingDetails($row['column_id'], $row['function_id']);
                        if ($mapping_details['param'] == 'auto_calculate') {
                            $previous_dues = $this->common->getRowValue('balance', 'customer', 'customer_code', $post_row['customer_code'], 1, " and merchant_id='" . $info['merchant_id'] . "'");
                            $post_row['values'][] = $previous_dues;
                            $post_row['ids'][] = $row['column_id'];
                            $post_row['datatypes'][] = $row['datatype'];
                            $post_row['column_name'][] = $row['column_name'];
                            $post_row['col_position'][] = $row['column_position'];
                            $post_row['col_type'][] = 'H';
                            $post_row['carry_forward_due'] = 1;
                            $exit = 1;
                        }
                    }
                    if ($exit == 0) {
                        $date = NULL;
                        if ($row['datatype'] == 'date' && (string) $getcolumnvalue[$rowno][$int] != '') {
                            //$getcolumnvalue[$rowno][$int] = $cell->getValue();
                            //die($getcolumnvalue[$rowno][$int]);
                            try {
                                if (is_numeric($getrowvalue[$rowno][$int])) {
                                    $value = PHPExcel_Style_NumberFormat::toFormattedString($getrowvalue[$rowno][$int], 'Y-m-d');
                                } else {
                                    $value = 'NA';
                                }
                            } catch (Exception $e) {
                                $value = 'NA';
                            }
                        } else {
                            $value = (string) $getcolumnvalue[$rowno][$int];
                        }

                        if ($row['function_id'] == 15) {
                            $post_row['einvoice_type'] = (string) $getcolumnvalue[$rowno][$int];
                        }

                        $post_row['values'][] = $value;
                        $post_row['ids'][] = $row['column_id'];
                        $post_row['datatypes'][] = $row['datatype'];
                        $post_row['column_name'][] = $row['column_name'];
                        $post_row['col_position'][] = $row['column_position'];
                        $post_row['col_type'][] = 'H';
                        $int = $int + 1;
                    }

                    if ($type == 3 || $type == 4) {
                        if ($row['function_id'] == 10) {
                            $int = $int - 1; //for removing billing period column for subscription in case of dynamic billing period is on
                            $post_row['billing_period_start_date'] = (string) $getcolumnvalue[$rowno][$int];
                            $int = $int + 1;
                            $post_row['billing_period_duration'] = (string) $getcolumnvalue[$rowno][$int];
                            $int = $int + 1;
                            $billing_period_type = (string) $getcolumnvalue[$rowno][$int];
                            $post_row['billing_period_type'] = (strtolower($billing_period_type));
                            $int = $int + 1;
                        }
                        if ($row['function_id'] == 4) {
                            $carry_due = (string) $getcolumnvalue[$rowno][$int];
                            $post_row['carry_due'] = (strtolower($carry_due) == 'no') ? 0 : 1;
                            $int = $int + 1;
                        }
                    }
                }

                if (count($currency) > 1) {
                    $post_row['currency'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                }

                if (count($billing_profile) > 1) {
                    $post_row['billing_profile'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                }

                foreach ($templateinfo['BDS'] as $row) {
                    $date = NULL;
                    if ($row['datatype'] == 'date' && (string) $getcolumnvalue[$rowno][$int] != '') {
                        $value = $this->dateformat($getcolumnvalue[$rowno][$int]);
                    } else {
                        $value = (string) $getcolumnvalue[$rowno][$int];
                    }

                    $post_row['values'][] = $value;
                    $post_row['ids'][] = $row['column_id'];
                    $post_row['datatypes'][] = $row['datatype'];
                    $post_row['column_name'][] = $row['column_name'];
                    $post_row['col_position'][] = $row['column_position'];
                    $post_row['col_type'][] = 'BDS';
                    $int = $int + 1;
                }
                $total_particular = 0;


                if (!empty($particular)) {
                    foreach ($particular as $dp) {
                        $rate_exist = 0;
                        $gst_exist = 0;
                        $tax_amount_exist = 0;

                        $gst = 0;
                        $rate = 0;
                        $qty = 1;
                        $discount_perc = 0;
                        $discount = 0;
                        foreach ($particular_column as $key => $pr) {
                            if ($key != 'sr_no') {
                                if ($key == 'rate') {
                                    $rate = $getcolumnvalue[$rowno][$int];
                                    $post_row[$key][] = $rate;
                                    $rate_exist = 1;
                                } elseif ($key == 'qty') {
                                    $qty = $getcolumnvalue[$rowno][$int];
                                    $post_row[$key][] = $qty;
                                } elseif ($key == 'gst') {
                                    $gst = $getcolumnvalue[$rowno][$int];
                                    $post_row[$key][] = $gst;
                                    $gst_exist = 1;
                                    $has_gst = 1;
                                } elseif ($key == 'discount_perc') {
                                    $discount_perc = $getcolumnvalue[$rowno][$int];
                                    $post_row[$key][] = $discount_perc;
                                } elseif ($key == 'discount') {
                                    $discount = $getcolumnvalue[$rowno][$int];
                                    $post_row[$key][] = $discount;
                                } elseif ($key == 'tax_amount') {
                                    if ($gst_exist == 0) {
                                        $post_row[$key][] = $getcolumnvalue[$rowno][$int];
                                    } else {
                                        $tax_amount_exist = 1;
                                        $int--;
                                    }
                                } elseif ($key == 'total_amount') {
                                    if ($rate_exist == 0) {
                                        $total = $getcolumnvalue[$rowno][$int];
                                    } else {
                                        $total = $rate * $qty;
                                        $int--;
                                    }
                                    if ($discount_perc > 0) {
                                        $discount = round($total * ($discount_perc / 100));
                                    }
                                    $total = $total - $discount;
                                    if ($tax_amount_exist == 1) {
                                        $tax_amount = round($total * $gst / 100, 2);
                                        $post_row['tax_amount'][] = $tax_amount;
                                    }
                                    if ($gst > 0) {
                                        if (isset($gst_tax_row[$gst])) {
                                            $gst_tax_row[$gst] = $gst_tax_row[$gst] + $total;
                                        } else {
                                            $gst_tax_row[$gst] = $total;
                                        }
                                    }
                                    $post_row[$key][] = $total;
                                    $total_particular = $total + $total_particular;
                                    $post_row['particular_id'][] = 0;
                                } else {
                                    $post_row[$key][] = (string) $getcolumnvalue[$rowno][$int];
                                }
                                $int++;
                            }
                        }
                    }
                }


                foreach ($travel_cols as $keytitle => $colcolunt) {
                    $key_col = $travel_cols_names[$keytitle];
                    for ($i = 0; $i < $colcolunt; $i++) {
                        $array = array();
                        $gst = 0;
                        foreach ($properties_column[$key_col]['column'] as $colkey => $coltitle) {
                            if ($colkey != 'sr_no') {
                                if ($colkey == 'gst') {
                                    $gst = $getcolumnvalue[$rowno][$int];
                                    $gst_exist = 1;
                                    $has_gst = 1;
                                }
                                if (substr($colkey, -5) == '_date') {
                                    $val = $this->dateformat($getcolumnvalue[$rowno][$int]);
                                    $date = new DateTime($val);
                                    $val = $date->format('Y-m-d H:i');
                                   
                                } else {
                                    $val = (string) $getcolumnvalue[$rowno][$int];
                                }
                                if ($colkey == 'total_amount') {
                                    if ($gst > 0) {
                                        if ($key_col != 'travel_cancel_section') {
                                            if (isset($gst_tax_row[$gst])) {
                                                $gst_tax_row[$gst] = $gst_tax_row[$gst] + $val;
                                            } else {
                                                $gst_tax_row[$gst] = $val;
                                            }
                                        }
                                    }
                                }
                                $array[$colkey] = $val;
                                $int++;
                            }
                        }
                        $post_row['travel_particular'][$key_col][] = $array;
                    }
                }
                if ($info['version'] == 1) {
                    $has_gst = 0;
                    $gst_tax_row = array();
                }
                $total_tax = 0;
                foreach ($tax as $tax_id) {
                    $tax_det = $this->common->getSingleValue('merchant_tax', 'tax_id', $tax_id);
                    if ($has_gst == 0 || !in_array($tax_det['tax_type'], array(1, 2, 3))) {
                        if ($tax_det['tax_type'] != 5) {
                            $post_row['tax_id'][] = $tax_id;
                            $post_row['tax_percent'][] = $tax_det['percentage'];
                            $tax_applicable = $getcolumnvalue[$rowno][$int];
                            $int++;
                            $post_row['tax_applicable'][] = $tax_applicable;
                            $tax_amt = $this->roundAmount($tax_det['percentage'] * $tax_applicable / 100, 2);
                            $total_tax = $total_tax + $tax_amt;
                            $post_row['tax_amt'][] = $tax_amt;
                            $post_row['tax_detail_id'][] = 0;
                        } else {
                            $post_row['tax_id'][] = $tax_id;
                            $post_row['tax_percent'][] = 0;
                            $post_row['tax_applicable'][] = 0;
                            $tax_amt = $tax_det['fix_amount'];
                            $total_tax = $total_tax + $tax_amt;
                            $post_row['tax_amt'][] = $tax_amt;
                            $post_row['tax_detail_id'][] = 0;
                        }
                    }
                }

                if (!empty($gst_tax_row)) {
                    $customer_state = $this->common->getRowValue('state', 'customer', 'customer_code', $post_row['customer_code'], 1, " and merchant_id='" . $info['merchant_id'] . "'");
                    if ($customer_state == '') {
                        $customer_state = $merchant_state;
                    }
                    if ($merchant_state == '') {
                        $merchant_state = $customer_state;
                    }
                    foreach ($gst_tax_row as $gst => $applicable) {
                        if ($merchant_state == $customer_state) {
                            $taxtypes = array(1, 2);
                            foreach ($taxtypes as $taxtype) {
                                $gper = $gst / 2;
                                $tax_id = $this->common->getRowValue('tax_id', 'merchant_tax', 'merchant_id', $info['merchant_id'], 1, " and percentage=" . $gper . " and tax_type=" . $taxtype);
                                $post_row['tax_id'][] = $tax_id;
                                $post_row['tax_percent'][] = $gper;
                                $post_row['tax_applicable'][] = $applicable;
                                $tax_amt = $this->roundAmount($gper * $applicable / 100, 2);
                                $total_tax = $total_tax + $tax_amt;
                                $post_row['tax_amt'][] = $tax_amt;
                                $post_row['tax_detail_id'][] = 0;
                            }
                        } else {
                            $tax_id = $this->common->getRowValue('tax_id', 'merchant_tax', 'merchant_id', $info['merchant_id'], 1, " and percentage=" . $gst . " and tax_type=3");
                            $post_row['tax_id'][] = $tax_id;
                            $post_row['tax_percent'][] = $gst;
                            $post_row['tax_applicable'][] = $applicable;
                            $tax_amt = $this->roundAmount($gst * $applicable / 100, 2);
                            $total_tax = $total_tax + $tax_amt;
                            $post_row['tax_amt'][] = $tax_amt;
                            $post_row['tax_detail_id'][] = 0;
                        }
                    }
                }

                if (!empty($plugin['deductible'])) {
                    foreach ($plugin['deductible'] as $row) {
                        $post_row['deduct_tax'][] = $row['tax_name'];
                        $percent = $getcolumnvalue[$rowno][$int];
                        $int++;
                        $post_row['deduct_percent'][] = $percent;
                        $applicable = $getcolumnvalue[$rowno][$int];
                        $int++;
                        $post_row['deduct_applicable'][] = $applicable;
                        $post_row['deduct_total'][] = $this->roundAmount($percent * $applicable / 100, 2);
                    }
                }
                if ($plugin['has_prepaid'] == 1) {
                    $advance = (string) $getcolumnvalue[$rowno][$int];
                    $post_row['advance'] = ($advance > 0) ? $advance : 0;
                    $int++;
                } else {
                    $post_row['advance'] = '0';
                }

                if ($plugin['has_partial'] == 1) {
                    $is_partial = (string) $getcolumnvalue[$rowno][$int];
                    $post_row['has_partial'] = (strtolower($is_partial) == 'yes') ? 1 : 0;
                    $int++;
                    $partial_min_amount = (string) $getcolumnvalue[$rowno][$int];
                    $post_row['partial_min_amount'] = ($partial_min_amount > 0) ? $partial_min_amount : 0;
                    $int++;
                } else {
                    $post_row['has_partial'] = 0;
                    $post_row['partial_min_amount'] = 0;
                }

                if ($plugin['has_online_payments'] == 1) {
                    $post_row['has_online_payments'] = 1;
                    $enable_payments = (string) $getcolumnvalue[$rowno][$int];
                    $post_row['enable_payments'] = (strtolower($enable_payments) == 'yes') ? 1 : 0;
                    $int++;
                } else {
                    $post_row['has_online_payments'] = 0;
                    $post_row['enable_payments'] = 0;
                }

                $post_row['invoice_narrative'] = (string) $getcolumnvalue[$rowno][$int];
                $int++;
                $post_row['notify_patron'] = (string) $getcolumnvalue[$rowno][$int];
                $int++;

                $post_row['product_taxation_type'] = (string) $getcolumnvalue[$rowno][$int];
                if (strtolower($post_row['product_taxation_type']) == 'inclusive') {
                    $post_row['product_taxation_type'] = 2;
                } else {
                    $post_row['product_taxation_type'] = 1;
                }

                if ($plugin['has_e_invoice'] == 1) {
                    $int++;
                    $has_e_invoice = (string) $getcolumnvalue[$rowno][$int];
                    $post_row['has_e_invoice'] = (strtolower($has_e_invoice) == 'yes') ? 1 : 0;
                    $int++;
                    $notify_e_invoice = (string) $getcolumnvalue[$rowno][$int];
                    $post_row['notify_e_invoice'] = (strtolower($notify_e_invoice) == 'yes') ? 1 : 0;
                }
                $post_row['totalcost'] = $this->roundAmount($total_particular, 2);
                $post_row['totaltax'] = $this->roundAmount($total_tax, 2);
                $amount = $this->roundAmount($post_row['totalcost'] + $post_row['totaltax'], 2);
                $post_row['amount'] = $amount;
                $amount = $this->roundAmount($total_particular + $total_tax + $previous_dues, 2);
                $post_row['grand_total'] = $amount;
                $post_row['template_type'] = $info['template_type'];
                //$post_row['plugin_value'] = $invoiceWrap->setInvoicePluginValues($template_id, 'bulk');
                $post_row['template_id'] = $template_id;
                $_POSTarray[] = $post_row;
            }
            return $_POSTarray;
        } catch (Exception $e) {
            Sentry\captureException($e);
            SwipezLogger::error(__CLASS__, '[E2366]bulk upload sheet getting post array template id ' . $template_id . $e->getMessage());
        }
    }

    function dateformat($date_value)
    {
        try {
            $excel_date = PHPExcel_Style_NumberFormat::toFormattedString($date_value, 'Y-m-d H:i');
            $excel_date = str_replace('/', '-', $excel_date);
        } catch (Exception $e) {
            $excel_date = (string) $date_value;
        }

        try {
            $excel_date = str_replace('-', '/', $excel_date);
            $date = new DateTime($excel_date);
        } catch (Exception $e) {
            $excel_date = str_replace('/', '-', $excel_date);
            try {
                $date = new DateTime($excel_date);
            } catch (Exception $e) {
                $value = (string) $date_value;
            }
        }
        try {
            if (isset($date)) {
                $value = $date->format('Y-m-d H:i');
            }
        } catch (Exception $e) {
            $value = (string) $date_value;
        }

        return $value;
    }

    function roundAmount($amount, $num)
    {
        $text = number_format($amount, $num);
        $amount = str_replace(',', '', $text);
        return $amount;
    }
}
