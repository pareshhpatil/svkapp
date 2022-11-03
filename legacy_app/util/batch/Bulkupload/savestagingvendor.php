<?php

include_once('bulkupload.php');

class SaveStagingVendor extends BulkUpload
{

    function __construct()
    {
        parent::__construct();
        $this->vendorsave();
        $this->beneficiarysave();
    }

    public function beneficiarysave()
    {
        $bulkuploadlist = $this->getbulkuploadlist(2, 8);
        foreach ($bulkuploadlist as $bulkupload) {
            try {
                $bulk_id = $bulkupload['bulk_upload_id'];
                $this->updateBulkUploadStatus($bulk_id, 8);
                if (!empty($bulkupload)) {
                    $this->logger->info(__CLASS__, 'Saving Bulk upload staging beneficiary initiate bulk_id is : ' . $bulk_id . ' and total rows are ' . $bulkupload['total_rows']);
                    $file = '../../../../public/uploads/Excel/' . $bulkupload['merchant_id'] . '/staging/' . $bulkupload['system_filename'];
                    if (!file_exists($file)) {
                        $this->updateBulkUploadStatus($bulk_id, 1, 'File does not exist');
                        throw new Exception($file . ' file does not exist');
                    } else {
                        $result = $this->beneficiary_bulk_upload($file, $bulk_id);
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

    public function vendorsave()
    {
        $bulkuploadlist = $this->getbulkuploadlist(2, 3);
        foreach ($bulkuploadlist as $bulkupload) {
            try {
                $bulk_id = $bulkupload['bulk_upload_id'];
                if (!empty($bulkupload)) {
                    $this->logger->info(__CLASS__, 'Saving Bulk upload staging vendor initiate bulk_id is : ' . $bulk_id . ' and total rows are ' . $bulkupload['total_rows']);
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

    public function beneficiary_bulk_upload($inputFile, $bulk_id)
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
                    $post_row['type'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                    $post_row['beneficiary_code'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                    $post_row['name'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                    $post_row['email'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                    $post_row['mobile'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                    $post_row['address'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                    $post_row['city'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                    $post_row['state'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                    $post_row['zipcode'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                    $post_row['account_number'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                    $post_row['ifsc_code'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                    $post_row['upi'] = (string) $getcolumnvalue[$rowno][$int];
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
                        $result = $this->createStagingBeneficiary($bulk_id, $merchant_id, $data, 0, $merchant_id);
                        $count_int++;
                    }
                }
                if ($is_falied == 0) {
                    $this->logger->info(__CLASS__, 'Bulk staging vendor saved sucessfully bulk id : ' . $bulk_id . ' Total vendor ' . $count_int);
                    return array('status' => true);
                } else {
                    return array('status' => false, 'error' => json_encode($errors));
                }
            } catch (Exception $e) {
                Sentry\captureException($e);

                $this->logger->error(__CLASS__, '[E6]Error while saving staging vendor save excel  bulk id : ' . $bulk_id . ' Error: ' . $e->getMessage());
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__CLASS__, '[E7]Error while uploading excel  bulk id : ' . $bulk_id . ' Error: ' . $e->getMessage());
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
                    $auto_generate_code = $this->common->getRowValue('vendor_code_auto_generate', 'merchant_setting', 'merchant_id', $merchant_id);
                    if ($auto_generate_code == 0) {
                        $post_row['vendor_code'] = (string) $getcolumnvalue[$rowno][$int];
                        $int++;
                    } else {
                        $post_row['vendor_code'] = '';
                    }
                    $post_row['vendor_name'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                    $post_row['email'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                    $post_row['mobile'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                    $post_row['address'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                    $post_row['city'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                    $post_row['state'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                    $post_row['zipcode'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                    $post_row['pan'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                    $post_row['adhar_card'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                    $post_row['gst'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                    $post_row['account_holder_name'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                    $post_row['account_number'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                    $post_row['bank_name'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                    $post_row['account_type'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                    $post_row['ifsc_code'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                    // if ($this->onlineSettlementStatus($merchant_id) == 1) {
                    $val = (string) $getcolumnvalue[$rowno][$int];
                    if ($val == '' || strtolower($val) == 'yes' || strtolower($val) == 'no') {
                        $post_row['online_settlement'] = (strtolower($val) == 'yes') ? 1 : 0;
                    } else {
                        $post_row['online_settlement'] = 2;
                    }
                    $int++;

                    $val = (string) $getcolumnvalue[$rowno][$int];
                    if ($val == '' || strtolower($val) == 'percentage' || strtolower($val) == 'fixed') {
                        $post_row['commision_type'] = (strtolower($val) == 'percentage') ? 1 : 2;
                    } else {
                        $post_row['commision_type'] = 3;
                    }
                    $int++;

                    if ($post_row['commision_type'] == 1) {
                        $post_row['commision_percent'] = (string) $getcolumnvalue[$rowno][$int];
                        $post_row['commision_amount'] = 0;
                    } else if ($post_row['commision_type'] == 2) {
                        $post_row['commision_amount'] = (string) $getcolumnvalue[$rowno][$int];
                        $post_row['commision_percent'] = 0;
                    } else {
                        $post_row['commision_amount'] = 0;
                        $post_row['commision_percent'] = 0;
                    }
                    $int++;

                    $val = (string) $getcolumnvalue[$rowno][$int];
                    if ($val == '' || strtolower($val) == 'automatic' || strtolower($val) == 'manual') {
                        $post_row['settlement_type'] = (strtolower($val) == 'manual') ? 1 : 2;
                    } else {
                        $post_row['settlement_type'] = 3;
                    }
                    $int++;
                    //} else {
                    //    $post_row['settlement_type'] = 0;
                    //   $post_row['commision_amount'] = 0;
                    //  $post_row['commision_percent'] = 0;
                    //    $post_row['commision_type'] = 0;
                    //    $post_row['online_settlement'] = 0;
                    // }
                    $_POSTarray[] = $post_row;
                }
            }
            $count_int = 0;
            try {
                $errorrow = 2;
                $errors = array();

                if (empty($errors)) {
                    foreach ($_POSTarray as $data) {
                        $result = $this->createStagingVendor($bulk_id, $merchant_id, $data, 0, $merchant_id);
                        $count_int++;
                    }
                }
                if ($is_falied == 0) {
                    $this->logger->info(__CLASS__, 'Bulk staging vendor saved sucessfully bulk id : ' . $bulk_id . ' Total vendor ' . $count_int);
                    return array('status' => true);
                } else {
                    return array('status' => false, 'error' => json_encode($errors));
                }
            } catch (Exception $e) {
                Sentry\captureException($e);

                $this->logger->error(__CLASS__, '[E6]Error while saving staging vendor save excel  bulk id : ' . $bulk_id . ' Error: ' . $e->getMessage());
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__CLASS__, '[E7]Error while uploading excel  bulk id : ' . $bulk_id . ' Error: ' . $e->getMessage());
        }
    }

    public function getState($state)
    {
        $sql = "select config_value from config where config_type='gst_state_code'";
        $this->db->exec($sql);
        $states = $this->db->resultset();
        foreach ($states as $item) {
            if (str_replace(' ', '', strtolower($item['config_value'])) == str_replace(' ', '', strtolower($state))) {
                return $item['config_value'];
            }
        }
    }

    public function createStagingVendor($bulk_id, $merchant_id, $data, $status, $user_id)
    {
        try {
            $commision_type = ($data['commision_type'] > 0) ? $data['commision_type'] : 0;
            $commision_percent = ($data['commision_percent'] > 0) ? $data['commision_percent'] : 0;
            $commision_amount = ($data['commision_amount'] > 0) ? $data['commision_amount'] : 0;
            $online_settlement = (isset($data['online_settlement'])) ? $data['online_settlement'] : 0;
            $settlement_type = ($data['settlement_type'] > 0) ? $data['settlement_type'] : 0;
            $db_state = $this->getState($data['state']);
            $sql = "INSERT INTO `staging_vendor`(bulk_id,`online_pg_settlement`,`settlement_type`,`commision_type`,`commission_percentage`,`commision_amount`
                ,`merchant_id`,`vendor_name`,`vendor_code`,`email_id`,`mobile`,`address`,`city`,`state`,`zipcode`,`pan`,`adhar_card`,`gst_number`,`bank_holder_name`,`bank_account_no`,
                `bank_name`,`ifsc_code`,`account_type`,`status`,`created_by`,`created_date`,`last_update_by`)
VALUES(:bulk_id,:online_pg_settlement,:settlement_type,:commision_type,:commission_percentage,:commision_amount,:merchant_id,:name,:vendor_code,:email,:mobile,:address,:city,
:state,:zipcode,:pan,:adhar_card,:gst,:bank_holder_name, :bank_account_no,:bank_name ,:ifsc_code ,:account_type, :status,:user_id,CURRENT_TIMESTAMP(),:user_id);";

            $params = array(
                ':bulk_id' => $bulk_id, ':online_pg_settlement' => $online_settlement, ':settlement_type' => $settlement_type,
                ':commision_type' => $commision_type, ':commission_percentage' => $commision_percent, ':commision_amount' => $commision_amount,
                ':merchant_id' => $merchant_id, ':name' => $data['vendor_name'], ':vendor_code' => $data['vendor_code'], ':email' => $data['email'], ':mobile' => $data['mobile'],
                ':address' => $data['address'], ':city' => $data['city'], ':state' => $db_state, ':zipcode' => $data['zipcode'],
                ':pan' => $data['pan'], ':adhar_card' => $data['adhar_card'], ':gst' => $data['gst'], ':bank_holder_name' => $data['account_holder_name'],
                ':bank_account_no' => $data['account_number'], ':bank_name' => $data['bank_name'],
                ':ifsc_code' => $data['ifsc_code'], ':account_type' => $data['account_type'], ':status' => $status, ':user_id' => $user_id
            );
            $this->db->exec($sql, $params);
            return $this->db->lastInsertId();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E295]Error while creating Staging vendor Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function createStagingBeneficiary($bulk_id, $merchant_id, $data, $status, $user_id)
    {
        try {
            $db_state = $this->getState($data['state']);
            $sql = "INSERT INTO `staging_beneficiary`(type,bulk_id,`merchant_id`,`name`,`beneficiary_code`,`email_id`,`mobile`,`address`,`city`,`state`,`zipcode`,`bank_account_no`,
                `ifsc_code`,`upi`,`status`,`created_by`,`created_date`,`last_update_by`)
VALUES(:type,:bulk_id,:merchant_id,:name,:beneficiary_code,:email,:mobile,:address,:city,
:state,:zipcode,:bank_account_no,:ifsc_code,:upi ,:status,:user_id,CURRENT_TIMESTAMP(),:user_id);";
            $params = array(
                ':bulk_id' => $bulk_id, ':type' => $data['type'], ':merchant_id' => $merchant_id, ':name' => $data['name'], ':beneficiary_code' => $data['beneficiary_code'], ':email' => $data['email'], ':mobile' => $data['mobile'], ':address' => $data['address'], ':city' => $data['city'], ':state' => $db_state, ':zipcode' => $data['zipcode'],
                ':bank_account_no' => $data['account_number'], ':ifsc_code' => $data['ifsc_code'],':upi' => $data['upi'], ':status' => $status, ':user_id' => $user_id
            );
            $this->db->exec($sql, $params);
            return $this->db->lastInsertId();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E295]Error while creating Staging vendor Error: ' . $e->getMessage());
            echo $e->getMessage();
        }
    }
}

new SaveStagingVendor();
