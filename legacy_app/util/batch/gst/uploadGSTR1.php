<?php

include_once('../config.php');
require_once MODEL . 'CommonModel.php';

class GSTR1Invoice
{

    public $db = NULL;
    public $logger = NULL;
    public $common = NULL;
    public $encrypt = NULL;

    function __construct()
    {
        $this->db = new DBWrapper();
        $this->logger = new SwipezLogger('log4php_config.xml');
        $this->common = new CommonModel();
        $this->encrypt = new Encryption();
        $this->saving();
    }

    function getPendingBulk()
    {
        try {
            $sql = "select * from iris_r1_upload where status=4";
            $params = array();
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            return $rows;
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__CLASS__, '[E2]Error while get gebulkuploadlist Error: ' . $e->getMessage());
        }
    }

    function getPendingInvoice($ids)
    {
        try {
            $sql = "select * from iris_invoice where id in " . $ids . " and is_active=1";
            $params = array();
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            return $rows;
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__CLASS__, '[E2]Error while get gebulkuploadlist Error: ' . $e->getMessage());
        }
    }

    function getInvoiceDocuments($fp, $gst_number, $merchant_id)
    {
        try {
            $sql = "select * from iris_invoice_document where merchant_id=:merchant_id and gstin=:gstin and fp=:fp and is_active=1";
            $params = array(':merchant_id' => $merchant_id, ':gstin' => $gst_number, ':fp' => $fp);
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            return $rows;
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__CLASS__, '[E2]Error while get gebulkuploadlist Error: ' . $e->getMessage());
        }
    }

    function getInvoiceDetail($invoice_id)
    {
        try {
            $sql = "select * from iris_invoice_detail where is_active=1 and invoice_id=:invoice_id";
            $params = array(':invoice_id' => $invoice_id);
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            return $rows;
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__CLASS__, '[E2]Error while get gebulkuploadlist Error: ' . $e->getMessage());
        }
    }

    function getStatecode($value)
    {
        try {
            $sql = "SELECT config_key FROM swipez.config where config_value=:value and config_type='gst_state_code'";
            $params = array(':value' => $value);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row['config_key'];
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__CLASS__, '[E2]Error while get gebulkuploadlist Error: ' . $e->getMessage());
        }
    }

    function updateStatus($bulk_id, $status, $json)
    {
        try {
            $sql = "update iris_r1_upload set status=:status,error_json=:json where upload_id=:bulk_upload_id";
            $params = array(':status' => $status, ':bulk_upload_id' => $bulk_id, ':json' => $json);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            $this->logger->error(__CLASS__, '[E2]Error while get gebulkuploadlist Error: ' . $e->getMessage());
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

            $this->logger->error(__CLASS__, '[E2]Error while get gebulkuploadlist Error: ' . $e->getMessage());
        }
    }

    function saving()
    {
        $irisdetails = $this->getIRISGSTDetails();
        require_once UTIL . 'IRISAPI.php';
        $irisapi = new IRISAPI($irisdetails);
        $bulkuploadlist = $this->getPendingBulk();
        foreach ($bulkuploadlist as $bulklist) {
            $this->updateStatus($bulklist['upload_id'], 8, '');
            $ids = $bulklist['invoice_ids'];
            $ids = str_replace('[', '(', $ids);
            $ids = str_replace(']', ')', $ids);
            $ids = str_replace('"', '', $ids);
            $invoice_list = $this->getPendingInvoice($ids);
            $document_list = $this->getInvoiceDocuments($bulklist['fp'], $bulklist['gstin'], $bulklist['merchant_id']);
            $irisapi->deleteGSTR($bulklist['fp'], $bulklist['gstin']);
            $status = 1;
            $errors = array();
            $count = count($invoice_list) - 1;
            $json = array();
            $int = 0;
            foreach ($invoice_list as $key => $rowdata) {
                try {
                    $invoices = array();
                    $invdetail = $this->getInvoiceDetail($rowdata['id']);
                    $row = 1;
                    $invoices['invTyp'] = $rowdata['invTyp'];
                    $invoices['splyTy'] = $rowdata['splyTy'];
                    $invoices['dst'] = $rowdata['dst'];
                    $invoices['refnum'] = $rowdata['refnum'];
                    $date = new DateTime($rowdata['pdt']);
                    $invoices['pdt'] = $date->format('d-m-Y');
                    $invoices['ctpy'] = $rowdata['ctpy'];

                    

                    $invoices['ctin'] = ($rowdata['ctin'] != '') ? $rowdata['ctin'] : null;
                    $invoices['cname'] = $rowdata['cname'];
                    if (strlen($rowdata['ctin']) != 15) {
                        $invoices['invTyp'] = 'B2CS';
                        $invoices['ctpy'] = 'U';
                        $invoices['ctin'] = NULL;
                        $invoices['cname'] = NULL;
                    }
                    if ($rowdata['dty'] == 'RI') {
                        $rowdata['ntNum'] = null;
                        $rowdata['ntDt'] = null;
                    } else {
                        $date = new DateTime($rowdata['ntDt']);
                        $rowdata['ntDt'] = $date->format('d-m-Y');
                    }
                    $invoices['ntNum'] = $rowdata['ntNum'];
                    $invoices['ntDt'] = $rowdata['ntDt'];
                    if ($rowdata['inum'] == '') {
                        $invoices['inum'] = null;
                        $invoices['idt'] = null;
                    } else {
                        $invoices['inum'] = trim($rowdata['inum']);
                        $date = new DateTime($rowdata['idt']);
                        $invoices['idt'] = $date->format('d-m-Y');
                    }

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
                    $is_cgst = 0;
                    foreach ($invdetail as $invdet) {
                        if ($invdet['txval'] > 0) {
                            $itemDetails['num'] = $row;
                            $itemDetails['sval'] = $invdet['sval'];
                            $itemDetails['ty'] = $invdet['ty'];
                            $itemDetails['hsnSc'] = $invdet['hsnSc'];
                            if (strlen($invdet['desc']) > 30) {
                                $invdet['desc'] = substr($invdet['desc'], 0, 28) . '..';
                            }
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
                            if ($itemDetails['irt'] == null && $itemDetails['crt'] == null) {
                                if ($rowdata['splyTy'] == 'INTRA') {
                                    $itemDetails['crt'] = 0;
                                    $itemDetails['camt'] = 0;
                                    $itemDetails['srt'] = 0;
                                    $itemDetails['samt'] = 0;
                                } else {
                                    $itemDetails['irt'] = 0;
                                    $itemDetails['iamt'] = 0;
                                }
                            }
                            if ($itemDetails['camt'] > 0) {
                                $is_cgst = 1;
                            }
                            $invoices['itemDetails'][] = $itemDetails;
                            $row++;
                        }
                    }
                    if ($is_cgst == 1) {
                        $invoices['splyTy'] = 'INTRA';
                    }
                    $invoices['gstin'] = $bulklist['gstin'];
                    $invoices['fp'] = $bulklist['fp'];
                    $invoices['ft'] = 'GSTR1';
                    if ($invoices['val'] > 0) {
                        $json['invoices'][] = $invoices;
                        if ($int == 500 || $count == $key) {
                            $res = $irisapi->saveInvoice($rowdata['gstin'], json_encode($json));
                            $json = array();
                            $int = 0;
                            //$this->logger->info(__CLASS__, 'Response ' . json_encode($res));
                            if ($res['response']['status'] == 'SUCCESS') {
                            } else {
                                $status = 2;
                                $errors[$invoices['inum']] = $res;
                                $this->logger->error(__CLASS__, 'Error Response for ' . $rowdata['id'] . ' Response: ' . json_encode($res));
                            }
                        }
                        $int++;
                    }
                } catch (Exception $e) {
                    Sentry\captureException($e);

                    $this->logger->error(__CLASS__, '[E6]Error while saving transfer ' . $rowdata['id'] . ' Error: ' . $e->getMessage());
                }
            }
            $json = array();
            foreach ($document_list as $doc) {
                $document['dty'] = 'DD';
                $document['docNum'] = $doc['docNum'];
                $document['docTyp'] = $doc['docTyp'];
                $document['num'] = $doc['srNo'];
                $document['from'] = $doc['from'];
                $document['to'] = $doc['to'];
                $document['totnum'] = $doc['total_no'];
                $document['cancel'] = $doc['cancel'];
                $document['netIssue'] = $doc['netIssue'];
                $document['gstin'] = $doc['gstin'];
                $document['fp'] = $doc['fp'];
                $document['ft'] = 'GSTR1';
                $json['invoices'][] = $document;
                $res = $irisapi->createDocument(json_encode($json), $bulklist['gstin']);
                if ($res['response']['status'] == 'SUCCESS') {
                } else {
                    $this->logger->error(__CLASS__, 'Error Response for ' . $rowdata['id'] . ' Request: ' . json_encode($json) . ' Response: ' . json_encode($res));
                }
            }
            $this->updateStatus($bulklist['upload_id'], $status, json_encode($errors));
        }
    }
}

new GSTR1Invoice();
