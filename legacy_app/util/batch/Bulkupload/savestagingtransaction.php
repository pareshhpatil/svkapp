<?php

include_once('bulkupload.php');

class SaveStagingTransaction extends BulkUpload
{

    function __construct()
    {
        parent::__construct();
        $this->transfersave();
    }

    public function transfersave()
    {
        $bulkuploadlist = $this->getbulkuploadlist(2, 5);
        foreach ($bulkuploadlist as $bulkupload) {
            try {
                $bulk_id = $bulkupload['bulk_upload_id'];
                if (!empty($bulkupload)) {
                    $this->logger->info(__CLASS__, 'Saving Bulk upload staging offline transaction initiate bulk_id is : ' . $bulk_id . ' and total rows are ' . $bulkupload['total_rows']);
                    $file = '../../../../public/uploads/Excel/' . $bulkupload['merchant_id'] . '/staging/' . $bulkupload['system_filename'];
                    if (!file_exists($file)) {
                        $this->updateBulkUploadStatus($bulk_id, 1, 'File does not exist');
                        throw new Exception($file . ' file does not exist');
                    } else {
                        $result = $this->bulk_upload($file, $bulkupload);
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

    public function bulk_upload($inputFile, $bulkupload)
    {
        try {
            $bulk_id = $bulkupload['bulk_upload_id'];
            $inputFileType = PHPExcel_IOFactory::identify($inputFile);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFile);
            $subject = $objPHPExcel->getProperties()->getSubject();
            $link = $this->encrypt->decode($subject);
            $merchant_id = $bulkupload['merchant_id'];
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
                        $getrawvalue[$rowno][] = $cell->getValue();
                    }
                    $post_row = array();
                    $int = 4;

                    $post_row['invoice_id'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                    try {
                        if (is_numeric($getrawvalue[$rowno][$int])) {
                            $value = PHPExcel_Style_NumberFormat::toFormattedString($getrawvalue[$rowno][$int], 'Y-m-d');
                        } else {
                            $value = 'NA';
                        }
                    } catch (Exception $e) {
                        $value = 'NA';
                    }
                    $post_row['date'] = $value;
                    $int++;
                    $post_row['amount'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                    $payment_mode = (string) $getcolumnvalue[$rowno][$int];
                    $payment_mode = trim($payment_mode);
                    switch (strtolower($payment_mode)) {
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
                        default:
                            $mode = 0;
                            break;
                    }
                    $post_row['mode'] = $mode;
                    $int++;
                    $post_row['bank_name'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                    $post_row['bank_transaction_no'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                    $post_row['cheque_no'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                    $post_row['cash_paid_to'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                    $notify = ucfirst((string) $getcolumnvalue[$rowno][$int]);
                    $post_row['notify'] = ($notify == 'Yes') ? 1 : 0;
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
                        $data['merchant_id'] = $merchant_id;
                        $result = $this->validateuploadpayment($data);
                        if (!empty($result)) {
                            $is_falied = 1;
                            $result['row'] = $errorrow;
                            $errors[] = $result;
                        } else {
                        }
                        $errorrow++;
                    }
                }

                if (empty($errors)) {
                    foreach ($_POSTarray as $data) {
                        $result = $this->createStagingTransaction($bulk_id, $bulkupload['merchant_id'], $data, $bulkupload['created_by']);
                        $count_int++;
                    }
                }
                if ($is_falied == 0) {
                    $this->logger->info(__CLASS__, 'Bulk staging offline transaction saved sucessfully bulk id : ' . $bulk_id . ' Total offline transaction ' . $count_int);
                    return array('status' => true);
                } else {
                    return array('status' => false, 'error' => json_encode($errors));
                }
            } catch (Exception $e) {
                Sentry\captureException($e);

                $this->logger->error(__CLASS__, '[E6]Error while saving staging offline transaction save excel  bulk id : ' . $bulk_id . ' Error: ' . $e->getMessage());
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__CLASS__, '[E7]Error while uploading excel  bulk id : ' . $bulk_id . ' Error: ' . $e->getMessage());
        }
    }

    function validateuploadpayment($data)
    {
        try {
            $request = $this->common->getSingleValue('payment_request', 'payment_request_id', $data['invoice_id'], 0, " and merchant_id='" . $data['merchant_id'] . "'");
            if (empty($request)) {
                $hasErrors[0][0] = 'Invoice ID';
                $hasErrors[0][1] = 'Invalid invoice id';
                return $hasErrors;
            } else {
                if ($request['payment_request_status'] == 1) {
                    $hasErrors[0][0] = 'Invoice ID';
                    $hasErrors[0][1] = 'Invoice has been paid already';
                    return $hasErrors;
                }
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E246]Error while sending payment request Error:' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function createStagingTransaction($bulk_id, $merchant_id, $data, $user_id)
    {
        try {
            $data['cheque_no'] = ($data['cheque_no'] > 0) ? $data['cheque_no'] : '';
            $sql = "INSERT INTO `staging_offline_response`(`payment_request_id`,`merchant_id`,`offline_response_type`,
`settlement_date`,`bank_transaction_no`,`bank_name`,`amount`,`cheque_no`,`cash_paid_to`,`notify_patron`,`bulk_id`,`created_by`,`created_date`,`last_update_by`)VALUES(:request_id,:merchant_id,:type,:date,:bank_transaction_no,:bank_name,:amount,:cheque_no,:cash_paid_to,:notify_patron,:bulk_id,:user_id,CURRENT_TIMESTAMP(),:user_id);";
            $params = array(
                ':bulk_id' => $bulk_id, ':merchant_id' => $merchant_id, ':request_id' => $data['invoice_id'], ':type' => $data['mode'], ':date' => $data['date'],
                ':bank_transaction_no' => $data['bank_transaction_no'], ':bank_name' => $data['bank_name'], ':amount' => $data['amount'], ':cheque_no' => $data['cheque_no'],
                ':cash_paid_to' => $data['cash_paid_to'], ':notify_patron' => $data['notify'], ':user_id' => $user_id
            );
            $this->db->exec($sql, $params);
            return $this->db->lastInsertId();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E295]Error while creating offline transaction Error: ' . $e->getMessage());
        }
    }
}

new SaveStagingTransaction();
