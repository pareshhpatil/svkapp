<?php

class GstModel extends Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function getBulkuploadList($merchant_id, $type)
    {
        try {
            $sql = "select bulk_upload_id,sub_type,merchant_id,merchant_filename,total_rows,system_filename,status,bulk_upload.created_date,config_value from bulk_upload inner join config on bulk_upload.status = config.config_key where merchant_id=:merchant_id and config.config_type='bulk_upload_status' and status in (1,2,3,4,5,8) and type=:type order by bulk_upload_id desc";
            $params = array(':merchant_id' => $merchant_id, ':type' => $type);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E254]Error while fetching bank list list Error: for merchant id[' . $merchant_id . ']  ' . $e->getMessage());
        }
    }

    public function getGSTR1Details($merchant_id)
    {
        try {
            $sql = "select distinct fp,gstin from iris_r1_upload where merchant_id=:merchant_id;";
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E254]Error while fetching bank list list Error: for merchant id[' . $merchant_id . ']  ' . $e->getMessage());
        }
    }

    public function getGSTInvoiceDetails($merchant_id)
    {
        try {
            $sql = "select distinct fp,ft,gstin from iris_invoice where merchant_id=:merchant_id and is_active=1;";
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E254]Error while fetching bank list list Error: for merchant id[' . $merchant_id . ']  ' . $e->getMessage());
        }
    }

    public function getGstInvoiceList($merchant_id, $gst_number, $date)
    {
        try {
            $sql = "select * from iris_invoice where merchant_id=:merchant_id and DATE_FORMAT(idt,'%Y-%m')= '" . $date . "' and is_active=1 and gstin=:gst_number";
            $params = array(':merchant_id' => $merchant_id, ':gst_number' => $gst_number);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E254]Error while fetching bank list list Error: for merchant id[' . $merchant_id . ']  ' . $e->getMessage());
        }
    }

    public function getExpenseDetail($merchant_id, $date, $gst_number)
    {
        try {
            $sql = "select e.*,v.vendor_name,v.state,v.gst_number from expense e inner join vendor v on e.vendor_id=v.vendor_id where e.merchant_id=:merchant_id and e.gst_number=:gst_number and DATE_FORMAT(bill_date,'%Y-%m')= '" . $date . "' and e.is_active=1 and (igst_amount>0 or cgst_amount>0)";
            $params = array(':merchant_id' => $merchant_id, ':gst_number' => $gst_number);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E254]Error while fetching bank list list Error: for merchant id[' . $merchant_id . ']  ' . $e->getMessage());
        }
    }

    public function getNotesDetail($merchant_id, $date)
    {
        try {
            $sql = "select e.*,v.customer_code,v.first_name,v.last_name,v.state,v.gst_number from credit_debit_note e inner join customer v on e.customer_id=v.customer_id where e.merchant_id=:merchant_id and DATE_FORMAT(date,'%Y-%m')= '" . $date . "' and e.is_active=1 and (igst_amount>0 or cgst_amount>0)";
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E254]Error while fetching bank list list Error: for merchant id[' . $merchant_id . ']  ' . $e->getMessage());
        }
    }

    public function getR1Details($merchant_id)
    {
        try {
            $sql = "select distinct fp,gstin from iris_r1_upload where merchant_id=:merchant_id and status=1;";
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E254]Error while fetching bank list list Error: for merchant id[' . $merchant_id . ']  ' . $e->getMessage());
        }
    }

    public function get3BSummary($merchant_id, $gstin, $fp, $dty)
    {
        try {
            $sql = "select i.gstin,sum(id.txval) as taxable,sum(id.iamt) as igst,sum(id.camt) as cgst,sum(id.samt) as sgst from iris_invoice_detail id inner join iris_invoice i on i.id=id.invoice_id where i.gstin=:gstin and fp=:fp and dty=:dty and merchant_id=:merchant_id and i.is_active=1 and id.is_active=1";
            $params = array(':merchant_id' => $merchant_id, ':gstin' => $gstin, ':dty' => $dty, ':fp' => $fp);
            $this->db->exec($sql, $params);
            $list = $this->db->single();
            return $list;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E254]Error while fetching bank list list Error: for merchant id[' . $merchant_id . ']  ' . $e->getMessage());
        }
    }

    public function get3BPurchaseSummary($merchant_id, $fp, $gstin)
    {
        try {
            $sql = "select sum(id.igst_amount) as igst,sum(id.cgst_amount) as cgst,sum(id.sgst_amount) as sgst from expense_detail id inner join expense i on i.expense_id=id.expense_id where DATE_FORMAT(bill_date,'%m%Y')=:fp and merchant_id=:merchant_id and i.is_active=1 and i.gst_number=:gstin";
            $params = array(':merchant_id' => $merchant_id, ':fp' => $fp, ':gstin' => $gstin);
            $this->db->exec($sql, $params);
            $list = $this->db->single();
            return $list;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E254]Error while fetching bank list list Error: for merchant id[' . $merchant_id . ']  ' . $e->getMessage());
        }
    }

    public function get3BDetail($merchant_id, $gstin, $fp, $dty)
    {
        try {
            $state = substr($gstin, 0, 2);
            $sql = "select i.gstin,i.pos,sum(id.txval) as taxable,sum(id.iamt) as igst,sum(id.camt) as cgst,sum(id.samt) as sgst from iris_invoice_detail id inner join iris_invoice i on i.id=id.invoice_id where i.gstin=:gstin and fp=:fp and dty=:dty and merchant_id=:merchant_id and invTyp='B2CS'  and  i.is_active=1 and  id.is_active=1 and i.pos<>" . $state . " group by i.pos";
            $params = array(':merchant_id' => $merchant_id, ':gstin' => $gstin, ':dty' => $dty, ':fp' => $fp);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            $array = array();
            foreach ($list as $row) {
                $array[$row['pos']] = $row;
            }
            return $array;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E254]Error while fetching bank list list Error: for merchant id[' . $merchant_id . ']  ' . $e->getMessage());
        }
    }

    public function getInvoices($merchant_id, $gstin, $from_date, $to_date, $type = "ALL")
    {
        try {
            $sql = "select * from iris_invoice where merchant_id=:merchant_id and gstin=:gstin and idt>=:from_date and idt<=:to_date and is_active=1 and dty='RI'";

            if ($type != "ALL") {
                $sql .= " and invTyp='" . $type . "'";
            }
            $params = array(':merchant_id' => $merchant_id, ':gstin' => $gstin, ':from_date' => $from_date, ':to_date' => $to_date);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E254]Error while fetching bank list list Error: for merchant id[' . $merchant_id . ']  ' . $e->getMessage());
        }
    }

    public function getNotes($merchant_id, $gstin, $from_date, $to_date, $type = "ALL")
    {
        try {
            $sql = "select * from iris_invoice where merchant_id=:merchant_id and gstin=:gstin and ntDt>=:from_date and ntDt<=:to_date and is_active=1 and dty<>'RI'";
            if ($type != "ALL") {
                $sql .= " and invTyp='" . $type . "'";
            }
            $params = array(':merchant_id' => $merchant_id, ':gstin' => $gstin, ':from_date' => $from_date, ':to_date' => $to_date);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E254]Error while fetching bank list list Error: for merchant id[' . $merchant_id . ']  ' . $e->getMessage());
        }
    }

    public function getGSTDetails($merchant_id)
    {
        try {
            $sql = "select gst_number as gstin,gst_number,company_name,gsp_id  from merchant_billing_profile where merchant_id=:merchant_id and is_active=1 and gsp_id>0";
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E254]Error while fetching bank list list Error: for merchant id[' . $merchant_id . ']  ' . $e->getMessage());
        }
    }

    public function saveR1Upload($invoice_ids, $count, $merchant_id, $gstin, $fp, $type = 'All')
    {
        try {
            $sql = "INSERT INTO `iris_r1_upload`(`merchant_id`,`total_invoices`,`fp`,`gstin`,`invoice_ids`,`type`,`created_by`,`created_date`,`last_update_by`)"
                . "VALUES(:merchant_id,:count,:fp,:gstin,:invoice_ids,:type,:merchant_id,CURRENT_TIMESTAMP(),:merchant_id);";
            $params = array(':merchant_id' => $merchant_id, ':count' => $count, ':invoice_ids' => $invoice_ids, ':gstin' => $gstin, ':fp' => $fp, ':type' => $type);
            $this->db->exec($sql, $params);
            return $this->db->lastInsertId();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E254]Error while fetching bank list list Error: for merchant id[' . $merchant_id . ']  ' . $e->getMessage());
        }
    }

    public function saveBulkUpload($merchant_filename, $system_filename, $merchant_id, $gstin, $fp)
    {
        try {
            $sql = "INSERT INTO `iris_3b_upload`(`merchant_id`,`merchant_filename`,`system_filename`,`fp`,`gstin`,`created_by`,`created_date`,`last_update_by`)"
                . "VALUES(:merchant_id,:merchant_filename,:system_filename,:fp,:gstin,:merchant_id,CURRENT_TIMESTAMP(),:merchant_id);";
            $params = array(':merchant_id' => $merchant_id, ':merchant_filename' => $merchant_filename, ':system_filename' => $system_filename, ':gstin' => $gstin, ':fp' => $fp);
            $this->db->exec($sql, $params);
            return $this->db->lastInsertId();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E254]Error while fetching bank list list Error: for merchant id[' . $merchant_id . ']  ' . $e->getMessage());
        }
    }

    public function saveTallyExportRequest($merchant_id, $gst_number, $fromdate, $todate, $type)
    {
        try {
            $sql = "INSERT INTO `tally_export_request`(`merchant_id`,`from_date`,`to_date`,`invoice_type`,`gstin`,`created_by`,`created_date`,`last_update_by`)"
                . "VALUES(:merchant_id,:fromdate,:todate,:type,:gstin,:merchant_id,CURRENT_TIMESTAMP(),:merchant_id);";
            $params = array(':merchant_id' => $merchant_id, ':gstin' => $gst_number, ':fromdate' => $fromdate, ':todate' => $todate, ':type' => $type);
            $this->db->exec($sql, $params);
            return $this->db->lastInsertId();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E254]Error while fetching bank list list Error: for merchant id[' . $merchant_id . ']  ' . $e->getMessage());
        }
    }

    public function uploadExcel($image_file, $merchant_id, $gst, $fp)
    {
        try {
            $merchant_filename = basename($image_file['name']);
            $merchant_filename = preg_replace('/[^\p{L}\p{N}\s]/u', '', $merchant_filename);
            $merchant_filename = str_replace(' ', '_', $merchant_filename);
            $filename = $image_file['name'];
            $ext = substr($filename, strrpos($filename, '.') + 1);
            $system_filename = str_replace($ext, '', $merchant_filename) . '_' . time() . rand(20, 25) . '.' . $ext;
            $newname = UTIL . 'batch/gst/' . $system_filename;

            $filenamee = 'uploads/Excel/' . $merchant_id . '';
            if (file_exists($filenamee)) {
            } else {
                mkdir($filenamee, 0755);
                mkdir($filenamee . '/error', 0755);
                mkdir($filenamee . '/staging', 0755);
                mkdir($filenamee . '/deleted', 0755);
            }


            $newname2 = 'uploads/Excel/' . $merchant_id . '/staging/' . $system_filename;
            if ((move_uploaded_file($image_file['tmp_name'], $newname))) {
                if ((copy($newname, $newname2))) {
                }
                return $this->saveBulkUpload($merchant_filename, $system_filename, $merchant_id, $gst, $fp);
            } else {

                SwipezLogger::error(__CLASS__, '[E251]Error while uploading excel sheet Error: for merchant id [' . $merchant_id . '] ');
                $this->setGenericError();
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E252]Error while uploading excel Error: for merchant id[' . $merchant_id . '] ' . $e->getMessage());
        }
    }

    public function getDocumentInvoiceDetails($merchant_id, $gst_number, $fp, $type, $ids = null)
    {
        try {
            if ($type == 'RI') {
                $sql = "select distinct inum as inv_number from iris_invoice where merchant_id=:merchant_id and gstin=:gst_number and is_active=1 and dty='RI' __SEARCH__ order by inum";
            } else if ($type == 'C') {
                $sql = "select distinct ntNum as inv_number from iris_invoice where merchant_id=:merchant_id and gstin=:gst_number and is_active=1 and dty='C' __SEARCH__ order by ntNum";
            } else if ($type == 'D') {
                $sql = "select distinct ntNum as inv_number from iris_invoice where merchant_id=:merchant_id and gstin=:gst_number and is_active=1 and dty='D' __SEARCH__ order by ntNum";
            }

            if ($ids == null) {
                $sql = str_replace('__SEARCH__', " and fp='" . $fp . "'", $sql);
            } else {
                $ids = str_replace('[', '(', $ids);
                $ids = str_replace(']', ')', $ids);
                $ids = str_replace('"', '', $ids);
                $sql = str_replace('__SEARCH__', " and id in " . $ids, $sql);
            }

            $params = array(':merchant_id' => $merchant_id, ':gst_number' => $gst_number);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E254]Error while fetching bank list list Error: for merchant id[' . $merchant_id . ']  ' . $e->getMessage());
        }
    }

    function saveInvoiceDocument($list, $merchant_id, $gst_number, $fp)
    {
        try {
            $inv = 0;
            $cr = 0;
            $db = 0;
            foreach ($list as $row) {
                if ($row['docNum'] == 1) {
                    $inv = $inv + 1;
                    $srNo = $inv;
                } else if ($row['docNum'] == 5) {
                    $cr = $cr + 1;
                    $srNo = $cr;
                } else if ($row['docNum'] == 4) {
                    $db = $db + 1;
                    $srNo = $db;
                }
                $sql = "INSERT INTO `iris_invoice_document`(`merchant_id`,`docNum`,`srNo`,`docTyp`,`from`,`to`,`total_no`,`cancel`,`netIssue`,`gstin`,`fp`,`ft`,`status`,`created_by`,`created_date`,`last_update_by`)"
                    . "VALUES(:merchant_id,:docNum,:srNo,:docTyp,:from,:to,:total_no,:cancel,:netIssue,:gstin,:fp,'GSTR1',0,:merchant_id,CURRENT_TIMESTAMP(),:merchant_id);";
                $params = array(':docNum' => $row['docNum'], ':srNo' => $srNo, ':docTyp' => $row['docTyp'], ':from' => $row['from_serial'], ':to' => $row['to_serial'], ':total_no' => $row['total'], ':cancel' => $row['canceled'], ':netIssue' => $row['total'] - $row['canceled'], ':merchant_id' => $merchant_id, ':gstin' => $gst_number, ':fp' => $fp);
                $this->db->exec($sql, $params);
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__METHOD__, ' Error: ' . $e->getMessage());
        }
    }

    function deleteInvoiceDocument($merchant_id, $gst_number, $fp)
    {
        try {
            $sql = "update iris_invoice_document set is_active=0 where merchant_id=:merchant_id and gstin=:gstin and fp=:fp";
            $params = array(':merchant_id' => $merchant_id, ':gstin' => $gst_number, ':fp' => $fp);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__METHOD__, ' Error: ' . $e->getMessage());
        }
    }

    function getStatecode($value)
    {
        try {
            $sql = "SELECT config_key FROM config where config_value=:value and config_type='gst_state_code'";
            $params = array(':value' => trim($value));
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row['config_key'];
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E2]Error while get gebulkuploadlist Error: ' . $e->getMessage());
        }
    }

    public function updateGSTInvoice($invoice_id, $merchant_id, $data)
    {
        try {
            if (isset($data['splyTy'])) {
                $splyTy = $data['splyTy'];
            } else {
                if ($data['bill_from'] != $data['ship_to']) {
                    $splyTy = 'INTER';
                } else {
                    $splyTy = 'INTRA';
                }
            }
            if (isset($data['pos'])) {
                $pos = $data['pos'];
            } else {
                $pos = $this->getStatecode($data['ship_to']);
                if (strlen($pos) == 1) {
                    $pos = '0' . $pos;
                }
            }
            $data['ctpy'] = ($data['invTyp'] == 'B2B') ? 'R' : 'U';
            $date = new DateTime($data['invoice_date']);

            $fp = $date->format('mY');
            $ft = 'GSTR1';
            $sql = "update `iris_invoice`set status=1,is_active=1,`merchant_id`=:merchant_id,`invTyp`=:invTyp,`splyTy`=:splyTy,`pdt`=:pdt,`ctpy`=:ctpy,"
                . "`ctin`=:ctin,`cname`=:cname,`inum`=:inum,`idt`=:idt,ntDt=:ntDt,`val`=:val,`pos`=:pos,`fy`=:fy,`gstin`=:gstin,`fp`=:fp,`ft`=:ft,`last_update_by`=:merchant_id where id=:invoice_id";

            $params = array(
                ':merchant_id' => $merchant_id, ':invTyp' => $data['invTyp'], ':splyTy' => $splyTy,
                ':pdt' => $data['invoice_date'], ':ntDt' => $data['ntDt'],
                ':ctpy' => $data['ctpy'], ':ctin' => $data['ctin'], ':cname' => $data['cname'], ':inum' => $data['invoice_number'],
                ':idt' => $data['invoice_date'], ':val' => $data['invoice_amount'], ':pos' => $pos,
                ':fy' => '', ':gstin' => $data['seller_gstin'], ':fp' => $fp, ':ft' => $ft, ':invoice_id' => $invoice_id
            );
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            dd($e);
            Sentry\captureException($e);

            SwipezLogger::error(__METHOD__, '[E295]Error while creating Staging Gst Invoice Error: ' . $e->getMessage());
        }
    }

    function updateInvoiceDetail($id, $data, $merchant_id)
    {
        if (strlen($data['description']) > 29) {
            $data['description'] = substr($data['description'], 0, 26) . '...';
        }
        $sql = "update `iris_invoice_detail` set `sval`=:sval,is_active=1,`desc`=:desc,hsnSc=:hsnSc,`qty`=:qty,`txval`=:txval,`irt`=:irt,`iamt`=:iamt,`crt`=:crt,`camt`=:camt,`srt`=:srt,`samt`=:samt
,`last_update_by`=:merchant_id where id=:id";
        $params = array(
            ':id' => $id, ':sval' => $data['invoice_amount'],
            ':desc' => $data['description'], ':hsnSc' => $data['sac'], ':qty' => $data['qty'], ':txval' => $data['exclusive_tax'], ':irt' => $data['igst_rate'], ':iamt' => $data['igst_tax'],
            ':crt' => $data['cgst_rate'], ':camt' => $data['cgst_tax'], ':srt' => $data['sgst_rate'], ':samt' => $data['sgst_tax'], ':merchant_id' => $merchant_id
        );
        $this->db->exec($sql, $params);
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

            SwipezLogger::error(__CLASS__, '[E2]Error while get gebulkuploadlist Error: ' . $e->getMessage());
        }
    }

    function getInvoiceDocuments($merchant_id, $fp, $gst_number)
    {
        try {
            $sql = "select * from iris_invoice_document where merchant_id=:merchant_id and fp=:fp and gstin=:gstin and is_active=1";
            $params = array(':merchant_id' => $merchant_id, ':fp' => $fp, ':gstin' => $gst_number);
            $this->db->exec($sql, $params);
            $row = $this->db->resultset();
            return $row;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__METHOD__, '[E2]Error while get gebulkuploadlist Error: ' . $e->getMessage());
        }
    }

    function getInvoiceDetail($invoice_id)
    {
        try {
            $sql = "select * from iris_invoice_detail where invoice_id=:invoice_id and is_active=1";
            $params = array(':invoice_id' => $invoice_id);
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            return $rows;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__METHOD__, '[E2]Error while get gebulkuploadlist Error: ' . $e->getMessage());
        }
    }

    function getreportb2b($merchant_id, $gst_number, $from_date, $to_date)
    {
        try {
            $sql = "SELECT ctin as GSTIN_of_Recipient,inum as invoice_number,idt as invoice_date,val as invoice_value,concat(c.config_key,'-',c.config_value) place_of_supply ,dty as invoice_type,max(irt) as rate,max(crt) as cgstrt,sum(txval) taxable_value,sum(iamt) IGST,sum(camt) CGST,sum(samt) SGST FROM iris_invoice i inner join  iris_invoice_detail d on d.invoice_id=i.id 
left outer join config c on c.config_key=i.pos and c.config_type='gst_state_code' where merchant_id=:merchant_id and invTyp='B2B' and gstin=:gstin and ((idt>=:from_date and idt<=:to_date and dty='RI') or (ntDt>=:from_date and ntDt<=:to_date and dty='C') )  group by d.invoice_id";
            $params = array(':merchant_id' => $merchant_id, ':gstin' => $gst_number, ':from_date' => $from_date, ':to_date' => $to_date);
            $this->db->exec($sql, $params);

            $rows = $this->db->resultset();
            return $rows;
        } catch (Exception $e) {
            Sentry\captureException($e);
            echo $e->getMessage();
            die();

            SwipezLogger::error(__METHOD__, '[E2]Error while get gebulkuploadlist Error: ' . $e->getMessage());
        }
    }

    function getreportb2clarge($merchant_id, $gst_number, $from_date, $to_date, $gstlargelimit)
    {
        try {
            $sql = "SELECT inum as invoice_number,idt as invoice_date,val as invoice_value,concat(c.config_key,'-',c.config_value) place_of_supply ,dty as invoice_type,max(irt) as rate,max(crt) as cgstrt,sum(txval) taxable_value,sum(iamt) IGST,sum(camt) CGST,sum(samt) SGST ,gstin as GSTIN FROM iris_invoice i inner join  iris_invoice_detail d on d.invoice_id=i.id and d.is_active=1
left outer join config c on c.config_key=i.pos and c.config_type='gst_state_code' where merchant_id=:merchant_id and i.is_active=1 and invTyp='B2CS' and gstin=:gstin and ((idt>=:from_date and idt<=:to_date and dty='RI') or (ntDt>=:from_date and ntDt<=:to_date and dty='C') ) and val>:large_limit  group by d.invoice_id";
            $params = array(':merchant_id' => $merchant_id, ':gstin' => $gst_number, ':from_date' => $from_date, ':to_date' => $to_date, ':large_limit' => $gstlargelimit);
            $this->db->exec($sql, $params);

            $rows = $this->db->resultset();
            return $rows;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__METHOD__, '[E2]Error while get gebulkuploadlist Error: ' . $e->getMessage());
        }
    }

    function getreporthsnsummary($merchant_id, $gst_number, $from_date, $to_date, $dty)
    {
        try {
            $datecol = 'ntDt';
            if ($dty == 'RI') {
                $datecol = 'idt';
            }
            $sql = "SELECT hsnSc as hsn,`desc` as `description`,uqc,sum(qty) total_quantity,sum(sval) as total_value,sum(txval) taxable_value,sum(iamt) IGST,sum(camt) CGST,sum(samt) SGST  FROM iris_invoice i inner join  iris_invoice_detail d on d.invoice_id=i.id and d.is_active=1
left outer join config c on c.config_key=i.pos and c.config_type='gst_state_code' where merchant_id=:merchant_id and i.is_active=1 and dty='" . $dty . "' and gstin=:gstin and " . $datecol . ">=:from_date and " . $datecol . "<=:to_date  group by d.hsnsc";
            $params = array(':merchant_id' => $merchant_id, ':gstin' => $gst_number, ':from_date' => $from_date, ':to_date' => $to_date);
            $this->db->exec($sql, $params);

            $list = $this->db->resultset();
            $array = array();
            foreach ($list as $row) {
                $hsn = ($row['hsn'] == '') ? 'NA' : $row['hsn'];
                $array[$hsn] = $row;
            }
            return $array;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__METHOD__, '[E2]Error while get gebulkuploadlist Error: ' . $e->getMessage());
        }
    }

    function getreportb2csmall($merchant_id, $gst_number, $from_date, $to_date, $gstlargelimit, $dty)
    {
        try {
            $date_col = 'ntDt';
            if ($dty == 'RI') {
                $date_col = 'idt';
            }
            if ($gstlargelimit > 0) {
                $invtype = " and invTyp='B2CS' and val<=" . $gstlargelimit;
            } else {
                $invtype = '';
            }
            $sql = "select concat(c.config_key,'-',c.config_value) place_of_supply,i.pos,sum(id.txval) as taxable,sum(id.iamt) as igst,sum(id.camt) as cgst,sum(id.samt) as sgst,sum(i.val) as total,i.gstin from iris_invoice_detail id inner join iris_invoice i on i.id=id.invoice_id left outer join config c on c.config_key=i.pos and c.config_type='gst_state_code' where i.gstin=:gstin and " . $date_col . ">=:from_date  and " . $date_col . "<=:to_date and dty=:dty " . $invtype . " and merchant_id=:merchant_id  and  i.is_active=1 and id.is_active=1 group by i.pos";
            $params = array(':merchant_id' => $merchant_id, ':gstin' => $gst_number, ':dty' => $dty, ':from_date' => $from_date, ':to_date' => $to_date);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            $array = array();
            foreach ($list as $row) {
                $array[$row['pos']] = $row;
            }
            return $array;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__METHOD__, '[E2]Error while get gebulkuploadlist Error: ' . $e->getMessage());
        }
    }

    public function createGSTInvoice($merchant_id, $payment_request_id, $data)
    {
        try {
            $bulk_id = 0;
            if ($data['bill_from'] != $data['ship_to']) {
                $splyTy = 'INTER';
            } else {
                $splyTy = 'INTRA';
            }
            $dst = 'O';
            $rchrg = 'N';
            $pgst = 'N';
            $prs = 'N';
            $odnum = '000';
            $refnum = time() . rand(1, 96669);
            $pos = $this->getStatecode($data['ship_to']);
            if (strlen($pos) == 1) {
                $pos = '0' . $pos;
            }
            if ($data['dty'] == 'C') {
                $date = new DateTime($data['note_date']);
            } else {
                $date = new DateTime($data['invoice_date']);
            }
            $fp = $date->format('mY');
            $ft = 'GSTR1';
            $sql = "INSERT INTO `iris_invoice`(`merchant_id`,`payment_request_id`,`invTyp`,`splyTy`,`dst`,`refnum`,`pdt`,`ctpy`,`ctin`,`cname`,"
                . "`ntNum`,`ntDt`,`inum`,`idt`,`val`,`pos`,`rchrg`,`fy`,`dty`,`rsn`,`pgst`,`prs`,`odnum`,`gen2`,`gen7`,`gen8`,`gen10`,`gen11`,"
                . "`gen12`,`gen13`,`gstin`,`fp`,`ft`,`bulk_id`,`status`,`created_by`,`created_date`,`last_update_by`)"
                . "VALUES(:merchant_id,:payment_request_id,:invTyp,:splyTy,:dst,:refnum,:pdt,:ctpy,:ctin,:cname,"
                . ":ntnum,:ntdt,:inum,:idt,:val,:pos,:rchrg,:fy,:dty,:rsn,:pgst,:prs,:odnum,"
                . ":gen2,:gen7,:gen8,:gen10,:gen11,:gen12,:gen13,:gstin,:fp,:ft,:bulk_id,1,:merchant_id,CURRENT_TIMESTAMP(),:merchant_id);";

            $params = array(
                ':merchant_id' => $merchant_id, ':payment_request_id' => $payment_request_id, ':invTyp' => $data['invTyp'], ':splyTy' => $splyTy,
                ':dst' => $dst, ':refnum' => $refnum, ':pdt' => $data['invoice_date'],
                ':ctpy' => $data['ctpy'], ':ctin' => $data['ctin'], ':cname' => $data['cname'],
                ':ntnum' => $data['note_number'], ':ntdt' => $data['note_date'],
                ':inum' => $data['invoice_number'],
                ':idt' => $data['invoice_date'], ':val' => $data['invoice_amount'], ':pos' => $pos, ':rchrg' => $rchrg,
                ':fy' => '', ':dty' => $data['dty'], ':rsn' => '', ':pgst' => $pgst,
                ':prs' => $prs, ':odnum' => $odnum, ':gen2' => $data['gen2'], ':gen7' => $data['gen7'], ':gen8' => $data['gen8'], ':gen10' => $data['gen10'],
                ':gen11' => $data['gen11'], ':gen12' => $data['gen12'], ':gen13' => $data['gen13'],
                ':gstin' => $data['seller_gstin'], ':fp' => $fp, ':ft' => $ft, ':bulk_id' => $bulk_id
            );
            $this->db->exec($sql, $params);
            $invoice_id = $this->db->lastInsertId();
            return $invoice_id;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__METHOD__, '[E295]Error while creating Staging Gst Invoice Error: ' . $e->getMessage());
        }
    }

    function createGSTInvoiceDetail($invoice_id, $data, $merchant_id, $num = 1)
    {
        try {
            if (strlen($data['description']) > 29) {
                $data['description'] = substr($data['description'], 0, 26) . '...';
            }
            if ($data['description'] == '') {
                $data['description'] = 'Particular';
            }
            $sql = "INSERT INTO `iris_invoice_detail`(`invoice_id`,`num`,`sval`,`ty`,`hsnSc`,`desc`,`uqc`,`qty`,`txval`,`irt`,`iamt`,`crt`,"
                . "`camt`,`srt`,`samt`,`csrt`,`csamt`,`txp`,`disc`,`adval`,`rt`,`product_code`,`created_by`,`created_date`,`last_update_by`)"
                . "VALUES(:invoice_id,:num,:sval,:ty,:hsnSc,:desc,:uqc,:qty,:txval,:irt,:iamt,:crt,:camt,:srt,:samt,:csrt,:csamt"
                . ",:txp,:disc,:adval,:rt,:product_code,:merchant_id,CURRENT_TIMESTAMP(),:merchant_id);";
            $params = array(
                ':invoice_id' => $invoice_id, ':num' => $num, ':sval' => $data['invoice_amount'], ':ty' => $data['ty'], ':hsnSc' => $data['sac'],
                ':desc' => $data['description'], ':uqc' => 'NOS', ':qty' => $data['qty'], ':txval' => $data['exclusive_tax'],
                ':irt' => $data['igst_rate'], ':iamt' => $data['igst_tax'],
                ':crt' => $data['cgst_rate'], ':camt' => $data['cgst_tax'], ':srt' => $data['sgst_rate'], ':samt' => $data['sgst_tax'],
                ':csrt' => 0, ':csamt' => 0, ':txp' => 'T', ':disc' => 0, ':adval' => 0, ':rt' => 0, ':product_code' => $data['product_code'], ':merchant_id' => $merchant_id
            );
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__METHOD__, '[E295]Error while creating Staging Gst Invoice Error: ' . $e->getMessage());
        }
    }

    function validateGSTstate($state, $config_array)
    {
        try {
            $state = str_replace('  ', '', $state);
            $code = $this->getGSTStatecode($state);
            if ($code != 0) {
                return $code;
            }
            $state = strtolower($state);
            if ($state == 'jammu kashmir' || $state == 'jammu & kashmir') {
                $state = 'Jammu and Kashmir';
            }
            if ($state == 'kl') {
                $state = 'Kerala';
            }

            if ($state == 'chhattisgarh') {
                $state = 'Chattisgarh';
            }
            if ($state == 'ar') {
                $state = 'Arunachal Pradesh';
            }
            if ($state == 'up') {
                $state = 'Uttar Pradesh';
            }
            if ($state == 'mp') {
                $state = 'Madhya Pradesh';
            }
            if ($state == 'tamilnadu') {
                $state = 'TAMIL NADU';
            }
            if ($state == 'daman & diu' || $state == 'daman diu') {
                $state = 'Daman and Diu';
            }
            if ($state == 'andaman nicobar islands') {
                $state = 'Andaman and Nicobar Islands';
            }
            if ($state == 'puducherry') {
                $state = 'Pondicherry';
            }
            if ($state == 'dadra & nagar haveli' || $state == 'dadra nagar haveli' || $state == 'Daman and Diu') {
                $state = 'Dadra and Nagar Haveli';
            }
            if (isset($config_array[$state])) {
                return $config_array[$state];
            } else {
                return 0;
            }
        } catch (Exception $e) {
            Sentry\captureException($e);
            $this->logger->error(__CLASS__, '[E2]Error while get gebulkuploadlist Error: ' . $e->getMessage());
        }
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
}
