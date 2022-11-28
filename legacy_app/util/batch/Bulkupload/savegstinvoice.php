<?php

include_once('bulkupload.php');

class SaveGSTInvoice extends BulkUpload
{

    private $errors = array();
    private $validation_array = array();

    function __construct()
    {
        parent::__construct();
        $this->mtrsaving();
    }

    public function mtrsaving()
    {
        $bulkuploadlist = $this->getbulkuploadlist(2, 7);
        foreach ($bulkuploadlist as $bulkupload) {
            try {
                $bulk_id = $bulkupload['bulk_upload_id'];
                if (!empty($bulkupload)) {
                    $this->logger->info(__METHOD__, 'Saving Bulk upload staging GST Invoice initiate bulk_id is : ' . $bulk_id . ' and total rows are ' . $bulkupload['total_rows']);
                    $file = '../../../../public/uploads/Excel/' . $bulkupload['merchant_id'] . '/staging/' . $bulkupload['system_filename'];
                    if (!file_exists($file)) {
                        $this->updateBulkUploadStatus($bulk_id, 1, 'File does not exist');
                        throw new Exception($file . ' file does not exist');
                    } else {
                        $this->updateBulkUploadStatus($bulk_id, 8, '');
                        $this->validation_array = json_decode($bulkupload['validate_value'], true);
                        if ($bulkupload['sub_type'] == 'Amazon') {
                            $result = $this->amazongstinvoice($file, $bulk_id, $bulkupload['merchant_id']);
                        } elseif ($bulkupload['sub_type'] == 'Flipkart') {
                            $result = $this->flipkartgstinvoice($file, $bulk_id, $bulkupload['merchant_id']);
                        } elseif ($bulkupload['sub_type'] == 'Stock') {
                            $result = $this->stocktransfergstinvoice($file, $bulk_id, $bulkupload['merchant_id']);
                        }
                        if ($result['status'] == TRUE) {
                            //$this->updateInvoiceSum($bulk_id);
                            $this->updateBulkUploadStatus($bulk_id, 3, '');
                        } else {
                            $this->updateBulkUploadStatus($bulk_id, 1, $result['error']);
                        }
                    }
                }
            } catch (Exception $e) {
                Sentry\captureException($e);

                $this->logger->error(__METHOD__, '[E3]Error while uploading staging customer Bulk id: ' . $bulk_id . ' Error: ' . $e->getMessage());
            }
        }
    }

    public function updateInvoiceSum($bulk_id)
    {
        try {
            $rows = $this->getInvoiceList($bulk_id);
            foreach ($rows as $row) {
                $det = $this->getInvoiceSum($row['id']);
                if ($det['cnt'] > 1) {
                    $this->updateSum($row['id'], $det['total']);
                }
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__METHOD__, '[E3]Error while uploading staging customer Bulk id: ' . $bulk_id . ' Error: ' . $e->getMessage());
        }
    }

    public function amazongstinvoice($inputFile, $bulk_id, $merchant_id)
    {
        try {
            $row = 1;
            $ignore_status = array('Cancel');
            $csvfile = fopen($inputFile, "r");
            while (!feof($csvfile)) {
                $mem_ = fgetcsv($csvfile);

                if ($row > 1) {
                    if ($mem_[0] != '') {
                        $post_row = array();
                        $post_row['row'] = $row;
                        $int = 0;
                        $post_row['seller_gstin'] = (string) $mem_[0];
                        $post_row['invoice_number'] = (string) $mem_[1];

                        $post_row['invoice_date'] = (string) $mem_[2];
                        if (substr($post_row['invoice_date'], 2, 1) == '-') {
                            $post_row['invoice_date'] = $this->getSqlDate($post_row['invoice_date']);
                        } else {
                            $post_row['invoice_date'] = $this->getSqlDate(substr($post_row['invoice_date'], 0, 10));
                        }

                        $post_row['order_id'] = (string) $mem_[4];
                        $post_row['order_date'] = (string) $mem_[7];
                        if (substr($post_row['order_date'], 2, 1) == '-') {
                            $post_row['order_date'] = $this->getSqlDate($post_row['order_date']);
                        } else {
                            $post_row['order_date'] = $this->getSqlDate(substr($post_row['order_date'], 0, 10));
                        }
                        $post_row['status'] = (string) $mem_[3];
                        $post_row['qty'] = (string) $mem_[9];
                        $post_row['description'] = (string) $mem_[10];
                        $post_row['product_code'] = (string) $mem_[11];
                        $post_row['sac'] = (string) $mem_[12];
                        $post_row['bill_from'] = (string) $mem_[16];
                        $post_row['bill_from'] = preg_replace('/\s+/', ' ', $post_row['bill_from']);
                        $post_row['supply_place'] = (string) $mem_[20];
                        if ($post_row['bill_from'] == '') {
                            $post_row['bill_from'] = $post_row['supply_place'];
                        }
                        $post_row['address'] = (string) $mem_[23] . ' ' . (string) $mem_[26];
                        $post_row['ship_to'] = (string) $mem_[24];
                        $post_row['ship_to'] = preg_replace('/\s+/', ' ', $post_row['ship_to']);
                        $post_row['pos'] = $this->getPos($post_row['ship_to'], $row);
                        $post_row['invoice_amount'] = $this->roundAmount((string) $mem_[27], 2);
                        $post_row['exclusive_tax'] = $this->roundAmount((string) $mem_[28], 2);
                        $post_row['total_tax_amount'] = $this->roundAmount((string) $mem_[29], 2);
                        $post_row['cgst_rate'] = (string) $mem_[30] * 100;
                        $post_row['sgst_rate'] = (string) $mem_[31] * 100;
                        $post_row['utgst_rate'] = (string) $mem_[32] * 100;
                        $post_row['igst_rate'] = (string) $mem_[33] * 100;
                        $post_row['discount'] = $this->roundAmount((string) $mem_[56], 2);
                        $post_row['discount_ex'] = $this->roundAmount((string) $mem_[57], 2);
                        $post_row['item_amount'] = $this->roundAmount((string) $mem_[35], 2);
                        $post_row['item_amount_ex'] = $this->roundAmount((string) $mem_[36], 2);
                        $post_row['item_amount'] = $post_row['item_amount'] - $post_row['discount'];
                        $post_row['discount'] = 0;
                        $post_row['item_exclusive_tax'] = $post_row['item_amount_ex'] - $post_row['discount_ex'];
                        $post_row['cgst_tax'] = (string) $mem_[30] * $post_row['item_exclusive_tax'];
                        $post_row['sgst_tax'] = (string) $mem_[31] * $post_row['item_exclusive_tax'];
                        $post_row['igst_tax'] = (string) $mem_[33] * $post_row['item_exclusive_tax'];

                        if ((isset($mem_[81]) && $mem_[81] != '') || (isset($mem_[80]) && $mem_[80] != '')) {
                            $post_row['invTyp'] = 'B2B';
                            $post_row['ctpy'] = 'R';
                            $post_row['ctin'] = ($mem_[80] != '') ? $mem_[80] : $mem_[81];
                            $post_row['cname'] = $mem_[82];
                            $post_row['utgst_tax'] = $this->roundAmount((string) $mem_[39], 2);
                            $post_row['creditnote_number'] = $mem_[83];
                            $post_row['creditnote_date'] = $mem_[84];
                        } else {
                            $post_row['invTyp'] = 'B2CS';
                            $post_row['ctpy'] = 'U';
                            $post_row['ctin'] = NULL;
                            $post_row['cname'] = NULL;
                            $post_row['utgst_tax'] = $this->roundAmount((string) $mem_[40], 2);
                            $post_row['creditnote_number'] = $mem_[76];
                            $post_row['creditnote_date'] = $mem_[77];
                        }
                        if (strlen($post_row['ctin']) != 15) {
                            $post_row['invTyp'] = 'B2CS';
                            $post_row['ctpy'] = 'U';
                            $post_row['ctin'] = NULL;
                            $post_row['cname'] = NULL;
                        }
                        if ($post_row['creditnote_date'] != '') {
                            if (substr($post_row['creditnote_date'], 2, 1) == '-') {
                                $post_row['creditnote_date'] = $this->getSqlDate($post_row['creditnote_date']);
                            } else {
                                $post_row['creditnote_date'] = $this->getSqlDate(substr($post_row['creditnote_date'], 0, 10));
                            }
                        } else {
                            $post_row['creditnote_date'] = null;
                        }
                        $post_row['payment_mode'] = substr($mem_[75], 0, 20);
                        $post_row['shipping_charges'] = $this->roundAmount((string) $mem_[42], 2) - $this->roundAmount((string) $mem_[59], 2);
                        $post_row['shipping_exclusive'] = $this->roundAmount((string) $mem_[43], 2) - $this->roundAmount((string) $mem_[60], 2);
                        $post_row['shipping_discount'] = 0;
                        $post_row['shipping_exclusive_tax'] = $post_row['shipping_exclusive'];
                        $post_row['shipping_igst_rate'] = 0;
                        $post_row['shipping_cgst_rate'] = 0;
                        if ($post_row['cgst_rate'] > 0) {
                            $cgst = $post_row['shipping_exclusive_tax'] * $post_row['cgst_rate'] / 100;
                            $igst = 0;
                            $post_row['shipping_igst_rate'] = 0;
                            $post_row['shipping_cgst_rate'] = $post_row['cgst_rate'];
                        } else {
                            $cgst = 0;
                            $igst = $post_row['shipping_exclusive_tax'] * $post_row['igst_rate'] / 100;
                            $post_row['shipping_igst_rate'] = $post_row['igst_rate'];
                            $post_row['shipping_cgst_rate'] = 0;
                        }

                        $post_row['shipping_cgst_tax'] = $this->roundAmount($cgst, 2);
                        $post_row['shipping_sgst_tax'] = $this->roundAmount($cgst, 2);
                        $post_row['shipping_igst_tax'] = $this->roundAmount($igst, 2);

                        $post_row['gift_wrap_charges'] = $this->roundAmount((string) $mem_[49], 2) - $this->roundAmount((string) $mem_[62], 2);
                        $post_row['gift_wrap_discount'] = 0;
                        $post_row['gift_wrap_exclusive_tax'] = $this->roundAmount((string) $mem_[50], 2) - $this->roundAmount((string) $mem_[63], 2);
                        if ($post_row['cgst_rate'] > 0) {
                            $cgst = $post_row['gift_wrap_exclusive_tax'] * $post_row['cgst_rate'] / 100;
                            $igst = 0;
                            $post_row['gift_igst_rate'] = 0;
                            $post_row['gift_cgst_rate'] = $post_row['cgst_rate'];
                        } else {
                            $cgst = 0;
                            $igst = $post_row['gift_wrap_exclusive_tax'] * $post_row['igst_rate'] / 100;
                            $post_row['gift_cgst_rate'] = 0;
                            $post_row['gift_igst_rate'] = $post_row['igst_rate'];
                        }
                        $post_row['gift_wrap_cgst_tax'] = $this->roundAmount($cgst, 2);
                        $post_row['gift_wrap_sgst_tax'] = $this->roundAmount($cgst, 2);
                        $post_row['gift_wrap_igst_tax'] = $this->roundAmount($igst, 2);
                        if (!in_array($post_row['status'], $ignore_status)) {
                            if ($post_row['invoice_number'] != '') {
                                $_POSTarray[] = $post_row;
                            }
                        }
                    }
                }
                $row++;
            }
            fclose($csvfile);
            $count_int = 0;
            try {
                $errorrow = 2;
                $errors = array();
                if (empty($this->errors)) {
                    foreach ($_POSTarray as $data) {
                        $data['source'] = 'Amazon';
                        $result = $this->createGSTInvoice($bulk_id, $merchant_id, $data, 0, $merchant_id, $data['row']);
                        $count_int++;
                    }
                    $this->updateInvoiceSum($bulk_id);
                    $this->logger->info(__METHOD__, 'Bulk staging GST Invoice saved sucessfully bulk id : ' . $bulk_id . ' Total GST Invoice ' . $count_int);
                    return array('status' => true);
                } else {
                    return array('status' => false, 'error' => json_encode($this->errors));
                }
            } catch (Exception $e) {
                Sentry\captureException($e);

                $this->logger->error(__METHOD__, '[E6]Error while saving staging GST Invoice save excel  bulk id : ' . $bulk_id . ' Error: ' . $e->getMessage());
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__METHOD__, '[E7]Error while uploading excel  bulk id : ' . $bulk_id . ' Error: ' . $e->getMessage());
        }
    }

