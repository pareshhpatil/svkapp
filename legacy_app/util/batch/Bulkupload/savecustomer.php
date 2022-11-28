<?php

include_once('bulkupload.php');

class SaveCustomer extends BulkUpload
{

    function __construct()
    {
        parent::__construct();
        $this->saving();
    }

    function getBulkCustomer($bulk_id, $merchant_id)
    {
        try {
            $sql = "call get_staging_customer_list(:bulk_id,:merchant_id,'')";
            $params = array(':bulk_id' => $bulk_id, ':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            return $rows;
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__CLASS__, '[E2]Error while get gebulkuploadlist Error: ' . $e->getMessage());
        }
    }

    function getcustomColumnValues($customer_id)
    {
        try {
            $sql = "select v.value,v.column_id,m.column_datatype,v.customer_id from staging_customer_column_values v inner join customer_column_metadata m on m.column_id=v.column_id where v.customer_id=:customer_id";
            $params = array(':customer_id' => $customer_id);
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            $column = array();
            $value = array();
            foreach ($rows as $row) {
                $column[] = $row['column_id'];
                $value[] = $row['value'];
                $datatype[] = $row['column_datatype'];
            }
            return array('column_id' => $column, 'value' => $value, 'datatype' => $datatype);
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__CLASS__, '[E3]Error while get custom column values ' . $customer_id . ' Error: ' . $e->getMessage());
        }
    }

    function saving()
    {
        try {
            require_once MODEL . 'merchant/CustomerModel.php';
            $customer = new CustomerModel();
            $bulkuploadlist = $this->getbulkuploadlist(4, 2);
            foreach ($bulkuploadlist as $bulkUpload) {

                $merchant_id = $bulkUpload['merchant_id'];
                $this->updateBulkUploadStatus($bulkUpload['bulk_upload_id'], 8);
                $merchant_setting = $this->common->getSingleValue('merchant_setting', 'merchant_id', $merchant_id);
                $auto_generate = $merchant_setting['customer_auto_generate'];

                $bulk_customerList = $this->getBulkCustomer($bulkUpload['bulk_upload_id'], $merchant_id);
                $count = 0;
                $is_failed = 0;
                $this->logger->info(__CLASS__, 'Saving customer initiate bulk_id is : ' . $bulkUpload['bulk_upload_id']);

                $this->db->begin_Transaction();
                $row = 2;
                $errors = array();
                foreach ($bulk_customerList as $info) {
                    if ($auto_generate == 1) {
                        $customer_code = $customer->getCustomerCode($merchant_id);
                    } else {
                        $customer_code = $info['customer_code'];
                    }
                    $columnValues = $this->getcustomColumnValues($info['customer_id']);
                    $res = $customer->isExistCustomerCode($merchant_id, $customer_code);
                    if ($res != FALSE) {
                        $error = array();
                        $error['customer_code'] = array('Customer code', 'Customer code already exist.');
                        $error['row'] = $row;
                        $errors[] = $error;
                        $is_failed = 1;
                    }
                    if ($is_failed == 0) {
                        $result = $customer->saveCustomer(
                            $merchant_id,
                            $merchant_id,
                            $customer_code,
                            $info['first_name'],
                            $info['last_name'],
                            $info['email'],
                            $info['mobile'],
                            $info['address'],
                            $info['address2'],
                            $info['city'],
                            $info['state'],
                            $info['zipcode'],
                            $columnValues['column_id'],
                            $columnValues['value'],
                            $bulkUpload['bulk_upload_id'],
                            $info['password'],
                            $info['gst'],
                            $info['company_name'],
                            $info['country']
                        );
                        if ($result['message'] != 'success') {
                            $is_failed = 1;
                            $this->logger->error(__CLASS__, '[E5]Error while saving customer customer id: ' . $info['customer_id'] . ' Bulk id: ' . $bulkUpload['bulk_upload_id'] . '  Error: ' . $result);
                        } else {
                            foreach ($columnValues['datatype'] as $key => $dtp) {
                                if ($dtp == 'stb') {
                                    $boxs = explode(',', $columnValues['value'][$key]);
                                    $customer->saveCustomerSettopBox($boxs, $result['customer_id'], $merchant_id, 'System');
                                }
                            }
                        }
                    }
                    $count++;
                    $row++;
                }
                if ($is_failed == 0) {
                    $this->logger->info(__CLASS__, 'Bulk customer saved sucessfully bulk id : ' . $bulkUpload['bulk_upload_id'] . ' Total customer ' . $count . '');
                    $this->db->commit();
                    $this->updateBulkUploadStatus($bulkUpload['bulk_upload_id'], 5);
                } else {
                    $this->db->rollback();
                    $this->updateBulkUploadStatus($bulkUpload['bulk_upload_id'], 1, json_encode($errors));
                    $this->moveExcelFile($merchant_id, 'staging', $bulkUpload['system_filename']);
                }
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__CLASS__, '[E6]Error while saving customer ' . $bulkUpload['bulk_upload_id'] . ' Error: ' . $e->getMessage());
        }
    }
}

new SaveCustomer();

include_once('savestagingfranchise.php');
include_once('savefranchise.php');
include_once('savetransaction.php');
include_once('savetransfer.php');
include_once('savevendor.php');
