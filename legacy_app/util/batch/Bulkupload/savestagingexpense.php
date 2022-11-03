<?php

include_once('bulkupload.php');

class SaveStagingExpense extends BulkUpload
{

    private $customer_codes = array();

    function __construct()
    {
        parent::__construct();
        $this->expensesave();
        $this->expensesavelive();
    }

    function getStagingExpenseList($id)
    {
        $sql = "SELECT * FROM staging_expense where bulk_id=:bulk_id and is_active=1";
        $params = array(':bulk_id' => $id);
        $this->db->exec($sql, $params);
        $rows = $this->db->resultset();
        return $rows;
    }

    function getExpenseSequence($merchant_id)
    {
        $sql = "SELECT * FROM merchant_auto_invoice_number where merchant_id=:merchant_id and type=3";
        $params = array(':merchant_id' => $merchant_id);
        $this->db->exec($sql, $params);
        $row = $this->db->single();
        return $row;
    }
    function expenseNumber($id)
    {
        $sql = "select generate_invoice_number(" . $id . ") as auto_invoice_number";
        $params = array();
        $this->db->exec($sql, $params);
        $row = $this->db->single();
        return $row['auto_invoice_number'];
    }
    function bulkexpensesave($expense_id, $expense_no)
    {
        $sql = "call save_bulk_expense(" . $expense_id . ",'" . $expense_no . "');";
        $params = array();
        $this->db->exec($sql, $params);
    }

    public function getExpenseNumber($expense_no, $merchant_id)
    {
        if ($expense_no == 'Auto generate') {
            $seq_detail = $this->getExpenseSequence($merchant_id, 3);
            return $this->expenseNumber($seq_detail['auto_invoice_id']);
        } else {
            return $expense_no;
        }
    }

    public function expensesavelive()
    {
        $bulkuploadlist = $this->getbulkuploadlist(4, 10);
        foreach ($bulkuploadlist as $bulkupload) {
            $expense_list = $this->getStagingExpenseList($bulkupload['bulk_upload_id']);
            foreach ($expense_list as $exp) {
                $expense_no = $this->getExpenseNumber($exp['expense_no'], $exp['merchant_id']);
                $this->bulkexpensesave($exp['expense_id'], $expense_no);
                $this->common->queryexecute("call `stock_management`('".$exp['merchant_id']."','" . $exp['expense_id'] . "',2,1);");
            }
            $this->updateBulkUploadStatus($bulkupload['bulk_upload_id'], 5, '');
        }
    }

    public function expensesave()
    {
        $bulkuploadlist = $this->getbulkuploadlist(2, 10);
        foreach ($bulkuploadlist as $bulkupload) {
            try {
                $bulk_id = $bulkupload['bulk_upload_id'];
                if (!empty($bulkupload)) {
                    $this->logger->info(__CLASS__, 'Saving Bulk upload staging expense initiate bulk_id is : ' . $bulk_id . ' and total rows are ' . $bulkupload['total_rows']);
                    $file = '../../../../public/uploads/Excel/' . $bulkupload['merchant_id'] . '/staging/' . $bulkupload['system_filename'];
                    if (!file_exists($file)) {
                        $this->updateBulkUploadStatus($bulk_id, 1, 'File does not exist');
                        throw new Exception($file . ' file does not exist');
                    } else {
                        $result = $this->bulk_upload($file, $bulk_id);
                        if ($result['status'] == TRUE) {
                            $this->updateBulkUploadStatus($bulk_id, 3, '');
                        } else {
                            $this->updateBulkUploadStatus($bulk_id, 1, $result['error']);
                        }
                    }
                }
            } catch (Exception $e) {
                Sentry\captureException($e);

                $this->logger->error(__CLASS__, '[E3]Error while uploading staging customer Bulk id: ' . $bulk_id . ' Error: ' . $e->getMessage());
            }
        }
    }