    public function stocktransfergstinvoice($inputFile, $bulk_id, $merchant_id)
    {
        try {
            $row = 1;
            $ignore_status = array('Cancel');
            $csvfile = fopen($inputFile, "r");
            while (!feof($csvfile)) {
                $mem_ = fgetcsv($csvfile);
                if ($row > 1) {
                    if ($mem_[0] != '') {
                        $post_row = array();
                        $post_row['row'] = $row;
                        $int = 0;
                        $post_row['seller_gstin'] = (string) $mem_[32];
                        $post_row['invoice_number'] = (string) $mem_[14];
                        $post_row['invoice_date'] = (string) $mem_[15];
                        if (substr($post_row['invoice_date'], 2, 1) == '-') {
                            $post_row['invoice_date'] = $this->getSqlDate($post_row['invoice_date']);
                        } else {
                            $post_row['invoice_date'] = $this->getSqlDate(substr($post_row['invoice_date'], 0, 10));
                        }
                        $post_row['order_id'] = (string) $mem_[3];
                        $post_row['order_date'] = $post_row['invoice_date'];

                        $post_row['status'] = (string) $mem_[1];
                        $post_row['qty'] = (string) $mem_[19];
                        $post_row['description'] = (string) $mem_[17];
                        $post_row['product_code'] = (string) $mem_[17];
                        $post_row['sac'] = (string) $mem_[20];
                        $post_row['bill_from'] = (string) $mem_[6];
                        $post_row['bill_from'] = preg_replace('/\s+/', ' ', $post_row['bill_from']);
                        $post_row['supply_place'] = (string) $mem_[6];
                        $post_row['address'] = $mem_[10] . ' ' . (string) $mem_[13];
                        $post_row['ship_to'] = (string) $mem_[11];
                        $post_row['ship_to'] = preg_replace('/\s+/', ' ', $post_row['ship_to']);
                        $post_row['pos'] = $this->getPos($post_row['ship_to'], $row);
                        $post_row['invoice_amount'] = $this->roundAmount((string) $mem_[16], 2);
                        $post_row['exclusive_tax'] = $this->roundAmount((string) $mem_[21], 2);
                        $post_row['cgst_rate'] = (string) $mem_[24] * 100;
                        $post_row['sgst_rate'] = (string) $mem_[24] * 100;
                        $post_row['utgst_rate'] = (string) $mem_[26] * 100;
                        $post_row['igst_rate'] = (string) $mem_[22] * 100;
                        $post_row['item_amount'] = $this->roundAmount((string) $mem_[16], 2);
                        $post_row['item_amount_ex'] = $this->roundAmount((string) $mem_[21], 2);
                        $post_row['discount'] = 0;
                        $post_row['discount_ex'] = 0;
                        $post_row['item_exclusive_tax'] = $post_row['item_amount_ex'];
                        $post_row['cgst_tax'] = $this->roundAmount((string) $mem_[25], 2);
                        $post_row['sgst_tax'] = $post_row['cgst_tax'];
                        $post_row['igst_tax'] = $this->roundAmount((string) $mem_[23], 2);
                        $post_row['total_tax_amount'] = $this->roundAmount($post_row['cgst_tax'] + $post_row['cgst_tax'] + $post_row['igst_tax'], 2);
                        $post_row['invTyp'] = 'B2B';
                        $post_row['ctpy'] = 'R';
                        $post_row['ctin'] = $mem_[0];
                        $post_row['cname'] = '';
                        $post_row['utgst_tax'] = $this->roundAmount((string) $mem_[39], 2);
                        $post_row['creditnote_number'] = null;
                        $post_row['creditnote_date'] = null;
                        $post_row['payment_mode'] = '';
                        $post_row['shipping_charges'] = 0;
                        $post_row['gift_wrap_charges'] = 0;
                        $post_row['gift_wrap_exclusive_tax'] = 0;
                        if (!in_array($post_row['status'], $ignore_status)) {
                            if ($post_row['invoice_number'] != '') {
                                $_POSTarray[] = $post_row;
                            }
                        }
                    }
                }
                $row++;
            }
            fclose($csvfile);
            $count_int = 0;
            try {
                $errorrow = 2;
                $errors = array();
                if (empty($this->errors)) {
                    foreach ($_POSTarray as $data) {
                        $data['source'] = 'Amazon';
                        $result = $this->createGSTInvoice($bulk_id, $merchant_id, $data, 0, $merchant_id, $data['row']);
                        $count_int++;
                    }
                    $this->updateInvoiceSum($bulk_id);
                    $this->saveStockExpense($bulk_id, $merchant_id);
                    $this->logger->info(__METHOD__, 'Bulk staging GST Invoice saved sucessfully bulk id : ' . $bulk_id . ' Total GST Invoice ' . $count_int);
                    return array('status' => true);
                } else {
                    return array('status' => false, 'error' => json_encode($this->errors));
                }
            } catch (Exception $e) {
                Sentry\captureException($e);

                $this->logger->error(__METHOD__, '[E6]Error while saving staging GST Invoice save excel  bulk id : ' . $bulk_id . ' Error: ' . $e->getMessage());
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__METHOD__, '[E7]Error while uploading excel  bulk id : ' . $bulk_id . ' Error: ' . $e->getMessage());
        }
    }

