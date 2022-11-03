<?php

include_once ('bulkupload.php');

class SaveStagingTransfer extends BulkUpload {

    function __construct() {
        parent::__construct();
        $this->transfersave(4);
        $this->transfersave(9);
    }

    public function transfersave($ttype) {
        $bulkuploadlist = $this->getbulkuploadlist(2, $ttype);
        foreach ($bulkuploadlist as $bulkupload) {
            $bulk_id = $bulkupload['bulk_upload_id'];
            $this->updateBulkUploadStatus($bulk_id, 8);
            try {
                if (!empty($bulkupload)) {
                    $this->logger->info(__CLASS__, 'Saving Bulk upload staging transfer initiate bulk_id is : ' . $bulk_id . ' and total rows are ' . $bulkupload['total_rows']);
                    $file = '../../../../public/uploads/Excel/' . $bulkupload['merchant_id'] . '/staging/' . $bulkupload['system_filename'];
                    if (!file_exists($file)) {
                        $this->updateBulkUploadStatus($bulk_id, 1, 'File does not exist');
                        throw new Exception($file . ' file does not exist');
                    } else {
                        if ($ttype == 9) {
                            $result = $this->bulkUploadPayout($file, $bulk_id);
                        } else {
                            $result = $this->bulk_upload($file, $bulk_id);
                        }
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

    public function bulk_upload($inputFile, $bulk_id) {
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

                    $ttype = (string) $getcolumnvalue[$rowno][$int];
                    if (strtolower($ttype) == 'franchise') {
                        $post_row['transfer_type'] = 2;
                    } elseif (strtolower($ttype) == 'vendor') {
                        $post_row['transfer_type'] = 1;
                    } else {
                        $post_row['transfer_type'] = 3;
                    }
                    $int++;
                    $b_id = (string) $getcolumnvalue[$rowno][$int];
                    if ($post_row['transfer_type'] == 1) {
                        if ($b_id != '') {
                            $vendor_id = $this->common->getRowValue('vendor_id', 'vendor', 'vendor_code', $b_id, 1, " and merchant_id='" . $merchant_id . "' and status=1");
                            if ($vendor_id > 0) {
                                $post_row['vendor_id'] = $vendor_id;
                            } else {
                                $post_row['vendor_id'] = $b_id;
                            }
                        } else {
                            $post_row['vendor_id'] = '';
                        }
                        $post_row['franchise_id'] = 'no';
                    } else {
                        $post_row['vendor_id'] = 'no';
                        if ($b_id > 0) {
                            $post_row['franchise_id'] = $b_id;
                        } else {
                            $post_row['franchise_id'] = '';
                        }
                    }
                    $int++;
                    $post_row['amount'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                    $type = 2;
                    $payment_mode = (string) $getcolumnvalue[$rowno][$int];

                    switch (strtolower($payment_mode)) {
                        case 'payment gateway':
                            $mode = 0;
                            $type = 1;
                        case 'neft':
                            $mode = 1;
                            break;
                        case 'cheque':
                            $mode = 2;
                            break;
                        case 'cash':
                            $mode = 3;
                            break;
                        case 'online payment':
                            $mode = 5;
                            break;
                        default :
                            $mode = 50;
                            break;
                    }
                    $post_row['type'] = $type;
                    $post_row['mode'] = $mode;
                    $int++;
                    try {
                        $excel_date = PHPExcel_Style_NumberFormat::toFormattedString($getcolumnvalue[$rowno][$int], 'Y-m-d');
                        $excel_date = str_replace('/', '-', $excel_date);
                    } catch (Exception $e) {
                        $excel_date = (string) $getcolumnvalue[$rowno][$int];
                    }

                    try {
                        $excel_date = str_replace('-', '/', $excel_date);
                        $date = new DateTime($excel_date);
                    } catch (Exception $e) {
                        $excel_date = str_replace('/', '-', $excel_date);
                        try {
                            $date = new DateTime($excel_date);
                        } catch (Exception $e) {
                            $value = (string) $getcolumnvalue[$rowno][$int];
                        }
                    }
                    try {
                        if (isset($date)) {
                            $value = $date->format('Y-m-d');
                        }
                    } catch (Exception $e) {
                        $value = (string) $getcolumnvalue[$rowno][$int];
                    }
                    $post_row['date'] = $value;
                    $int++;
                    $post_row['bank_name'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                    $post_row['bank_transaction_no'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                    $post_row['cheque_no'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                    $post_row['cash_paid_to'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                    $post_row['narrative'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                    $_POSTarray[] = $post_row;
                }
            }
            $count_int = 0;
            try {
                $errorrow = 2;
                $errors = array();

                if (empty($errors)) {
                    foreach ($_POSTarray as $data) {
                        $result = $this->createStagingTransfer($bulk_id, $merchant_id, $data, $merchant_id);
                        $count_int++;
                    }
                }
                if ($is_falied == 0) {
                    $this->logger->info(__CLASS__, 'Bulk staging transfer saved sucessfully bulk id : ' . $bulk_id . ' Total transfer ' . $count_int);
                    return array('status' => true);
                } else {
                    return array('status' => false, 'error' => json_encode($errors));
                }
            } catch (Exception $e) {
Sentry\captureException($e);
                
$this->logger->error(__CLASS__, '[E6]Error while saving staging transfer save excel  bulk id : ' . $bulk_id . ' Error: ' . $e->getMessage());
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
$this->logger->error(__CLASS__, '[E7]Error while uploading excel  bulk id : ' . $bulk_id . ' Error: ' . $e->getMessage());
        }
    }

    public function bulkUploadPayout($inputFile, $bulk_id) {
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

                    $post_row['beneficiary_id'] = (string) $getcolumnvalue[$rowno][$int];
                    $bene_id = $this->common->getRowValue('beneficiary_id', 'beneficiary', 'beneficiary_code', $post_row['beneficiary_id'], 1, " and merchant_id='" . $merchant_id . "' and status=1");
                    if ($bene_id != '') {
                        $post_row['beneficiary_id'] = $bene_id;
                    }
                    $int++;
                    $post_row['amount'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                    $post_row['narrative'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                    $post_row['payment_mode'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                    $post_row['type'] = 1;
                    $post_row['transfer_type'] = 3;
                    $post_row['mode'] = 1;
                    $_POSTarray[] = $post_row;
                }
            }
            $count_int = 0;
            try {
                $errorrow = 2;
                $errors = array();

                if (empty($errors)) {
                    foreach ($_POSTarray as $data) {
                        $result = $this->createStagingTransfer($bulk_id, $merchant_id, $data, $merchant_id);
                        $count_int++;
                    }
                }
                if ($is_falied == 0) {
                    $this->logger->info(__CLASS__, 'Bulk staging transfer saved sucessfully bulk id : ' . $bulk_id . ' Total transfer ' . $count_int);
                    return array('status' => true);
                } else {
                    return array('status' => false, 'error' => json_encode($errors));
                }
            } catch (Exception $e) {
Sentry\captureException($e);
                
$this->logger->error(__CLASS__, '[E6]Error while saving staging transfer save excel  bulk id : ' . $bulk_id . ' Error: ' . $e->getMessage());
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
$this->logger->error(__CLASS__, '[E7]Error while uploading excel  bulk id : ' . $bulk_id . ' Error: ' . $e->getMessage());
        }
    }

    public function createStagingTransfer($bulk_id, $merchant_id, $data, $user_id) {
        try {
            $datet = new DateTime($data['date']);
            $date = $datet->format('Y-m-d');
            $data['vendor_id'] = ($data['vendor_id'] > 0) ? $data['vendor_id'] : 0;
            $data['franchise_id'] = ($data['franchise_id'] > 0) ? $data['franchise_id'] : 0;
            $sql = "INSERT INTO `staging_vendor_transfer`(`type`,`beneficiary_type`,`beneficiary_id`,`merchant_id`,`vendor_id`,`franchise_id`,`amount`,`narrative`,`status`,`offline_response_type`,`transfer_date`,
                `bank_transaction_no`,`cheque_no`,`cash_paid_to`,`bank_name`,`bulk_id`,`mode`,`created_by`,`created_date`,`last_update_by`)
                VALUES(:type,:beneficiary_type,:beneficiary_id,:merchant_id,:vendor_id,:franchise_id,:amount,:narrative,0,:response_type,:date,:bank_transaction_no,:cheque_no,:cash_paid_to,:bank_name,:bulk_id,:mode,:user_id,CURRENT_TIMESTAMP(),:user_id);";
            $params = array(':type' => $data['type'], ':beneficiary_id' => $data['beneficiary_id'], ':beneficiary_type' => $data['transfer_type'], ':merchant_id' => $merchant_id, ':vendor_id' => $data['vendor_id'], ':franchise_id' => $data['franchise_id'], ':amount' => $data['amount'],
                ':narrative' => $data['narrative'], ':response_type' => $data['mode'], ':date' => $date, ':bank_transaction_no' => $data['bank_transaction_no'],
                ':cheque_no' => $data['cheque_no'], ':cash_paid_to' => $data['cash_paid_to'], ':bank_name' => $data['bank_name'], ':bulk_id' => $bulk_id,':mode' => $data['payment_mode'], ':user_id' => $user_id);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E109-8]Error whil eoffline seat booking Error:  for user id[' . $merchant_id . ']' . $e->getMessage());
            echo $e->getMessage();
        }
    }

}

new SaveStagingTransfer();