    public function bulk_upload($inputFile, $bulk_id)
    {
        try {
            $inputFileType = PHPExcel_IOFactory::identify($inputFile);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFile);
            $subject = $objPHPExcel->getProperties()->getSubject();
            $link = $this->encrypt->decode($subject);
            $merchant_id = substr($link, 0, 10);
            $worksheet = $objPHPExcel->getSheet(0);
            $highestRow = $worksheet->getHighestRow(); // e.g. 10
            $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
            $nrColumns = ord($highestColumn) - 64;
            $is_falied = 0;
            $this->customer_codes = array();
            $expense_auto_generate = $this->common->getRowValue('expense_auto_generate', 'merchant_setting', 'merchant_id', $merchant_id);
            $mstate = $this->common->getMerchantProfile($merchant_id, 0, 'state');

            $getcolumnvalue = array();
            for ($rowno = 2; $rowno <= $highestRow; ++$rowno) {
                $cell = $worksheet->getCellByColumnAndRow(0, $rowno);
                $val = $cell->getFormattedValue();
                if ((string) $val != '') {
                    for ($col = 0; $col < $highestColumnIndex; ++$col) {
                        $cell = $worksheet->getCellByColumnAndRow($col, $rowno);
                        $val = $cell->getFormattedValue();
                        $getcolumnvalue[$rowno][] = $val;
                    }
                    $post_row = array();
                    $int = 0;

                    if ($expense_auto_generate == 0) {
                        $post_row['expense_no'] = (string) $getcolumnvalue[$rowno][$int];
                        $int++;
                    } else {
                        $post_row['expense_no'] = 'Auto generate';
                    }
                    $post_row['vendor_id'] = (string) $getcolumnvalue[$rowno][$int];
                    $vstate = $this->common->getRowValue('state', 'vendor', 'vendor_id', $post_row['vendor_id']);
                    $int++;
                    $post_row['category_id'] = $this->getMasterId((string) $getcolumnvalue[$rowno][$int], $merchant_id, 'expense_category');
                    $int++;
                    $post_row['department_id'] = $this->getMasterId((string) $getcolumnvalue[$rowno][$int], $merchant_id, 'expense_department');
                    $int++;
                    $post_row['invoice_no'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                    $post_row['bill_date'] = $this->excelDate((string) $getcolumnvalue[$rowno][$int]);
                    $int++;
                    $post_row['due_date'] = $this->excelDate((string) $getcolumnvalue[$rowno][$int]);
                    $int++;
                    $post_row['tds'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                    $post_row['discount'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                    $post_row['adjustment'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                    $payment_status = 0;
                    switch (strtolower((string) $getcolumnvalue[$rowno][$int])) {
                        case 'paid':
                            $payment_status = 1;
                            break;
                        case 'unpaid':
                            $payment_status = 2;
                            break;
                        case 'refunded':
                            $payment_status = 3;
                            break;
                        case 'cancelled':
                            $payment_status = 4;
                            break;
                    }
                    $post_row['payment_status'] = $payment_status;
                    $int++;
                    $payment_mode = 0;
                    switch (strtolower((string) $getcolumnvalue[$rowno][$int])) {
                        case 'online':
                            $payment_mode = 4;
                            break;
                        case 'cash':
                            $payment_mode = 3;
                            break;
                        case 'neft':
                            $payment_mode = 1;
                            break;
                        case 'cheque':
                            $payment_mode = 2;
                            break;
                        default:
                            $payment_mode = 0;
                    }
                    $post_row['payment_mode'] = $payment_mode;
                    $int++;
                    $post_row['narrative'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;

                    $keyint = 0;
                    $run = 1;
                    $sub_total = 0;
                    $total_tax = 0;
                    $tds_amount = 0;
                    $cgst_amt = 0;
                    $igst_amt = 0;
                    while ($run == 1) {
                        $post_row['particulars'][$keyint]['particular'] = (string) $getcolumnvalue[$rowno][$int];
                        $int++;
                        $post_row['particulars'][$keyint]['sac'] = (string) $getcolumnvalue[$rowno][$int];
                        $int++;
                        $unit = $getcolumnvalue[$rowno][$int];
                        $post_row['particulars'][$keyint]['unit'] = $unit;
                        $int++;
                        $rate = $getcolumnvalue[$rowno][$int];
                        $post_row['particulars'][$keyint]['rate'] = $rate;
                        $int++;
                        $sale_price = $getcolumnvalue[$rowno][$int];
                        $post_row['particulars'][$keyint]['sale_price'] = $sale_price;
                        $int++;
                        $total_price = $rate * $unit;
                        $post_row['particulars'][$keyint]['total_price'] = $total_price;
                        $sub_total = $sub_total + $total_price;

                        $gst = (string) $getcolumnvalue[$rowno][$int];
                        $tax = 0;
                        switch ($gst) {
                            // case 'Non Taxable':
                            //     $tax = 1; break;
                            case '0':
                                $tax = 0;
                                break;
                            case '5':
                                $tax = 5;
                                break;
                            case '12':
                                $tax = 12;
                                break;
                            case '18':
                                $tax = 18;
                                break;
                            case '28':
                                $tax = 28;
                                break;
                            default:
                                $tax = 0;
                                break;
                        }
                        $post_row['particulars'][$keyint]['tax'] = $tax;
                        $post_row['particulars'][$keyint]['gst'] = $gst;
                        $tax_amount = 0;
                        if ($gst > 0) {
                            $tax_amount = round($total_price * $gst / 100, 2);
                            $total_tax = $total_tax + $tax_amount;
                            $post_row['particulars'][$keyint]['gst'] = $gst;
                        } else {
                            $post_row['particulars'][$keyint]['gst'] = 0;
                        }
                        $post_row['particulars'][$keyint]['tax_amount'] = $tax_amount;
                        $post_row['particulars'][$keyint]['total_value'] = $tax_amount + $total_price;
                        $int++;
                        if ((string) $getcolumnvalue[$rowno][$int] == '') {
                            $run = 0;
                        }
                        $keyint++;
                    }
                    if ($total_tax > 0) {
                        if ($mstate != $vstate) {
                            $cgst_amt = $total_tax / 2;
                        } else {
                            $igst_amt = $total_tax;
                        }
                    }
                    $post_row['cgst_amount'] = $cgst_amt;
                    $post_row['sgst_amount'] = $cgst_amt;
                    $post_row['igst_amount'] = $igst_amt;
                    $post_row['base_amount'] = $sub_total;
                    if ($post_row['tds'] > 0) {
                        $tds_amount = round($sub_total * $post_row['tds'] / 100, 2);
                    }
                    $post_row['grand_total'] = $sub_total + $total_tax - $post_row['discount'] + $post_row['adjustment'] - $tds_amount;

                    $_POSTarray[] = $post_row;
                }
            }
            $count_int = 0;
            try {
                $errorrow = 2;
                $errors = array();

                if (empty($errors)) {
                    foreach ($_POSTarray as $_POST) {
                        $expense_id = $this->uploadExpense($bulk_id, $merchant_id, $merchant_id);
                        if ($expense_id > 0) {
                            foreach ($_POST['particulars'] as $prow) {
                                $product_name = ucwords(strtolower(trim($prow['particular'])));
                                //check if particular means products is exist in merchant_product table
                                $id = $this->common->getRowValue('product_id', 'merchant_product', 'merchant_id', $merchant_id, 1, " and product_name='" . $product_name . "'");
                                if ($id == false) {
                                    $sql = "INSERT INTO `merchant_product`(`merchant_id`,`product_name`,`type`,`price`,`sac_code`,`purchase_cost`,`gst_percent`,`created_by`,`created_date`,`last_update_by`)"
                                    . "VALUES(:merchant_id,:product_name,:type,:price,:sac_code,:purchase_cost,:gst_percent,:user_id,CURRENT_TIMESTAMP(),:user_id);";
                                    $params = array(':merchant_id' => $merchant_id,':product_name' => $product_name,':type'=>'Goods',':price' => $prow['rate'], ':sac_code' => $prow['sac'],':purchase_cost' => $prow['sale_price'], ':gst_percent' => $prow['gst'], ':user_id' => $merchant_id);
                                    
                                    $this->db->exec($sql, $params);
                                    $id = $this->db->lastInsertId();
                                    $prow['product_id'] = $id;
                                } else {
                                    $prow['product_id'] = $id;
                                }
                                $this->uploadExpenseDetail($expense_id, $prow, $merchant_id);
                            }
                        }
                        $count_int++;
                    }
                }
                if ($is_falied == 0) {
                    $this->logger->info(__CLASS__, 'Bulk staging customer saved sucessfully bulk id : ' . $bulk_id . ' Total customer ' . $count_int);
                    return array('status' => true);
                } else {
                    return array('status' => false, 'error' => json_encode($errors));
                }
            } catch (Exception $e) {
                Sentry\captureException($e);

                $this->logger->error(__CLASS__, '[E6]Error while saving staging customer save excel  bulk id : ' . $bulk_id . ' Error: ' . $e->getMessage());
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__CLASS__, '[E7]Error while uploading excel  bulk id : ' . $bulk_id . ' Error: ' . $e->getMessage());
        }
    }

    function excelDate($datestring)
    {
        try {
            $excel_date = PHPExcel_Style_NumberFormat::toFormattedString($datestring, 'Y-m-d');
            $excel_date = str_replace('/', '-', $excel_date);
        } catch (Exception $e) {
            $excel_date = $datestring;
        }

        try {
            $excel_date = str_replace('-', '/', $excel_date);
            $date = new DateTime($excel_date);
        } catch (Exception $e) {
            $excel_date = str_replace('/', '-', $excel_date);
            try {
                $date = new DateTime($excel_date);
            } catch (Exception $e) {
                $value = $datestring;
            }
        }
        try {
            if (isset($date)) {
                $value = $date->format('Y-m-d');
            }
        } catch (Exception $e) {
            $value = $datestring;
        }
        return $value;
    }

    function uploadExpense($bulk_id, $merchant_id, $user_id)
    {
        try {
            $_POST['discount']=($_POST['discount']>0)? $_POST['discount'] : 0;
            $_POST['tds']=($_POST['tds']>0)? $_POST['tds'] : 0;
            $_POST['adjustment']=($_POST['adjustment']>0)? $_POST['adjustment'] : 0;
            
            $sql = "INSERT INTO `staging_expense`(`merchant_id`,`vendor_id`,`category_id`,`department_id`,`expense_no`,`invoice_no`,"
                . "`bill_date`,`due_date`,`tds`,`discount`,`adjustment`,`payment_status`,`payment_mode`,`base_amount`,`cgst_amount`,"
                . "`sgst_amount`,`igst_amount`,`total_amount`,`narrative`,`bulk_id`,`created_by`,`created_date`,"
                . "`last_update_by`)VALUES(:merchant_id,:vendor_id,:category_id,:department_id,:expense_no,:invoice_no,:bill_date,:due_date,:tds,:discount,:adjustment,:payment_status,:payment_mode,:base_amount,:cgst_amount,:sgst_amount,:igst_amount,:total_amount,:narrative,:bulk_id,:created_by,CURRENT_TIMESTAMP(),:created_by);";
            $params = array(
                ':bulk_id' => $bulk_id, ':user_id' => $user_id, ':merchant_id' => $merchant_id, ':vendor_id' => $_POST['vendor_id'], ':category_id' => $_POST['category_id'], ':department_id' => $_POST['department_id'],
                ':expense_no' => $_POST['expense_no'], ':invoice_no' => $_POST['invoice_no'], ':bill_date' => $_POST['bill_date'], ':due_date' => $_POST['due_date'], ':tds' => $_POST['tds'], ':discount' => $_POST['discount'], ':adjustment' => $_POST['adjustment'], ':payment_status' => $_POST['payment_status'], ':payment_mode' => $_POST['payment_mode'], ':base_amount' => $_POST['base_amount'], ':cgst_amount' => $_POST['cgst_amount'], ':sgst_amount' => $_POST['sgst_amount'], ':igst_amount' => $_POST['igst_amount'], ':total_amount' => $_POST['grand_total'], ':narrative' => $_POST['narrative'], ':created_by' => $user_id
            );
            $this->db->exec($sql, $params);
            $id = $this->db->lastInsertId();
            return $id;
        } catch (Exception $e) {
            Sentry\captureException($e);
            echo $e->getMessage();

            $this->logger->error(__CLASS__, '[E8]Error while save staging customer Error: ' . $e->getMessage());
        }
    }

    function uploadExpenseDetail($expense_id, $row, $user_id)
    {
        try {
            $sql = "INSERT INTO `staging_expense_detail`(`expense_id`,`particular_name`,`product_id`,`sac_code`,`qty`,`rate`,`sale_price`,`amount`,`tax`,"
                . "`gst_percent`,total_value,"
                . "`created_by`,`created_date`,`last_update_by`)"
                . "VALUES(:expense_id,:particular,:product_id,:sac,:unit,:rate,:sale_price,:amount,:tax,:gst_percent,:total_value,:user_id,CURRENT_TIMESTAMP(),:user_id);";
            $params = array(
                ':expense_id' => $expense_id, ':particular' => $row['particular'],':product_id' => $row['product_id'], ':merchant_id' => $user_id,
                ':sac' => $row['sac'], ':unit' => $row['unit'], ':rate' => $row['rate'], ':sale_price' => $row['sale_price'],
                ':amount' => $row['total_price'], ':tax' => $row['tax'], ':gst_percent' => $row['gst'], ':total_value' => $row['total_value'], ':user_id' => $user_id
            );
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);
            echo $e->getMessage();

            $this->logger->error(__CLASS__, '[E8]Error while save staging customer Error: ' . $e->getMessage());
        }
    }

    public function getMasterId($value, $merchant_id, $table)
    {
        $id = $this->common->getRowValue('id', $table, 'merchant_id', $merchant_id, 1, " and name='" . $value . "'");
        if ($id == false) {
            $sql = "INSERT INTO `" . $table . "`(`merchant_id`,`name`,`created_by`,`created_date`,`last_update_by`)"
                . "VALUES(:merchant_id,:name,:merchant_id,CURRENT_TIMESTAMP(),:merchant_id);";
            $params = array(':merchant_id' => $merchant_id, ':name' => $value);
            $this->db->exec($sql, $params);
            $id = $this->db->lastInsertId();
        }
        return $id;
    }
}

new SaveStagingExpense();