    public function flipkartgstinvoice($inputFile, $bulk_id, $merchant_id)
    {
        try {
            $row_no = 1;
            $inputFileType = PHPExcel_IOFactory::identify($inputFile);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFile);
            $worksheet = $objPHPExcel->getSheetByName('Sales Report');
            $highestRow = $worksheet->getHighestRow(); // e.g. 10
            $highestColumnIndex = 55; //PHPExcel_Cell::columnIndexFromString($highestColumn);
            for ($rowno = 2; $rowno <= $highestRow; ++$rowno) {
                for ($col = 0; $col < $highestColumnIndex; ++$col) {
                    $cell = $worksheet->getCellByColumnAndRow($col, $rowno);
                    $val = $cell->getValue();
                    $getcolumnvalue[$rowno][] = $val;
                }
            }

            $notegetcolumnvalue = array();
            $worksheet = $objPHPExcel->getSheetByName('Cash Back Report');
            if ($worksheet != null) {
                $highestRow = $worksheet->getHighestRow(); // e.g. 10
                $highestColumnIndex = 27; //PHPExcel_Cell::columnIndexFromString($highestColumn);
                for ($rowno = 2; $rowno <= $highestRow; ++$rowno) {
                    for ($col = 0; $col < $highestColumnIndex; ++$col) {
                        $cell = $worksheet->getCellByColumnAndRow($col, $rowno);
                        $val = $cell->getFormattedValue();
                        $notegetcolumnvalue[$rowno][] = $val;
                    }
                }
            }


            $sales = array();
            $returncancel = array();

            foreach ($getcolumnvalue as $row) {
                if ($row[50] == '') {
                    $inc = 1;
                } else {
                    $inc = 0;
                }
                if ($row[0] != '') {
                    $post_row = array();

                    $post_row['seller_gstin'] = (string) $row[0];
                    $post_row['order_id'] = (string) $row[1] . '/' . (string) $row[2];
                    $post_row['product_code'] = str_replace('"', '', (string) $row[4]);
                    $post_row['description'] = str_replace('"', '', (string) $row[3]);
                    $post_row['sac'] = (string) $row[6];
                    $post_row['payment_mode'] = substr($row[9], 0, 20);

                    $post_row['qty'] = (string) $row[13 - $inc];

                    $post_row['invoice_number'] = (string) $row[43 - $inc];
                    $post_row['order_date'] = $this->getexceldate($row[11 - $inc]);
                    $post_row['invoice_date'] = $this->getexceldate($row[44 - $inc]);

                    $post_row['bill_from'] = substr($row[0], 0, 2);
                    $post_row['address'] = (string) $row[48 - $inc];
                    $post_row['ship_to'] = (string) $row[49 - $inc];
                    $post_row['pos'] = $this->getPos($post_row['ship_to'], $row_no);
                    $post_row['invoice_amount'] = $this->roundAmount((string) $row[45 - $inc], 2);
                    $post_row['item_invoice_amount'] = $this->roundAmount((string) $row[21 - $inc], 2);
                    $post_row['exclusive_tax'] = $this->roundAmount((string) $row[23 - $inc], 2);
                    $post_row['cgst_rate'] = (string) $row[32 - $inc];
                    $post_row['sgst_rate'] = (string) $row[32 - $inc];
                    $post_row['utgst_rate'] = 0;
                    $post_row['igst_rate'] = (string) $row[30 - $inc];
                    $post_row['utgst_tax'] = 0;
                    $post_row['total_tax_amount'] = $this->roundAmount((string) $row[33 - $inc], 2) * 2 + $this->roundAmount((string) $row[31 - $inc], 2);
                    $post_row['shipping_charges'] = $this->roundAmount((string) $row[20 - $inc], 2);
                    $post_row['item_invoice_amount'] = $post_row['exclusive_tax'] + $post_row['total_tax_amount'];
                    $post_row['item_amount'] = $post_row['item_invoice_amount'] - $post_row['shipping_charges'];

                    if ($post_row['igst_rate'] > 0) {
                        $gst_rate = $post_row['igst_rate'];
                    } else {
                        $gst_rate = $post_row['cgst_rate'] * 2;
                    }
                    $reversegst = 100 + $gst_rate;

                    $post_row['item_exclusive_tax'] = $this->roundAmount($post_row['item_amount'] * 100 / $reversegst, 2);
                    //$discount = $this->roundAmount($row[16 - $inc],2) + $bank_share;
                    $post_row['discount'] = 0;


                    $itemcgstamt = $post_row['item_exclusive_tax'] * $post_row['cgst_rate'] / 100;
                    $itemigstamt = $post_row['item_exclusive_tax'] * $post_row['igst_rate'] / 100;

                    $post_row['cgst_tax'] = $this->roundAmount($itemcgstamt, 2);
                    $post_row['sgst_tax'] = $this->roundAmount($itemcgstamt, 2);
                    $post_row['igst_tax'] = $this->roundAmount($itemigstamt, 2);

                    $post_row['invTyp'] = 'B2CS';
                    $post_row['ctpy'] = 'U';
                    $post_row['ctin'] = NULL;
                    $post_row['cname'] = NULL;
                    $post_row['utgst_tax'] = 0;

                    if ($post_row['igst_tax'] > 0) {
                        $post_row['supply_place'] = 'NA';
                    } else {
                        $post_row['supply_place'] = $post_row['ship_to'];
                    }


                    $post_row['shipping_discount'] = 0;
                    $post_row['shipping_exclusive_tax'] = $this->roundAmount($post_row['shipping_charges'] * 100 / $reversegst, 2);
                    if ($post_row['cgst_rate'] > 0) {
                        $cgst = $this->roundAmount($post_row['shipping_exclusive_tax'] * $post_row['cgst_rate'] / 100, 2);
                        $igst = 0;
                    } else {
                        $cgst = 0;
                        $igst = $this->roundAmount($post_row['shipping_exclusive_tax'] * $post_row['igst_rate'] / 100, 2);
                    }

                    $post_row['shipping_igst_rate'] = $post_row['igst_rate'];
                    $post_row['shipping_cgst_rate'] = $post_row['cgst_rate'];

                    $post_row['shipping_cgst_tax'] = $this->roundAmount($cgst, 2);
                    $post_row['shipping_sgst_tax'] = $this->roundAmount($cgst, 2);
                    $post_row['shipping_igst_tax'] = $this->roundAmount($igst, 2);

                    $post_row['creditnote_number'] = null;
                    $post_row['creditnote_date'] = null;
                    $post_row['row'] = $row_no;
                    $oprocode = $post_row['order_id'];

                    switch ($row[8]) {
                        case 'Cancellation':
                            $aint = 2;
                            while (isset($return[$post_row['order_id']])) {
                                $post_row['order_id'] = $oprocode . '_' . $aint;
                                $aint++;
                            }
                            $return[$post_row['order_id']] = $post_row;
                            break;
                        case 'Return':
                            $aint = 2;
                            while (isset($return[$post_row['order_id']])) {
                                $post_row['order_id'] = $oprocode . '_' . $aint;
                                $aint++;
                            }
                            $return[$post_row['order_id']] = $post_row;
                            break;
                        case 'Return Cancellation':
                            $aint = 2;
                            while (isset($sales[$post_row['order_id']])) {
                                $post_row['order_id'] = $oprocode . '_' . $aint;
                                $aint++;
                            }
                            $sales[$post_row['order_id']] = $post_row;
                            break;
                        case 'Sale':
                            $aint = 2;
                            while (isset($sales[$post_row['order_id']])) {
                                $post_row['order_id'] = $oprocode . '_' . $aint;
                                $aint++;
                            }
                            $sales[$post_row['order_id']] = $post_row;
                            break;
                        default:
                            $sales[$post_row['order_id']] = $post_row;
                            break;
                    }
                }
                $row_no++;
            }
            foreach ($sales as $row) {
                $_POSTarray[] = $row;
            }


            foreach ($return as $row) {
                $sale_row = array();
                $oprocode = $row['order_id'];
                $aint = 1;

                if (substr($row['order_id'], -3, 1) == '_') {
                    $aint = substr($row['order_id'], -2);
                } else {
                    if (substr($row['order_id'], -2, 1) == '_') {
                        $aint = substr($row['order_id'], -1);
                    } else {
                        if (isset($sales[$row['order_id']])) {
                            $sale_row = $sales[$row['order_id']];
                        } else {
                            $sale_row = $this->getProductcodeInvoice($merchant_id, $post_row['seller_gstin'], $row['order_id']);
                        }
                    }
                }

                while ($aint > 1 && empty($sale_row)) {
                    if ($aint == 2) {
                        $replace = '';
                    } else {
                        $a = $aint - 1;
                        $replace = '_' . $a;
                    }
                    $row['order_id'] = str_replace('_' . $aint, $replace, $oprocode);
                    if (isset($sales[$row['order_id']])) {
                        $sale_row = $sales[$row['order_id']];
                    } else {
                        $sale_row = $this->getProductcodeInvoice($merchant_id, $post_row['seller_gstin'], $row['order_id']);
                    }
                    $aint--;
                }


                if (!empty($sale_row)) {
                    $post_row = $row;
                    $post_row['creditnote_number'] = $row['invoice_number'];
                    $post_row['creditnote_date'] = $row['invoice_date'];
                    $post_row['invoice_number'] = $sale_row['invoice_number'];
                    if (strtotime($row['invoice_date']) < strtotime($sale_row['invoice_date'])) {
                        $post_row['invoice_date'] = $row['invoice_date'];
                    } else {
                        $post_row['invoice_date'] = $sale_row['invoice_date'];
                    }
                    $_POSTarray[] = $post_row;
                } else {
                    $post_row = $row;
                    $post_row['creditnote_number'] = $row['invoice_number'];
                    $post_row['creditnote_date'] = $row['invoice_date'];
                    $post_row['invoice_number'] = null;
                    $post_row['invoice_date'] = $row['invoice_date'];
                    $_POSTarray[] = $post_row;
                    //$this->errors[] = array('State' => array('Invoice number', 'Credit note does not have invoice number. Note no: ' . $row['invoice_number']), 'row' => $row['row']);
                }
            }
            //$notegetcolumnvalue = array();
            $notelist = array();
            foreach ($notegetcolumnvalue as $key => $row) {
                $post_row = array();
                $order_id = (string) $row[1] . '/' . (string) $row[2];
                $oorder_id = $order_id;

                $aint = 2;
                while ($notelist[$order_id]) {
                    $order_id = (string) $row[1] . '/' . (string) $row[2] . '_' . $aint;
                    $aint++;
                }

                $notelist[$order_id] = '1';
                if (isset($sales[$oorder_id])) {
                    $post_row = $sales[$oorder_id];
                } else {
                    $sale_row = $this->getProductcodeInvoice($merchant_id, (string) $row[0], $oorder_id);
                    if (!empty($sale_row)) {
                        $post_row['seller_gstin'] = (string) $row[0];
                        $post_row['order_id'] = (string) $row[1] . '/' . (string) $row[2];
                        $post_row['product_code'] = str_replace('"', '', (string) $row[4]);
                        $post_row['description'] = $sale_row['desc'];
                        $post_row['sac'] = $sale_row['hsnSc'];
                        $post_row['invoice_number'] = $sale_row['invoice_number'];
                        $post_row['invoice_date'] = $sale_row['invoice_date'];
                        if ($sale_row['splyTy'] == 'INTER') {
                            $post_row['bill_from'] = 'NA';
                            $post_row['supply_place'] = 'NA';
                        } else {
                            $post_row['bill_from'] = $sale_row['state'];
                            $post_row['supply_place'] = $sale_row['state'];
                        }
                        $post_row['ship_to'] = $sale_row['state'];
                        $post_row['invTyp'] = $sale_row['invTyp'];
                        $post_row['ctin'] = $sale_row['ctin'];
                        $post_row['cname'] = $sale_row['cname'];
                        $post_row['ctpy'] = $sale_row['ctpy'];
                        $post_row['pos'] = $sale_row['pos'];
                    }
                }
                if (!empty($post_row)) {
                    if ($row[3] == 'Credit Note') {
                        $post_row['note_type'] = 'D';
                    }

                    $post_row['qty'] = 1;
                    $post_row['creditnote_number'] = $row[5];
                    $post_row['creditnote_date'] = $this->getexceldate($row[7]);
                    $post_row['invoice_amount'] = $this->roundAmount((string) $row[6], 2);
                    $post_row['exclusive_tax'] = $this->roundAmount((string) $row[8], 2);

                    $post_row['cgst_rate'] = (string) $row[13];
                    $post_row['sgst_rate'] = (string) $row[13];
                    $post_row['utgst_rate'] = 0;
                    $post_row['igst_rate'] = (string) $row[11];
                    $post_row['cgst_tax'] = $this->roundAmount((string) $row[14], 2);
                    $post_row['sgst_tax'] = $this->roundAmount((string) $row[14], 2);
                    $post_row['igst_tax'] = $this->roundAmount((string) $row[12], 2);

                    $post_row['item_amount'] = $post_row['invoice_amount'];
                    $post_row['item_exclusive_tax'] = $post_row['exclusive_tax'];
                    $post_row['discount'] = 0;

                    $post_row['shipping_charges'] = 0;
                    $post_row['shipping_discount'] = 0;
                    $post_row['shipping_exclusive_tax'] = 0;

                    $post_row['shipping_igst_rate'] = 0;
                    $post_row['shipping_cgst_rate'] = 0;

                    $post_row['shipping_cgst_tax'] = 0;
                    $post_row['shipping_sgst_tax'] = 0;
                    $post_row['shipping_igst_tax'] = 0;
                    $_POSTarray[] = $post_row;
                } else {
                    $this->errors[] = array('State' => array('Invoice number', 'Note does not have invoice details. Note no: ' . $row[5]), 'row' => $key + 1);
                }
            }
            $count_int = 0;
            try {
                $errorrow = 2;
                $errors = array();
                if (empty($this->errors)) {
                    foreach ($_POSTarray as $data) {
                        $data['source'] = 'Flipkart';
                        $result = $this->createGSTInvoice($bulk_id, $merchant_id, $data, 0, $merchant_id, $data['row']);
                        $count_int++;
                    }
                    $this->logger->info(__METHOD__, 'Bulk staging GST Invoice saved sucessfully bulk id : ' . $bulk_id . ' Total GST Invoice ' . $count_int);
                    return array('status' => true);
                } else {
                    return array('status' => false, 'error' => json_encode($this->errors));
                }
            } catch (Exception $e) {
                Sentry\captureException($e);

                $this->logger->error(__METHOD__, '[E6]Error while saving staging GST Invoice save excel  bulk id : ' . $bulk_id . ' Error: ' . $e->getMessage());
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__METHOD__, '[E7]Error while uploading excel  bulk id : ' . $bulk_id . ' Error: ' . $e->getMessage());
        }
    }

    function roundAmount($amount, $num)
    {
        $amount = str_replace('-', '', $amount);
        if ($amount > 0) {
            $text = number_format($amount, $num);
            $amount = str_replace(',', '', $text);
        } else {
            $amount = 0;
        }
        return $amount;
    }

    function getGSTStatecode($state)
    {
        $state = strtoupper($state);
        $gst_code['AD'] = '37';
        $gst_code['AR'] = '12';
        $gst_code['AP'] = '37';
        $gst_code['AS'] = '18';
        $gst_code['BR'] = '10';
        $gst_code['CG'] = '22';
        $gst_code['DL'] = '07';
        $gst_code['GA'] = '30';
        $gst_code['GJ'] = '24';
        $gst_code['HR'] = '06';
        $gst_code['HP'] = '02';
        $gst_code['JK'] = '01';
        $gst_code['JH'] = '20';
        $gst_code['KA'] = '29';
        $gst_code['KL'] = '32';
        $gst_code['LD'] = '31';
        $gst_code['MP'] = '23';
        $gst_code['MH'] = '27';
        $gst_code['MN'] = '14';
        $gst_code['ML'] = '17';
        $gst_code['MZ'] = '15';
        $gst_code['NL'] = '13';
        $gst_code['OD'] = '21';
        $gst_code['PY'] = '34';
        $gst_code['PB'] = '03';
        $gst_code['RJ'] = '08';
        $gst_code['SK'] = '11';
        $gst_code['TN'] = '33';
        $gst_code['TS'] = '36';
        $gst_code['TR'] = '16';
        $gst_code['UP'] = '09';
        $gst_code['UK'] = '05';
        $gst_code['WB'] = '19';
        $gst_code['AN'] = '35';
        $gst_code['CH'] = '04';
        $gst_code['DNHDD'] = '26';
        $gst_code['LA'] = '38';
        $gst_code['OT'] = '97';
        if (isset($gst_code[$state])) {
            return $gst_code[$state];
        } else {
            return 0;
        }
    }

    function getPos($state, $row)
    {
        $state = str_replace('  ', '', $state);
        $state = trim($state);

        if (isset($this->validation_array[$state])) {
            return $this->validation_array[$state];
        }


        $code = $this->getGSTStatecode($state);
        if ($code != 0) {
            return $code;
        }

        if ($state == 'JAMMU KASHMIR' || $state == 'Jammu Kashmir' || $state == 'Jammu & Kashmir') {
            $state = 'Jammu and Kashmir';
        }
        if ($state == 'KL') {
            $state = 'Kerala';
        }
        if ($state == 'CHHATTISGARH') {
            $state = 'Chattisgarh';
        }
        if ($state == 'Chhattisgarh') {
            $state = 'Chattisgarh';
        }
        if ($state == 'AR') {
            $state = 'Arunachal Pradesh';
        }
        if ($state == 'UP') {
            $state = 'Uttar Pradesh';
        }
        if ($state == 'MP') {
            $state = 'Madhya Pradesh';
        }
        if ($state == 'tamilnadu' || $state == 'Tamilnadu') {
            $state = 'TAMIL NADU';
        }
        if ($state == 'Daman & Diu' || $state == 'DAMAN DIU') {
            $state = 'Daman and Diu';
        }
        if ($state == 'ANDAMAN NICOBAR ISLANDS') {
            $state = 'Andaman and Nicobar Islands';
        }
        if ($state == 'Puducherry' || $state == 'PUDUCHERRY') {
            $state = 'Pondicherry';
        }
        if ($state == 'Dadra & Nagar Haveli' || $state == 'DADRA NAGAR HAVELI' || $state == 'Daman and Diu') {
            $state = 'Dadra and Nagar Haveli';
        }

        $pos = $this->getStatecode($state);
        if ($pos == 0) {
            $this->errors[] = array('State' => array('State', 'State does not exist'), 'row' => $row);
        } else {
            return $pos;
        }
    }

    public function createGSTInvoice($bulk_id, $merchant_id, $data, $status, $user_id, $row)
    {
        try {
            if ($data['seller_gstin'] != $data['ctin']) {
                $dst = 'O';
                $rchrg = 'N';
                $pgst = 'N';
                $prs = 'N';
                $odnum = '000';
                $refnum = time() . rand(1, 96669);
                $pos = $data['pos'];
                if (strlen($pos) == 1) {
                    $pos = '0' . $pos;
                }
                if ($pos == '25') {
                    $pos = '26';
                }
                if ($pos == '28') {
                    $pos = '37';
                }
                $ship_tostate = strtolower($data['ship_to']);
                $supply_tostate = strtolower($data['supply_place']);
                if ($ship_tostate != $supply_tostate) {
                    $splyTy = 'INTER';
                } else {
                    $splyTy = 'INTRA';
                }

                if ($data['creditnote_number'] != '') {
                    if ($data['note_type'] == 'D') {
                        $dty = 'D';
                    } else {
                        $dty = 'C';
                    }
                    $date = new DateTime($data['creditnote_date']);
                } else {
                    $dty = 'RI';
                    $date = new DateTime($data['invoice_date']);
                }
                $detail_id = 0;
                $fp = $date->format('mY');
                $invoice_id = $this->checkInvoiceNumber($data['invoice_number'], $dty, $merchant_id, $data['invoice_date'], $data['creditnote_number'], $bulk_id, $data['seller_gstin']);
                $ft = 'GSTR1';
                if ($invoice_id > 0) {
                    //$this->updateBulkID($invoice_id, $bulk_id);
                    $detail_id = $this->checkInvoiceDetailNumber($invoice_id, $data['product_code']);
                } else {

                    $sql = "INSERT INTO `iris_invoice`(`merchant_id`,`invTyp`,`splyTy`,`dst`,`refnum`,`pdt`,`ctpy`,`ctin`,`cname`,"
                        . "`inum`,`idt`,`ntNum`,`ntDt`,`val`,`pos`,`rchrg`,`fy`,`dty`,`rsn`,`pgst`,`prs`,`odnum`,`gen2`,`gen7`,`gen8`,`gen10`,`gen11`,"
                        . "`gen12`,`gen13`,`gstin`,`fp`,`ft`,`bulk_id`,`order_id`,`order_date`,`address`,`source`,`created_by`,`created_date`,`last_update_by`)"
                        . "VALUES(:merchant_id,:invTyp,:splyTy,:dst,:refnum,:pdt,:ctpy,:ctin,:cname,"
                        . ":inum,:idt,:ntNum,:ntDt,:val,:pos,:rchrg,:fy,:dty,:rsn,:pgst,:prs,:odnum,"
                        . ":gen2,:gen7,:gen8,:gen10,:gen11,:gen12,:gen13,:gstin,:fp,:ft,:bulk_id,:order_id,:order_date,:address,:source,:merchant_id,CURRENT_TIMESTAMP(),:merchant_id);";

                    $params = array(
                        ':merchant_id' => $merchant_id, ':invTyp' => $data['invTyp'], ':splyTy' => $splyTy, ':dst' => $dst, ':refnum' => $refnum, ':pdt' => $data['invoice_date'], ':ctpy' => $data['ctpy'], ':ctin' => $data['ctin'], ':cname' => $data['cname'], ':inum' => $data['invoice_number'],
                        ':idt' => $data['invoice_date'], ':ntNum' => $data['creditnote_number'], ':ntDt' => $data['creditnote_date'], ':val' => $data['invoice_amount'], ':pos' => $pos, ':rchrg' => $rchrg,
                        ':fy' => '', ':dty' => $dty, ':rsn' => '', ':pgst' => $pgst,
                        ':prs' => $prs, ':odnum' => $odnum, ':gen2' => $data['gen2'], ':gen7' => $data['gen7'], ':gen8' => $data['gen8'], ':gen10' => $data['gen10'],
                        ':gen11' => $data['gen11'], ':gen12' => $data['gen12'], ':gen13' => $data['gen13'],
                        ':gstin' => $data['seller_gstin'], ':fp' => $fp, ':ft' => $ft, ':bulk_id' => $bulk_id, ':source' => $data['source'], ':address' => $data['address'], ':order_id' => $data['order_id'], ':order_date' => $data['order_date']
                    );
                    $this->db->exec($sql, $params);
                    $invoice_id = $this->db->lastInsertId();
                }

                if ($detail_id > 0) {
                } else {
                    $this->saveParticular($invoice_id, $data['item_amount'], $data['sac'], $data['description'], $data['qty'], $data['discount'], $data['item_exclusive_tax'], $data['igst_rate'], $data['igst_tax'], $data['cgst_rate'], $data['cgst_tax'], $data['sgst_rate'], $data['sgst_tax'], $data['product_code'], $merchant_id);
                    if ($data['shipping_charges'] > 0) {
                        $this->saveShippingParticular($invoice_id, $data, $merchant_id);
                    }

                    if ($data['gift_wrap_charges'] > 0) {
                        $this->saveWrappingParticular($invoice_id, $data, $merchant_id);
                    }
                }
            }
        } catch (Exception $e) {

            $this->logger->error(__METHOD__, '[E295]Error while creating Staging Gst Invoice Error: ' . $e->getMessage() . ' Data : ' . json_encode($data));
        }
    }

    function saveShippingParticular($invoice_id, $data, $merchant_id)
    {
        try {
            $this->saveParticular($invoice_id, $data['shipping_charges'], '996812', 'Shipping charges', 0, $data['shipping_discount'], $data['shipping_exclusive_tax'], $data['shipping_igst_rate'], $data['shipping_igst_tax'], $data['shipping_cgst_rate'], $data['shipping_cgst_tax'], $data['shipping_cgst_rate'], $data['shipping_sgst_tax'], 'SH' . $data['product_code'], $merchant_id, 'S');
        } catch (Exception $e) {

            $this->logger->error(__METHOD__, '[E295]Error while creating Staging Gst Invoice Error: ' . $e->getMessage() . ' Data : ' . json_encode($data));
        }
    }

    function saveWrappingParticular($invoice_id, $data, $merchant_id)
    {
        try {
            $this->saveParticular($invoice_id, $data['gift_wrap_charges'], '998541', 'Gift wrap charges', 0, $data['gift_wrap_discount'], $data['gift_wrap_exclusive_tax'], $data['gift_igst_rate'], $data['gift_wrap_igst_tax'], $data['gift_cgst_rate'], $data['gift_wrap_cgst_tax'], $data['gift_cgst_rate'], $data['gift_wrap_sgst_tax'], 'GW' . $data['product_code'], $merchant_id, 'S');
        } catch (Exception $e) {

            $this->logger->error(__METHOD__, '[E295]Error while creating Staging Gst Invoice Error: ' . $e->getMessage() . ' Data : ' . json_encode($data));
        }
    }

    function saveParticular($invoice_id, $invoice_amount, $sac, $description, $qty, $disc, $exclusive_tax, $igst_rate, $igst_tax, $cgst_rate, $cgst_tax, $sgst_rate, $sgst_tax, $product_code, $merchant_id, $ty = 'G')
    {
        try {
            if ($ty == 'S') {
                $uqc = 'NA';
            } else {
                $uqc = 'NOS';
            }
            $sql = "INSERT INTO `iris_invoice_detail`(`invoice_id`,`num`,`sval`,`ty`,`hsnSc`,`desc`,`uqc`,`qty`,`txval`,`irt`,`iamt`,`crt`,"
                . "`camt`,`srt`,`samt`,`csrt`,`csamt`,`txp`,`disc`,`adval`,`rt`,`product_code`,`created_by`,`created_date`,`last_update_by`)"
                . "VALUES(:invoice_id,1,:sval,:ty,:hsnSc,:desc,:uqc,:qty,:txval,:irt,:iamt,:crt,:camt,:srt,:samt,:csrt,:csamt"
                . ",:txp,:disc,:adval,:rt,:product_code,:merchant_id,CURRENT_TIMESTAMP(),:merchant_id);";
            $params = array(
                ':invoice_id' => $invoice_id, ':sval' => $invoice_amount, ':ty' => $ty, ':hsnSc' => $sac,
                ':desc' => $description, ':uqc' => $uqc, ':qty' => $qty, ':txval' => $exclusive_tax, ':irt' => $igst_rate, ':iamt' => $igst_tax,
                ':crt' => $cgst_rate, ':camt' => $cgst_tax, ':srt' => $sgst_rate, ':samt' => $sgst_tax,
                ':csrt' => 0, ':csamt' => 0, ':txp' => 'T', ':disc' => $disc, ':adval' => 0, ':rt' => 0, ':product_code' => $product_code, ':merchant_id' => $merchant_id
            );
            $this->db->exec(
                $sql,
                $params
            );
        } catch (Exception $e) {

            $this->logger->error(__METHOD__, '[E295]Error while creating Staging Gst Invoice Error: ' . $e->getMessage() . ' Data : ' . json_encode($params));
        }
    }

    public function getSqlDate($date)
    {
        $date = str_replace('"', '', $date);
        if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $date)) {
            return $date;
        }
        $day = substr($date, 0, 2);
        $month = substr($date, 3, 2);
        $year = substr($date, 6, 4);
        return $year . '-' . $month . '-' . $day;
    }

    public function checkInvoiceNumber($invoiceNumber, $status, $merchantID, $bill_date, $creditnote_number, $bulk_id, $gstNumber)
    {
        try {
            if ($creditnote_number == null) {
                $sql = "select id,bulk_id from iris_invoice where inum=:inum and dty=:dty and merchant_id=:merchant_id and (ntNum is null or ntNum='') and idt=:idt and gstin=:gstin and is_active=1";
                $params = array(':inum' => $invoiceNumber, ':dty' => $status, ':merchant_id' => $merchantID, ':idt' => $bill_date, ':gstin' => $gstNumber);
            } else {
                $sql = "select id,bulk_id from iris_invoice where inum=:inum and dty=:dty and merchant_id=:merchant_id and ntNum=:ntNum and idt=:idt and gstin=:gstin and is_active=1";
                $params = array(':inum' => $invoiceNumber, ':dty' => $status, ':ntNum' => $creditnote_number, ':merchant_id' => $merchantID, ':idt' => $bill_date, ':gstin' => $gstNumber);
            }
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if (empty($row)) {
                return 0;
            } else {
                if ($row['bulk_id'] != $bulk_id) {
                    $sql = "update iris_invoice set is_active=0  where id=:id";
                    $params = array(':id' => $row['id']);
                    $this->db->exec($sql, $params);
                    return 0;
                }
                return $row['id'];
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__METHOD__, '[E2]Error while get gebulkuploadlist Error: ' . $e->getMessage());
        }
    }

    public function checkInvoiceDetailNumber($invoiceId, $product_code)
    {
        try {
            $sql = "select id from iris_invoice_detail where product_code=:product_code and invoice_id=:invoice_id";
            $params = array(':product_code' =>
            $product_code, ':invoice_id' => $invoiceId);

            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if (empty($row)) {
                return 0;
            } else {
                return $row['id'];
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__METHOD__, '[E2]Error while get gebulkuploadlist Error: ' . $e->getMessage());
        }
    }

    public function checkexistExpense($gstNumber, $invoice_number, $merchant_id, $vendor_id)
    {
        try {
            $sql = "select expense_id from expense where merchant_id=:merchant_id and invoice_no=:invoice_no and gst_number=:gst_number and vendor_id=:vendor_id";
            $params = array(':merchant_id' => $merchant_id, ':invoice_no' => $invoice_number, ':gst_number' => $gstNumber, ':vendor_id' => $vendor_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if (empty($row)) {
                return false;
            } else {
                return true;
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__METHOD__, '[E2]Error while get gebulkuploadlist Error: ' . $e->getMessage());
        }
    }

    function getInvoiceList($bulk_id)
    {
        try {
            $sql = "select * from iris_invoice where bulk_id=:bulk_id and is_active=1";
            $params = array(':bulk_id' => $bulk_id);
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();

            return $rows;
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__METHOD__, '[E2]Error while get gebulkuploadlist Error: ' . $e->getMessage());
        }
    }

    function getExpenseCategory($table_name, $merchant_id, $name)
    {
        $sql = "select id from " . $table_name . " where name=:name and merchant_id=:merchant_id and is_active=1";
        $params = array(':merchant_id' => $merchant_id, ':name' => $name);
        $this->db->exec($sql, $params);
        $row = $this->db->single();
        if (empty($row)) {
            $sql = "INSERT INTO `" . $table_name . "`(`merchant_id`,`name`,`created_by`,`created_date`,`last_update_by`)"
                . "VALUES(:merchant_id,:name,:merchant_id,CURRENT_TIMESTAMP(),:merchant_id);";
            $params = array(':merchant_id' => $merchant_id, ':name' => $name);
            $this->db->exec($sql, $params);
            return $this->db->lastInsertId();
        } else {
            return $row['id'];
        }
    }

    function getVendorId($merchant_id, $gst_number)
    {
        try {
            $sql = "select vendor_id from vendor where gst_number=:gst_number and merchant_id=:merchant_id and is_active=1";
            $params = array(':merchant_id' => $merchant_id, ':gst_number' => $gst_number);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if (empty($row)) {
                $state = $this->getStateName(substr($gst_number, 0, 2));
                $sql = "INSERT INTO vendor(`merchant_id`,`vendor_name`,`gst_number`,state,email_id,mobile,pan,adhar_card,address,city,zipcode,bank_holder_name,bank_account_no,`created_by`,`created_date`,`last_update_by`)"
                    . "VALUES(:merchant_id,'Amazon Stock',:gst_number,:state,'','','','','','','','','',:merchant_id,CURRENT_TIMESTAMP(),:merchant_id);";
                $params = array(':merchant_id' => $merchant_id, ':gst_number' => $gst_number, ':state' => $state);
                $this->db->exec($sql, $params);
                return $this->db->lastInsertId();
            } else {
                return $row['vendor_id'];
            }
        } catch (Exception $e) {
            Sentry\captureException($e);
            echo $e->getMessage();

            $this->logger->error(__METHOD__, '[E2]Error while get vendor id Error: ' . $e->getMessage());
        }
    }

    function saveStockExpense($bulk_id, $merchant_id)
    {
        try {
            $rows = $this->getInvoiceList($bulk_id);
            $category_id = $this->getExpenseCategory('expense_category', $merchant_id, 'Stock transfer');
            $department_id = $this->getExpenseCategory('expense_department', $merchant_id, 'Amazon');
            $expense_rows = array();
            $int = 0;
            $tax_array = array('5' => 3, '12' => 4, '18' => 5, '28' => 6);
            foreach ($rows as $row) {
                $array = array();
                $details = $this->getInvoiceDetail($row['id']);
                $sumDet = $this->getInvoiceSumDetail($row['id']);
                $array['vendor_id'] = $this->getVendorId($merchant_id, $row['ctin']);
                $exist = $this->checkexistExpense($row['gstin'], $row['inum'], $merchant_id, $array['vendor_id']);
                if ($exist == false) {
                    $array['category_id'] = $category_id;
                    $array['department_id'] = $department_id;
                    $array['invoice_no'] = $row['inum'];
                    $array['expense_no'] = 'Auto generate';
                    $array['bill_date'] = $row['idt'];
                    $array['due_date'] = $row['idt'];
                    $array['base_amount'] = $sumDet['t_taxable'];
                    $array['total_amount'] = $row['val'];
                    $array['cgst_amount'] = $sumDet['t_cgst'];
                    $array['sgst_amount'] = $sumDet['t_cgst'];
                    $array['igst_amount'] = $sumDet['t_igst'];
                    $array['gst_number'] = $row['gstin'];
                    $array['tds'] = 0;
                    $array['discount'] = 0;
                    $array['adjustment'] = 0;
                    $array['payment_status'] = 0;
                    $array['payment_mode'] = 0;
                    $array['narrative'] = '';
                    foreach ($details as $particular) {
                        $parray = array();
                        $parray['particular_name'] = $particular['desc'];
                        $parray['tax'] = $particular['desc'];
                        $parray['sac'] = $particular['hsnSc'];
                        $parray['qty'] = $particular['qty'];
                        $parray['particular_id'] = 0;
                        $parray['rate'] = round($particular['txval'] / $parray['qty'], 2);
                        $gst_per = ($particular['irt'] > 0) ? $particular['irt'] : $particular['crt'] * 2;
                        $parray['amount'] = $particular['sval'];
                        $parray['gst_percent'] = $gst_per;
                        $parray['tax'] = $tax_array[round($gst_per, 0)];
                        $parray['tax'] = ($parray['tax'] > 0) ? $parray['tax'] : 0;
                        $parray['cgst_amount'] = $particular['camt'];
                        $parray['sgst_amount'] = $particular['camt'];
                        $parray['igst_amount'] = $particular['iamt'];
                        $parray['total_value'] = $particular['sval'];
                        $array['particular'][] = $parray;
                    }
                    $array['type'] = 1;
                    $array['notify'] = 0;
                    $expense_rows[] = $array;
                    $int++;
                }
                if ($int == 50) {
                    $this->saveExpense($merchant_id, $expense_rows);
                    $expense_rows = array();
                    $int = 0;
                }
            }
            $this->saveExpense($merchant_id, $expense_rows);
        } catch (Exception $e) {
            Sentry\captureException($e);
            echo $e->getMessage();

            $this->logger->error(__METHOD__, '[E2]Error while save expense Error: ' . $e->getMessage());
        }
    }

    function saveExpense($merchant_id, $expense_array)
    {
        $api_base = getenv('SWIPEZ_API_URL');
        if ($api_base == '') {
            $api_base = 'http://swipez.api/api/';
        }
        try {
            $login_token = $this->getToken($merchant_id);
            $json = $this->apisrequest($api_base . 'token', 'login_token=' . $login_token, array());
            $array = json_decode($json, 1);
            $api_token = $array['success']['token'];
            $header = array(
                "Authorization: Bearer " . $api_token
            );
            $json = json_encode($expense_array);
            $result = $this->apisrequest($api_base . 'v1/expense/save', $json, $header);
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__CLASS__, '[E6]Error while saving transfer ' . $bulkUpload['bulk_upload_id'] . ' Error: ' . $e->getMessage());
        }
    }

    function getProductcodeInvoice($merchant_id, $gstin, $order_id)
    {
        try {
            $sql = "select inum as invoice_number,idt as invoice_date,invTyp,ctin,cname,ctpy,hsnSc,`desc`,c.config_value as state,pos,splyTy from iris_invoice i inner join  iris_invoice_detail d on d.invoice_id=i.id inner join config c on c.config_key=i.pos and c.config_type='gst_state_code' where order_id=:order_id and merchant_id=:merchant_id and gstin=:gstin and dty='RI'";
            $params = array(':order_id' =>
            $order_id, ':merchant_id' => $merchant_id, ':gstin' => $gstin);
            $this->db->exec($sql, $params);
            $row = $this->db->single();

            return $row;
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__METHOD__, '[E2]Error while get gebulkuploadlist Error: ' . $e->getMessage());
        }
    }

    function getInvoiceSum($invoice_id)
    {
        try {
            $sql = "select sum(sval) as total,count(id) as cnt from iris_invoice_detail where invoice_id=:invoice_id";
            $params = array(':invoice_id' =>
            $invoice_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row;
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__METHOD__, '[E2]Error while get gebulkuploadlist Error: ' . $e->getMessage());
        }
    }

    function getIRISGSTDetails()
    {
        try {
            $sql = "select * from config where config_type='IRIS_GST_DATA'";
            $params = array();


            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();

            return $rows;
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__METHOD__, '[E2]Error while get gebulkuploadlist Error: ' . $e->getMessage());
        }
    }

    function getStateName($config_key)
    {
        try {
            if (substr($config_key, 0, 1) == '0') {
                $config_key = substr($config_key, 1);
            }
            $sql = "select config_value from config where config_type='gst_state_code' and config_key=:config_key";
            $params = array(':config_key' => $config_key);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row['config_value'];
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__METHOD__, '[E2]Error while get gebulkuploadlist Error: ' . $e->getMessage());
        }
    }

    function getPendingInvoice()
    {
        try {
            $sql = "select * from iris_invoice where status=0";
            $params = array();
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();

            return $rows;
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__METHOD__, '[E2]Error while get gebulkuploadlist Error: ' . $e->getMessage());
        }
    }

    function updateBulkID($invoice_id, $bulk_id)
    {
        try {
            $sql = "update iris_invoice set bulk_id=:bulk_id where id=:invoice_id";
            $params = array(':bulk_id' =>
            $bulk_id, ':invoice_id' => $invoice_id);

            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__METHOD__, '[E2]Error while update total Error: ' . $e->getMessage());
        }
    }

    function updateSum($invoice_id, $total)
    {
        try {
            $sql = "update iris_invoice set val=:val where id=:invoice_id";
            $params = array(':val' =>
            $total, ':invoice_id' => $invoice_id);

            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__METHOD__, '[E2]Error while update total Error: ' . $e->getMessage());
        }
    }

    function getInvoiceDetail($invoice_id)
    {
        try {
            $sql = "select * from iris_invoice_detail where invoice_id=:invoice_id";
            $params = array(':invoice_id' =>
            $invoice_id);
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            return $rows;
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__METHOD__, '[E2]Error while get gebulkuploadlist Error: ' . $e->getMessage());
        }
    }

    function getInvoiceSumDetail($invoice_id)
    {
        try {
            $sql = "select sum(txval) as t_taxable,sum(sval) as t_total,sum(camt) as t_cgst,sum(iamt) as t_igst from iris_invoice_detail where invoice_id=:invoice_id";
            $params = array(':invoice_id' =>
            $invoice_id);
            $this->db->exec($sql, $params);
            $rows = $this->db->single();
            return $rows;
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__METHOD__, '[E2]Error while get gebulkuploadlist Error: ' . $e->getMessage());
        }
    }

    function getStatecode($value)
    {
        try {
            $sql = "SELECT config_key FROM config where config_value=:value and config_type='gst_state_code'";
            $params = array(':value' => $value);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if (empty($row)) {
                return 0;
            } else {
                return $row['config_key'];
            }
        } catch (Exception $e) {
            Sentry\captureException($e);


            $this->logger->error(__METHOD__, '[E2]Error while get gebulkuploadlist Error: ' . $e->getMessage());
        }
    }

    function getexceldate($val)
    {
        try {
            $excel_date = PHPExcel_Style_NumberFormat::toFormattedString($val, 'Y-m-d');
            $excel_date = str_replace('/', '-', $excel_date);
        } catch (Exception $e) {
            $excel_date = (string) $val;
        }

        try {
            $excel_date = str_replace('-', '/', $excel_date);
            $date = new DateTime($excel_date);
        } catch (Exception $e) {

            $excel_date = str_replace('/', '-', $excel_date);
            try {
                $date = new DateTime($excel_date);
            } catch (Exception $e) {
                $value = (string) $val;
            }
        }
        try {
            if (isset($date)) {
                $value = $date->format('Y-m-d');
            }
        } catch (Exception $e) {
            $value = (string) $val;
        }
        return $value;
    }

    function saving()
    {
        $irisdetails = $this->getIRISGSTDetails();
        require_once UTIL . 'IRISAPI.php';
        $irisapi = new IRISAPI($irisdetails);
        $bulkuploadlist = $this->getPendingInvoice();
        foreach ($bulkuploadlist as $rowdata) {
            try {
                $json = array();
                $invoices = array();
                $invdetail = $this->getInvoiceDetail($rowdata['id']);
                $count = 0;
                $row = 1;
                $invoices['invTyp'] = $rowdata['invTyp'];
                $invoices['splyTy'] = $rowdata['splyTy'];
                $invoices['dst'] = $rowdata['dst'];
                $invoices['refnum'] = $rowdata['refnum'];
                $date = new DateTime($rowdata['pdt']);
                $invoices['pdt'] = $date->format('d-m-Y');
                $invoices['ctpy'] = $rowdata['ctpy'];
                $invoices['ctin'] = $rowdata['ctin'];
                $invoices['cname'] = $rowdata['cname'];
                $invoices['ntNum'] = $rowdata['ntNum'];
                $invoices['ntDt'] = $rowdata['ntDt'];
                $invoices['inum'] = $rowdata['inum'];
                $date = new DateTime($rowdata['idt']);
                $invoices['idt'] = $date->format('d-m-Y');
                $invoices['val'] = $rowdata['val'];
                $invoices['pos'] = $rowdata['pos'];
                $invoices['rchrg'] = $rowdata['rchrg'];
                $invoices['fy'] = $rowdata['fy'];
                $invoices['dty'] = $rowdata['dty'];
                $invoices['rsn'] = $rowdata['rsn'];
                $invoices['pgst'] = $rowdata['pgst'];
                $invoices['prs'] = $rowdata['prs'];
                $invoices['odnum'] = $rowdata['odnum'];
                $invoices['gen2'] = $rowdata['gen2'];
                $invoices['gen7'] = $rowdata['gen7'];
                $invoices['gen8'] = $rowdata['gen8'];
                $invoices['gen10'] = $rowdata['gen10'];
                $invoices['gen11'] = $rowdata['gen11'];
                $invoices['gen12'] = $rowdata['gen12'];
                $invoices['gen13'] = $rowdata['gen13'];

                foreach ($invdetail as $invdet) {
                    $itemDetails['num'] = $row;
                    $itemDetails['sval'] = $invdet['sval'];
                    $itemDetails['ty'] = $invdet['ty'];
                    $itemDetails['hsnSc'] = $invdet['hsnSc'];
                    $itemDetails['desc'] = $invdet['desc'];
                    $itemDetails['uqc'] = $invdet['uqc'];
                    $itemDetails['qty'] = $invdet['qty'];
                    $itemDetails['txval'] = $invdet['txval'];
                    $itemDetails['irt'] = ($invdet['irt'] > 0) ? $invdet['irt'] : null;
                    $itemDetails['iamt'] = ($invdet['iamt'] > 0) ? $invdet['iamt'] : null;
                    $itemDetails['crt'] = ($invdet['crt'] > 0) ? $invdet['crt'] : null;
                    $itemDetails['camt'] = ($invdet['camt'] > 0) ? $invdet['camt'] : null;
                    $itemDetails['srt'] = ($invdet['srt'] > 0) ? $invdet['srt'] : null;
                    $itemDetails['samt'] = ($invdet['samt'] > 0) ? $invdet['samt'] : null;
                    $itemDetails['csrt'] = ($invdet['csrt'] > 0) ? $invdet['csrt'] : null;
                    $itemDetails['csamt'] = ($invdet['csamt'] > 0) ? $invdet['csamt'] : null;
                    $itemDetails['txp'] = $invdet['txp'];
                    $itemDetails['disc'] = $invdet['disc'];
                    $itemDetails['adval'] = $invdet['adval'];
                    $itemDetails['rt'] = $invdet['rt'];
                    $invoices['itemDetails'][] = $itemDetails;
                    $row++;
                }
                $invoices['gstin'] = $rowdata['gstin'];
                $invoices['fp'] = $rowdata['fp'];
                $invoices['ft'] = $rowdata['ft'];
                $json['invoices'][] = $invoices;

                $this->logger->info(__METHOD__, 'Request ' . json_encode($json));
                $res = $irisapi->saveInvoice($rowdata['gstin'], json_encode($json));
                $this->logger->info(__METHOD__, 'Response ' . json_encode($res));
                if ($res['response']['status'] == 'SUCCESS') {
                    $this->common->genericupdate('iris_invoice', 'status', 1, 'id', $rowdata['id']);
                } else {
                    $this->common->genericupdate('iris_invoice', 'status', 2, 'id', $rowdata['id']);
                }
            } catch (Exception $e) {
                Sentry\captureException($e);

                $this->logger->error(__METHOD__, '[E6]Error while saving transfer ' . $rowdata['id'] . ' Error: ' . $e->getMessage());
            }
        }
    }
}

new SaveGSTInvoice();
