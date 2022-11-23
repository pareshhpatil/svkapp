<?php

include_once ('bulkupload.php');

class SaveStagingFranchise extends BulkUpload {

    function __construct() {
        parent::__construct();
        $this->franchisesave();
    }

    public function franchisesave() {
        $bulkuploadlist = $this->getbulkuploadlist(2, 6);
        foreach ($bulkuploadlist as $bulkupload) {
            try {
                $bulk_id = $bulkupload['bulk_upload_id'];
                if (!empty($bulkupload)) {
                    $this->logger->info(__CLASS__, 'Saving Bulk upload staging franchise initiate bulk_id is : ' . $bulk_id . ' and total rows are ' . $bulkupload['total_rows']);
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
                    $post_row['franchise_name'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                    $post_row['contact_person_name'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                    $post_row['email'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                    $post_row['mobile'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                    $post_row['address'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                    $post_row['contact_person_name2'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                    $post_row['email2'] = (string) $getcolumnvalue[$rowno][$int];
                    $int++;
                    $post_row['mobile2'] = (string) $getcolumnvalue[$rowno][$int];
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
                    if ($this->onlineSettlementStatus($this->merchant_id) == 1) {
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
                    } else {
                        $post_row['settlement_type'] = 0;
                        $post_row['commision_amount'] = 0;
                        $post_row['commision_percent'] = 0;
                        $post_row['commision_type'] = 0;
                        $post_row['online_settlement'] = 0;
                    }
                    $_POSTarray[] = $post_row;
                }
            }
            $count_int = 0;
            try {
                $errorrow = 2;
                $errors = array();

                if (empty($errors)) {
                    foreach ($_POSTarray as $data) {
                        $result = $this->createStagingFranchise($bulk_id, $merchant_id, $data, 0, $merchant_id);
                        $count_int++;
                    }
                }
                if ($is_falied == 0) {
                    $this->logger->info(__CLASS__, 'Bulk staging franchise saved sucessfully bulk id : ' . $bulk_id . ' Total franchise ' . $count_int);
                    return array('status' => true);
                } else {
                    return array('status' => false, 'error' => json_encode($errors));
                }
            } catch (Exception $e) {
Sentry\captureException($e);
                
$this->logger->error(__CLASS__, '[E6]Error while saving staging franchise save excel  bulk id : ' . $bulk_id . ' Error: ' . $e->getMessage());
            }
        } catch (Exception $e) {
Sentry\captureException($e);
            
$this->logger->error(__CLASS__, '[E7]Error while uploading excel  bulk id : ' . $bulk_id . ' Error: ' . $e->getMessage());
        }
    }

    public function createStagingFranchise($bulk_id, $merchant_id, $data, $status, $user_id) {
        try {
            $commision_type = ($data['commision_type'] > 0) ? $data['commision_type'] : 0;
            $commision_percent = ($data['commision_percent'] > 0) ? $data['commision_percent'] : 0;
            $commision_amount = ($data['commision_amount'] > 0) ? $data['commision_amount'] : 0;
            $online_settlement = (isset($data['online_settlement'])) ? $data['online_settlement'] : 0;
            $settlement_type = ($data['settlement_type'] > 0) ? $data['settlement_type'] : 0;

            $sql = "INSERT INTO `staging_franchise`(`merchant_id`,`franchise_name`,`contact_person_name`,`email_id`,`mobile`,`address`,
                `contact_person_name2`,`email_id2`,`mobile2`,`bank_holder_name`,`bank_account_no`,`bank_name`,`account_type`,`ifsc_code`,
                `commission_percentage`,`commision_amount`,`online_pg_settlement`,`commision_type`,`settlement_type`,`status`,
                `bulk_id`,`created_by`,`created_date`,`last_update_by`)
                 VALUES(:merchant_id,:franchise_name,:person1,:email,:mobile,:address,:person2,:email2,:mobile2,:account_name,:account_no,
                 :bank_name,:account_type,:ifsc_code,:commision_percent,:commision_amount,:online_settlement,:commision_type,:settlement_type,:status,
                 :bulk_id,:user_id,CURRENT_TIMESTAMP(),:user_id);";

            $params = array(':merchant_id' => $merchant_id, ':franchise_name' => $data['franchise_name'], ':person1' => $data['contact_person_name'], ':email' => $data['email'], ':mobile' => $data['mobile'], ':address' => $data['address'], ':person2' => $data['contact_person_name2'], ':email2' => $data['email2'], ':mobile2' => $data['mobile2'],                ':account_name' => $data['account_holder_name'], ':account_no' => $data['account_number'], ':bank_name' => $data['bank_name'],
                ':ifsc_code' => $data['ifsc_code'], ':account_type' => $data['account_type'], ':online_settlement' => $online_settlement,
                ':settlement_type' => $settlement_type, ':commision_type' => $commision_type, ':commision_percent' => $commision_percent,
                ':commision_amount' => $commision_amount, ':status' => $status, ':bulk_id' => $bulk_id, ':user_id' => $user_id);
            $this->db->exec($sql, $params);
            return $this->db->lastInsertId();
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E295]Error while creating Staging vendor Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }
}

new SaveStagingFranchise();
