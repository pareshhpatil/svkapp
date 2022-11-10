<?php

/**
 * This class calls necessary db objects to handle payment requests and requests to payment gateway
 *
 * @author Paresh
 */
class InvoiceModel extends Model
{

    function __construct()
    {
        parent::__construct();
    }


    public function saveInvoice($merchant_id, $user_id, $customer_id, $template_id, $invoice_number, $billdate, $duedate, $expiry_date, $cyclename, $values, $ids, $narrative, $amount, $tax, $previous_dues, $last_payment, $adjustment, $late_fee, $advance, $notify_patron, $payment_request_status, $franchise_id, $vendor_id, $created_by, $request_type = 1, $payment_request_type = 1, $auto_collect_plan_id = 0, $custom_reminder = 0, $plugin_value, $billing_profile = 0, $generate_estimate_invoice = 1, $parent_request_id = 0, $currency = 'INR', $einvoice_type = null, $product_taxation_type = 1)
    {
        try {
            $ids = implode('~', $ids);
            $values = implode('~', $values);
            $sql = "call `insert_invoicevalues`(:merchant_id,:user_id,:customer_id,:invoice_number,:template_id,:values,:ids,:billdate,:duedate,:cyclename,:narrative,:amount,:tax,:previous_dues,:last_payment,:adjustment,:late_fee,:advance,:notify_patron,:payment_request_status,:franchise_id,:vendor_id,:expiry_date,:created_by,:request_type,:type,:auto_collect_plan_id,:custom_reminder,:plugin_value,:billing_profile,:generate_estimate_invoice,:parent_request_id,:currency,:einvoice_type,:product_taxation_type );";
            $params = array(
                ':merchant_id' => $merchant_id, ':user_id' => $user_id, ':customer_id' => $customer_id, ':invoice_number' => $invoice_number, ':template_id' => $template_id,
                ':values' => $values, ':ids' => $ids, ':billdate' => $billdate, ':duedate' => $duedate, ':cyclename' => $cyclename, ':narrative' => $narrative, ':amount' => $amount,
                ':tax' => $tax, ':previous_dues' => $previous_dues, ':last_payment' => $last_payment, ':adjustment' => $adjustment, ':late_fee' => $late_fee, ':advance' => $advance,
                ':notify_patron' => $notify_patron, ':payment_request_status' => $payment_request_status, ':franchise_id' => $franchise_id, ':vendor_id' => $vendor_id,
                ':expiry_date' => $expiry_date, ':created_by' => $created_by, ':request_type' => $request_type, ':type' => $payment_request_type, ':auto_collect_plan_id' => $auto_collect_plan_id,
                ':custom_reminder' => $custom_reminder, ':plugin_value' => $plugin_value, ':billing_profile' => $billing_profile, ':generate_estimate_invoice' => $generate_estimate_invoice,
                ':parent_request_id' => $parent_request_id, ':currency' => $currency, ':einvoice_type' => $einvoice_type, ':product_taxation_type' => $product_taxation_type
            );

            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if ($row['message'] != 'success') {
                throw new Exception('Merchant id ' . $merchant_id . ' Error: ' . $row['Message']);
            }
            return $row;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E101-1]Error while saving invoice Error:  for merchant id[' . $merchant_id . '] and template id [' . $template_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }


    public function updateInvoice($payment_request_id, $user_id, $customer_id, $invoice_number, $billdate, $duedate, $expiry_date, $cyclename, $values, $ids, $narrative, $amount, $tax, $previous_dues, $last_payment, $adjustment, $late_fee, $advance, $notify_patron, $payment_request_status, $franchise_id, $vendor_id, $created_by, $request_type = 1, $auto_collect_plan_id = 0, $plugin_value, $staging = 0, $billing_profile = 0, $generate_estimate_invoice = 1, $currency = 'INR', $einvoice_type = null, $product_taxation_type = 1)
    {
        try {
            if (strlen($payment_request_id) == 10) {
                $ids = implode('~', $ids);
                $values = implode('~', $values);
                $sql = "call `update_invoicevalues`(:payment_request_id,:user_id,:customer_id,:invoice_number,:values,:ids,
                :billdate,:duedate,:cyclename,:narrative,:amount,:tax,:previous_dues,:last_payment,:adjustment,:late_fee,
                :advance,:notify_patron,:payment_request_status,:franchise_id,:vendor_id,:expiry_date,:created_by,
                :request_type,:auto_collect_plan_id,:plugin_value,:staging,:billing_profile,:generate_estimate_invoice,
                :currency,:einvoice_type, :product_taxation_type);";
                $params = array(
                    ':payment_request_id' => $payment_request_id, ':user_id' => $user_id, ':customer_id' => $customer_id,
                    ':invoice_number' => $invoice_number, ':values' => $values, ':ids' => $ids, ':billdate' => $billdate, ':duedate' => $duedate,
                    ':cyclename' => $cyclename, ':narrative' => $narrative, ':amount' => $amount, ':tax' => $tax, ':previous_dues' => $previous_dues,
                    ':last_payment' => $last_payment, ':adjustment' => $adjustment, ':late_fee' => $late_fee, ':advance' => $advance,
                    ':notify_patron' => $notify_patron, ':payment_request_status' => $payment_request_status, ':franchise_id' => $franchise_id,
                    ':vendor_id' => $vendor_id, ':expiry_date' => $expiry_date, ':created_by' => $created_by, ':request_type' => $request_type,
                    ':auto_collect_plan_id' => $auto_collect_plan_id, ':plugin_value' => $plugin_value, ':staging' => $staging,
                    ':billing_profile' => $billing_profile, ':generate_estimate_invoice' => $generate_estimate_invoice, ':currency' => $currency,
                    ':einvoice_type' => $einvoice_type, ':product_taxation_type' => $product_taxation_type
                );
                $this->db->exec($sql, $params);
                $row = $this->db->single();
                return $row;
            } else {
                $this->setGenericError();
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E101-1]Error while saving invoice Error:  for user id[' . $userid . '] and template id [' . $template_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function saveInvoiceParticular($payment_request_id, $payment_request_status, $particular_id, $item, $arc, $sac_code, $description, $qty, $unit_type, $rate, $gst, $tax_amount, $discount_perc, $discount, $total_amount, $narrative, $user_id, $merchant_id, $staging = 0, $bulk_id = 0, $mrp, $product_expiry_date, $product_number)
    {
        try {
            $particular_id = implode('~', $particular_id);
            $item = implode('~', $item);
            $arc = implode('~', $arc);
            $sac_code = implode('~', $sac_code);
            $description = implode('~', $description);
            $qty = implode('~', $qty);
            $unit_type = implode('~', $unit_type);
            $rate = implode('~', $rate);
            $mrp = implode('~', $mrp);
            $product_expiry_date = implode('~', $product_expiry_date);
            $product_number = implode('~', $product_number);
            $gst = implode('~', $gst);
            $tax_amount = implode('~', $tax_amount);
            $discount_perc = implode('~', $discount_perc);
            $discount = implode('~', $discount);
            $total_amount = implode('~', $total_amount);
            $narrative = implode('~', $narrative);
            $sql = "call `save_invoice_particular`(:payment_request_id,:payment_request_status,:particular_id,:item,:arc,:sac_code,:description,:qty,:unit_type,:rate,:mrp,:product_expiry_date,:product_number,:gst,:tax_amount,:discount_perc,:discount,:total_amount,:narrative,:user_id,:merchant_id ,:staging,:bulk_id);";
            $params = array(
                ':payment_request_id' => $payment_request_id, ':payment_request_status' => $payment_request_status,
                ':particular_id' => $particular_id, ':item' => $item, ':arc' => $arc, ':sac_code' => $sac_code,
                ':description' => $description, ':qty' => $qty, ':unit_type' => $unit_type, ':rate' => $rate, ':mrp' => $mrp,
                ':product_expiry_date' => $product_expiry_date, ':product_number' => $product_number,
                ':gst' => $gst, ':tax_amount' => $tax_amount, ':discount_perc' => $discount_perc,
                ':discount' => $discount, ':total_amount' => $total_amount, ':narrative' => $narrative,
                ':user_id' => $user_id, ':merchant_id' => $merchant_id, ':staging' => $staging, ':bulk_id' => $bulk_id
            );
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E101-1-1]Error while saving particular details. Params ' . json_encode($params) . ' Error:  ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function saveInvoiceCoveringNote($payment_request_id, $merchant_id, $template_name, $body, $subject, $invoice_label, $pdf_enable, $user_id, $type)
    {
        try {
            $pdf_enable = ($pdf_enable == 1) ? 1 : 0;
            if ($type == 1) {
                $sql = "update `invoice_covering_note` set `merchant_id`=:merchant_id,`template_name`=:template_name,`body`=:body,`subject`=:subject,`invoice_label`=:invoice_label,`pdf_enable`=:pdf_enable,`last_update_by`=:user_id where payment_request_id=:payment_request_id";
            } else {
                $sql = "INSERT INTO `invoice_covering_note`(`merchant_id`,`payment_request_id`,`template_name`,`body`,`subject`,`invoice_label`,`pdf_enable`,`created_by`,`created_date`,
                    `last_update_by`)VALUES(:merchant_id,:payment_request_id,:template_name,:body,:subject,:invoice_label,:pdf_enable,:user_id,CURRENT_TIMESTAMP(),:user_id);";
            }
            $params = array(
                ':merchant_id' => $merchant_id, ':payment_request_id' => $payment_request_id, ':template_name' => $template_name, ':body' => $body, ':subject' => $subject, ':invoice_label' => $invoice_label,
                ':pdf_enable' => $pdf_enable, ':user_id' => $user_id
            );
            $this->db->exec($sql, $params);
            return $this->db->lastInsertId();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E295]Error while creating covering note Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function saveInvoiceTax($payment_request_id, $tax_id, $tax_percent, $tax_applicable, $tax_amt, $tax_detail_id, $user_id, $staging = 0, $bulk_id = 0)
    {
        try {
            $tax_id = implode('~', $tax_id);
            $tax_percent = implode('~', $tax_percent);
            $tax_applicable = implode('~', $tax_applicable);
            $tax_amt = implode('~', $tax_amt);
            $tax_detail_id = implode('~', $tax_detail_id);
            $sql = "call `save_invoice_tax`(:payment_request_id,:tax_id,:tax_percent,:tax_applicable,:tax_amt,:tax_detail_id,:user_id,:staging,:bulk_id );";
            $params = array(':payment_request_id' => $payment_request_id, ':tax_id' => $tax_id, ':tax_percent' => $tax_percent, ':tax_applicable' => $tax_applicable, ':tax_amt' => $tax_amt, ':tax_detail_id' => $tax_detail_id, ':user_id' => $user_id, ':staging' => $staging, ':bulk_id' => $bulk_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E101-1-1]Error while saving tax details. Params ' . json_encode($params) . ' Error:  ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function saveInvoiceReminder($payment_request_id, $due_date, $reminder, $subject, $sms, $merchant_id, $user_id)
    {
        try {
            $reminder = implode('~', $reminder);
            $subject = implode('~', $subject);
            $sms = implode('~', $sms);
            $sql = "call `save_invoice_reminder`(:payment_request_id,:due_date,:reminder,:subject,:sms,:merchant_id,:user_id );";
            $params = array(':payment_request_id' => $payment_request_id, ':due_date' => $due_date, ':reminder' => $reminder, ':subject' => $subject, ':sms' => $sms, ':merchant_id' => $merchant_id, ':user_id' => $user_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E101-1-1]Error while saving travel ticket details. Params ' . json_encode($params) . ' Error:  ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function saveTravelDetails($payment_request_id, $texistid, $btype, $booking_date, $journey_date, $b_name, $b_type, $unit_type, $sac_code, $b_from, $b_to, $b_amt, $b_charge, $b_unit, $b_rate, $b_mrp, $b_product_expiry_date, $b_product_number, $b_discount_perc, $b_discount, $b_gst, $b_total, $user_id, $b_description, $b_information, $staging = 0, $bulk_id = 0)
    {
        try {
            if (strlen($this->system_user_id) > 9) {
                $user_id = $this->system_user_id;
            }
            $texistid = implode('~', $texistid);
            $btype = implode('~', $btype);
            $booking_date = implode('~', $booking_date);
            $journey_date = implode('~', $journey_date);
            $b_name = implode('~', $b_name);
            $b_type = implode('~', $b_type);
            $unit_type = implode('~', $unit_type);
            $sac_code = implode('~', $sac_code);
            $b_from = implode('~', $b_from);
            $b_to = implode('~', $b_to);
            $b_amt = implode('~', $b_amt);
            $b_charge = implode('~', $b_charge);
            $b_unit = implode('~', $b_unit);
            $b_rate = implode('~', $b_rate);
            $b_mrp = implode('~', $b_mrp);
            $b_product_expiry_date = implode('~', $b_product_expiry_date);
            $b_product_number = implode('~', $b_product_number);
            $b_discount_perc = implode('~', $b_discount_perc);
            $b_discount = implode('~', $b_discount);
            $b_gst = implode('~', $b_gst);
            $b_description = implode('~', $b_description);
            $b_information = implode('~', $b_information);
            $b_total = implode('~', $b_total);
            $sql = "call `save_travel_particular`(:payment_request_id,:texistid,:btype,:booking_date,:journey_date,:b_name,:b_type,
            :unit_type,:sac_code,:b_from,:b_to1,:b_amt,:b_charge,:b_unit,:b_rate,:b_mrp,:b_product_expiry_date,:product_number,
            :b_discount_perc,:b_discount,:b_gst,:b_total,:b_description,:b_information,:user_id,:staging,:bulk_id );";
            $params = array(
                ':payment_request_id' => $payment_request_id, ':texistid' => $texistid, ':btype' => $btype, ':unit_type' => $unit_type,
                ':sac_code' => $sac_code, ':booking_date' => $booking_date, ':journey_date' => $journey_date, ':b_name' => $b_name, ':b_type' => $b_type,
                ':b_from' => $b_from, ':b_to1' => $b_to, ':b_amt' => $b_amt, ':b_charge' => $b_charge, ':b_unit' => $b_unit, ':b_rate' => $b_rate,
                ':b_mrp' => $b_mrp, ':b_product_expiry_date' => $b_product_expiry_date, ':product_number' => $b_product_number,
                ':b_discount_perc' => $b_discount_perc, ':b_discount' => $b_discount, ':b_gst' => $b_gst, ':b_total' => $b_total, ':b_description' => $b_description, ':b_information' => $b_information,
                ':user_id' => $user_id, ':staging' => $staging, ':bulk_id' => $bulk_id
            );

            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if ($row['message'] == 'success') {
            } else {
                $sql = "show errors";
                $params = array();
                $this->db->exec($sql, $params);
                $row = $this->db->single();

                SwipezLogger::error(__CLASS__, '[E101-1-1]Error while saving travel ticket details. Params ' . json_encode($params) . ' Error:  ' . json_encode($row));
            }
            return $row;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E101-1-1]Error while saving travel ticket details. Params ' . json_encode($params) . ' Error:  ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function saveConstructionDetails($payment_request_id, $data, $int, $user_id)
    {
        $array = array('current_contract_amount', 'original_contract_amount', 'approved_change_order_amount', 'previously_billed_percent', 'current_billed_amount','net_billed_amount', 'stored_materials', 'total_billed', 'retainage_amount_for_this_draw', 'total_outstanding_retainage', 'previously_billed_amount', 'current_billed_percent', 'retainage_percent', 'retainage_amount_previously_withheld', 'retainage_release_amount');
        foreach ($data as $k => $v) {
            if (in_array($k, $array)) {
                foreach ($data[$k] as $ki => $vi) {
                    $data[$k][$ki] = str_replace(',', '', $data[$k][$ki]);
                }
            }
        }
        try {
            $array_attach='';
            if($data['attach'][$int]!='')
            {
            $array_attach=explode(",",$data['attach'][$int]);
            $array_attach=json_encode( $array_attach);
            }
           
            $params = array(':payment_request_id' => $payment_request_id, ':bill_code' => $data['bill_code'][$int], ':description' => $data['description'][$int], ':bill_type' => $data['bill_type'][$int], ':original_contract_amount' => $data['original_contract_amount'][$int], ':approved_change_order_amount' => $data['approved_change_order_amount'][$int], ':current_contract_amount' => $data['current_contract_amount'][$int], ':previously_billed_percent' => $data['previously_billed_percent'][$int], ':previously_billed_amount' => $data['previously_billed_amount'][$int], ':current_billed_percent' => $data['current_billed_percent'][$int], ':current_billed_amount' => $data['current_billed_amount'][$int],':net_billed_amount' => $data['net_billed_amount'][$int], ':stored_materials' => $data['stored_materials'][$int], ':total_billed' => $data['total_billed'][$int], ':retainage_percent' => $data['retainage_percent'][$int], ':retainage_amount_previously_withheld' => $data['retainage_amount_previously_withheld'][$int], ':retainage_amount_for_this_draw' => $data['retainage_amount_for_this_draw'][$int], ':retainage_release_amount' => $data['retainage_release_amount'][$int], ':total_outstanding_retainage' => $data['total_outstanding_retainage'][$int], ':project' => $data['project'][$int], ':cost_code' => $data['cost_code'][$int], ':cost_type' => $data['cost_type'][$int], ':group' => $data['group'][$int], ':bill_code_detail' => $data['bill_code_detail'][$int], ':calculated_perc' => $data['calculated_perc'][$int], ':calculated_row' => $data['calculated_row'][$int], ':user_id' => $user_id,':attach_files' => $array_attach);
            if ($data['particular_id'][$int] > 0) {
                $params['id'] = $data['particular_id'][$int];
                $sql =  "update `invoice_construction_particular` set `payment_request_id`=:payment_request_id, `bill_code`=:bill_code, `description`=:description, `bill_type`=:bill_type, `original_contract_amount`=:original_contract_amount, `approved_change_order_amount`=:approved_change_order_amount, `current_contract_amount`=:current_contract_amount, `previously_billed_percent`=:previously_billed_percent, `previously_billed_amount`=:previously_billed_amount, `current_billed_percent`=:current_billed_percent, `current_billed_amount`=:current_billed_amount,`net_billed_amount`=:net_billed_amount,`stored_materials`=:stored_materials ,`total_billed`=:total_billed, `retainage_percent`=:retainage_percent, `retainage_amount_previously_withheld`=:retainage_amount_previously_withheld, `retainage_amount_for_this_draw`=:retainage_amount_for_this_draw, `retainage_release_amount`=:retainage_release_amount, `total_outstanding_retainage`=:total_outstanding_retainage, `project`=:project, `cost_code`=:cost_code, `cost_type`=:cost_type, `group`=:group, `bill_code_detail`=:bill_code_detail,`calculated_perc`=:calculated_perc,`calculated_row`=:calculated_row, `last_update_by`=:user_id,`attachments`=:attach_files,is_active=1
                where id=:id";
            } else {
                $sql = "INSERT INTO `invoice_construction_particular` (`payment_request_id`, `bill_code`, `description`, `bill_type`, `original_contract_amount`, `approved_change_order_amount`, `current_contract_amount`, `previously_billed_percent`, `previously_billed_amount`, `current_billed_percent`, `current_billed_amount`,`net_billed_amount`,`stored_materials` ,`total_billed`, `retainage_percent`, `retainage_amount_previously_withheld`, `retainage_amount_for_this_draw`, `retainage_release_amount`, `total_outstanding_retainage`, `project`, `cost_code`, `cost_type`, `group`, `bill_code_detail`,`calculated_perc`,`calculated_row`,`attachments`,`created_by`, `created_date`, `last_update_by`)
                VALUES (:payment_request_id,:bill_code,:description,:bill_type,:original_contract_amount,:approved_change_order_amount,:current_contract_amount,:previously_billed_percent,:previously_billed_amount,:current_billed_percent,:current_billed_amount,:net_billed_amount,:stored_materials,:total_billed,:retainage_percent,:retainage_amount_previously_withheld,:retainage_amount_for_this_draw,:retainage_release_amount,:total_outstanding_retainage,:project,:cost_code,:cost_type,:group,:bill_code_detail,:calculated_perc,:calculated_row,:attach_files,:user_id,now(),:user_id);";
            }
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            SwipezLogger::error(__CLASS__, '[E101-2]Error while saving invoice Error:  for user id[' . $user_id . '] and for payment request id [' . $payment_request_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function saveRevision($payment_request_id, $json, $number = 'V1', $user_id)
    {
        try {
            $sql = "INSERT INTO `invoice_revision`(`payment_request_id`,`json`,`revision_no`,`created_by`,`created_date`,`last_update_by`)VALUES
            (:request_id,:json,:number,:user_id,now(),:user_id);";
            $params = array(':request_id' => $payment_request_id, ':json' => $json, ':number' => $number, ':user_id' => $user_id);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E103+96]Error while save Revisions ' . $payment_request_id . ' Error: ' . $e->getMessage());
        }
    }



    public function oldupdateInvoice($payment_request_id, $user_id, $customer_id, $existid, $existvalue, $billdate, $duedate, $cyclename, $value, $id, $narrative, $amount, $tax, $particular_total, $tax_total, $previous_dues, $last_payment, $adjustment, $invoice_number, $supplier_id, $supplier, $late_fee, $advance, $expiry_date, $notify_patron, $coupon_id, $franchise_id = 0, $franchise_name_invoice = 1, $vendor_id = 0, $covering_id = 0, $cust_subject = '', $cust_sms = '', $invoice_title = '', $is_partial = 0, $partial_min_amount = 0, $auto_collect_plan_id = 0)
    {
        try {
            $invoice_number = ($invoice_number != '') ? $invoice_number : '';
            $advance = ($advance > 0) ? $advance : 0;
            $coupon_id = ($coupon_id > 0) ? $coupon_id : 0;
            $franchise_id = ($franchise_id > 0) ? $franchise_id : 0;
            $vendor_id = ($vendor_id > 0) ? $vendor_id : 0;
            $notify_patron = ($notify_patron > 0) ? $notify_patron : 0;
            $expiry_date = (strlen($expiry_date) > 5) ? $expiry_date : NULL;
            $previous_payment = ($previous_payment > 0) ? $previous_payment : 0;
            $last_payment = ($last_payment > 0) ? $last_payment : 0;
            $adjustment = ($adjustment > 0) ? $adjustment : 0;
            $is_partial = ($is_partial > 0) ? $is_partial : 0;
            $partial_min_amount = ($partial_min_amount > 0) ? $partial_min_amount : 0;
            $auto_collect_plan_id = ($auto_collect_plan_id > 0) ? $auto_collect_plan_id : 0;
            $amount = ($amount > 0) ? $amount : 0;
            $supplier_id = ($supplier_id > 0) ? $supplier_id : 0;
            $covering_id = ($covering_id > 0) ? $covering_id : 0;
            $previous_dues = ($previous_dues > 0) ? $previous_dues : 0;
            $tax = ($tax > 0) ? $tax : 0;
            $supplier = implode(',', $supplier);
            $ids = implode('~', $id);
            $values = implode('~', $value);
            $existid = implode('~', $existid);
            $existvalue = implode('~', $existvalue);
            if (strlen($this->system_user_id) > 9) {
                $update_by = $this->system_user_id;
            } else {
                $update_by = $user_id;
            }
            $sql = "call `update_invoicevalues`(:payment_request_id,:existid,:existvalue,:values,:ids,:user_id,:customer_id,:billdate,:duedate,:cyclename,:narrative,:amount,:tax,:previous_dues,:last_payment,:adjustment,:invoice_number,:supplier_id,:supplier,:late_fee,:advance,:expiry_date,:notify_patron,:coupon_id,:franchise_id,:franchise_name_invoice,:vendor_id,:covering_id,:cust_subject,:cust_sms,:invoice_title,:is_partial,:partial_min_amount,:auto_collect_plan_id,:update_by);";
            $params = array(
                ':payment_request_id' => $payment_request_id, ':existid' => $existid, ':existvalue' => $existvalue, ':values' => $values, ':ids' => $ids, ':user_id' => $user_id, ':customer_id' => $customer_id, ':billdate' => $billdate, ':duedate' => $duedate, ':cyclename' => $cyclename, ':narrative' => $narrative,
                ':amount' => $amount, ':tax' => $tax, ':previous_dues' => $previous_dues, ':last_payment' => $last_payment, ':adjustment' => $adjustment, ':invoice_number' => $invoice_number, ':supplier_id' => $supplier_id, ':supplier' => $supplier, ':late_fee' => $late_fee, ':advance' => $advance, ':expiry_date' => $expiry_date, ':notify_patron' => $notify_patron, ':coupon_id' => $coupon_id, ':franchise_id' => $franchise_id, ':franchise_name_invoice' => $franchise_name_invoice, ':vendor_id' => $vendor_id, ':covering_id' => $covering_id, ':cust_subject' => $cust_subject, ':cust_sms' => $cust_sms, ':invoice_title' => $invoice_title, ':is_partial' => $is_partial, ':partial_min_amount' => $partial_min_amount, ':auto_collect_plan_id' => $auto_collect_plan_id, ':update_by' => $update_by
            );
            $this->db->exec($sql, $params);
            $row = $this->db->single();

            if ($row['message'] == 'success') {
                $row['Message'] = 'success';
                return $row;
            } else {
                return $row;
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E101-2]Error while saving invoice Error:  for user id[' . $userid . '] and for payment request id [' . $payment_request_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function updateApiInvoice($invoice_id, $user_id, $customer_id, $billdate, $duedate, $cyclename, $value, $id, $narrative, $amount, $tax, $particular_total, $tax_total, $previous_dues, $last_payment, $adjustment, $supplier, $late_fee, $advance, $expiry_date, $notify_patron, $coupon_id = 0, $franchise_id = 0, $webhook_id = 0, $vendor_id = 0)
    {
        try {
            $advance = ($advance > 0) ? $advance : 0;
            $notify_patron = ($notify_patron > 0) ? $notify_patron : 0;
            $expiry_date = (strlen($expiry_date) > 5) ? $expiry_date : NULL;
            $previous_dues = ($previous_dues > 0) ? $previous_dues : 0;
            $last_payment = ($last_payment > 0) ? $last_payment : 0;
            $webhook_id = ($webhook_id > 0) ? $webhook_id : 0;
            $adjustment = ($adjustment > 0) ? $adjustment : 0;
            $amount = ($amount != '') ? $amount : 0;
            $tax = ($tax > 0) ? $tax : 0;
            $mobile = ($mobile == '') ? 0 : $mobile;

            $supplier = implode(',', $supplier);
            $ids = implode('~', $id);
            $values = implode('~', $value);
            $sql = "call update_api_invoice(:invoice_id,:values,:ids,:user_id,:customer_id,:billdate,:duedate,:cyclename,:narrative,:amount,:tax,:particular_total,:tax_total,
                :previous_dues,:last_payment,:adjustment,:supplier,:late_fee,:advance,:expiry_date,:notify_patron,:coupon_id,:franchise_id,:webhook_id,:vendor_id,:type);";
            $params = array(
                ':invoice_id' => $invoice_id, ':values' => $values, ':ids' => $ids, ':user_id' => $user_id, ':customer_id' => $customer_id, ':billdate' => $billdate, ':duedate' => $duedate, ':cyclename' => $cyclename, ':narrative' => $narrative,
                ':amount' => $amount, ':tax' => $tax, ':particular_total' => $particular_total, ':tax_total' => $tax_total, ':previous_dues' => $previous_dues, ':last_payment' => $last_payment, ':adjustment' => $adjustment,
                ':supplier' => $supplier, ':late_fee' => $late_fee, ':advance' => $advance, ':expiry_date' => $expiry_date, ':notify_patron' => $notify_patron, ':coupon_id' => $coupon_id, ':franchise_id' => $franchise_id, ':webhook_id' => $webhook_id, ':vendor_id' => $vendor_id, ':type' => 1
            );
            echo '<pre>';
            print_r($params);
            die();
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E101-3]Error while saving invoice Error:  for user id[' . $userid . '] and template id [' . $template_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function saveReminder($payment_request_id, $date, $subject, $sms, $merchant_id, $user_id)
    {
        try {
            $sql = "INSERT INTO `invoice_custom_reminder`(`payment_request_id`,`date`,`subject`,`sms`,`merchant_id`,`created_by`,`last_update_by`,`created_date`)"
                . "VALUES(:request_id,:date,:subject,:sms,:merchant_id,:user_id,:user_id,CURRENT_TIMESTAMP());";
            $params = array(':request_id' => $payment_request_id, ':date' => $date, ':subject' => $subject, ':sms' => $sms, ':merchant_id' => $merchant_id, ':user_id' => $user_id);
            $this->db->exec($sql, $params);
            $this->db->closeStmt();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E103]Error while save Reminder ' . $payment_request_id . ' Error: ' . $e->getMessage());
        }
    }

    public function saveVendorCommission($payment_request_id, $merchant_id, $vendor_id, $amount, $type, $commission_value, $user_id)
    {
        try {
            $sql = "INSERT INTO `invoice_vendor_commission`(`payment_request_id`,`merchant_id`,`vendor_id`,`amount`,`type`,`commission_value`,`created_by`,`last_update_by`,`created_date`)"
                . "VALUES(:request_id,:merchant_id,:vendor_id,:amount,:type,:commission_value,:user_id,:user_id,CURRENT_TIMESTAMP());";
            $params = array(':request_id' => $payment_request_id, ':merchant_id' => $merchant_id, ':vendor_id' => $vendor_id, ':amount' => $amount, ':type' => $type, ':commission_value' => $commission_value, ':user_id' => $user_id);
            $this->db->exec($sql, $params);
            $this->db->closeStmt();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__ . __METHOD__, '[E103]Error while save Vendor Commission ' . $payment_request_id . ' Error: ' . $e->getMessage());
        }
    }

    public function saveFranchiseSale($customer_id, $gs_id, $payment_request_id, $date, $gross, $non_brand_gross, $user_id)
    {
        try {
            $net = round($gross * 100 / 105);
            $tax = round($gross - $net, 2);
            $non_brand_net = round($non_brand_gross * 100 / 105);
            $non_brand_tax = round($non_brand_gross - $non_brand_net, 2);
            if ($gs_id > 0) {
                $sql = "update `invoice_food_franchise_sales` set `customer_id`=:customer_id,`date`=:date,`gross_sale`=:gross_sale,`tax`=:tax,`billable_sale`=:billable_sale,`non_brand_gross_sale`=:non_brand_gross_sale,`non_brand_tax`=:non_brand_tax,`non_brand_billable_sale`=:non_brand_billable_sale,is_active=1,`last_update_by`=:user_id where id=:request_id";
                $payment_request_id = $gs_id;
            } else {
                $sql = "INSERT INTO `invoice_food_franchise_sales`(`customer_id`,`payment_request_id`,`date`,`gross_sale`,`tax`,`billable_sale`,`non_brand_gross_sale`,`non_brand_tax`,`non_brand_billable_sale`,`status`,`created_by`,`last_update_by`,`created_date`)"
                    . "VALUES(:customer_id,:request_id,:date,:gross_sale,:tax,:billable_sale,:non_brand_gross_sale,:non_brand_tax,:non_brand_billable_sale,1,:user_id,:user_id,CURRENT_TIMESTAMP());";
            }
            $params = array(':customer_id' => $customer_id, ':request_id' => $payment_request_id, ':date' => $date, ':gross_sale' => $gross, ':tax' => $tax, ':billable_sale' => $net, ':non_brand_gross_sale' => $non_brand_gross, ':non_brand_tax' => $non_brand_tax, ':non_brand_billable_sale' => $non_brand_net, ':user_id' => $user_id);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E103]Error while save franchise sale ' . $payment_request_id . ' Error: ' . $e->getMessage());
        }
    }

    public function saveFranchiseSummary($type, $payment_request_id, $gross_sale, $net_sale,  $commision_fee_percent, $commision_waiver_percent, $commision_net_percent, $gross_fee, $waiver_fee, $net_fee, $non_brand_gross_sale, $non_brand_net_sale,  $non_brand_commision_fee_percent, $non_brand_commision_waiver_percent, $non_brand_commision_net_percent, $non_brand_gross_fee, $non_brand_waiver_fee, $non_brand_net_fee, $penalty, $bill_period, $user_id)
    {
        try {
            if ($type != 'insert') {
                $sql = "update `invoice_food_franchise_summary` set `gross_sale`=:gross_sale,`net_sale`=:net_sale,
                `commision_fee_percent`=:commision_fee_percent,`commision_waiver_percent`=:commision_waiver_percent,
                `commision_net_percent`=:commision_net_percent,`gross_fee`=:gross_fee,`waiver_fee`=:waiver_fee,`net_fee`=:net_fee,
                `non_brand_gross_sale`=:non_brand_gross_sale,`non_brand_net_sale`=:non_brand_net_sale,`non_brand_commision_fee_percent`=:non_brand_commision_fee_percent,
                `non_brand_commision_waiver_percent`=:non_brand_commision_waiver_percent,`non_brand_commision_net_percent`=:non_brand_commision_net_percent,
                `non_brand_gross_fee`=:non_brand_gross_fee,`non_brand_waiver_fee`=:non_brand_waiver_fee,`non_brand_net_fee`=:non_brand_net_fee,
                `penalty`=:penalty,`bill_period`=:bill_period,`last_update_by`=:user_id where payment_request_id=:payment_request_id";
            } else {
                $sql = "INSERT INTO `invoice_food_franchise_summary`(`payment_request_id`,`gross_sale`,`net_sale`,`commision_fee_percent`,`commision_waiver_percent`,
            `commision_net_percent`,`gross_fee`,`waiver_fee`,`net_fee`,`non_brand_gross_sale`,`non_brand_net_sale`,`non_brand_commision_fee_percent`,`non_brand_commision_waiver_percent`,
            `non_brand_commision_net_percent`,`non_brand_gross_fee`,`non_brand_waiver_fee`,`non_brand_net_fee`,`penalty`,`bill_period`,`created_by`,
            `created_date`,`last_update_by`)VALUES(:payment_request_id,:gross_sale,:net_sale,:commision_fee_percent,:commision_waiver_percent,:commision_net_percent,:gross_fee,
            :waiver_fee,:net_fee,:non_brand_gross_sale,:non_brand_net_sale,:non_brand_commision_fee_percent,:non_brand_commision_waiver_percent,:non_brand_commision_net_percent,:non_brand_gross_fee,
            :non_brand_waiver_fee,:non_brand_net_fee,:penalty,:bill_period,:user_id,CURRENT_TIMESTAMP(),:user_id);";
            }
            $params = array(
                ':payment_request_id' => $payment_request_id, ':gross_sale' => $gross_sale, ':net_sale' => $net_sale,
                ':commision_fee_percent' => $commision_fee_percent, ':commision_waiver_percent' => $commision_waiver_percent,
                ':commision_net_percent' => $commision_net_percent, ':gross_fee' => $gross_fee,
                ':waiver_fee' => $waiver_fee, ':net_fee' => $net_fee, ':non_brand_gross_sale' => $non_brand_gross_sale,
                ':non_brand_net_sale' => $non_brand_net_sale, ':non_brand_commision_fee_percent' => $non_brand_commision_fee_percent,
                ':non_brand_commision_waiver_percent' => $non_brand_commision_waiver_percent,
                ':non_brand_commision_net_percent' => $non_brand_commision_net_percent, ':non_brand_gross_fee' => $non_brand_gross_fee,
                ':non_brand_waiver_fee' => $non_brand_waiver_fee, ':non_brand_net_fee' => $non_brand_net_fee, ':penalty' => $penalty,
                ':bill_period' => $bill_period, ':user_id' => $user_id
            );
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);
            SwipezLogger::error(__CLASS__, '[E103]Error while save franchise summary ' . $payment_request_id . ' Error: ' . $e->getMessage());
        }
    }

    public function disableSale($payment_request_id, $user_id)
    {
        try {
            $sql = "update `invoice_food_franchise_sales` set `is_active`=0,`last_update_by`=:user_id"
                . " where payment_request_id=:payment_request_id";
            $params = array(':payment_request_id' => $payment_request_id, ':user_id' => $user_id);
            $this->db->exec($sql, $params);
            $this->db->closeStmt();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E103]Error while update Reminder ' . $id . ' Error: ' . $e->getMessage());
        }
    }

    public function updateReminder($id, $date, $subject, $sms, $user_id)
    {
        try {
            $sql = "update `invoice_custom_reminder` set `date`=:date,`subject`=:subject,`sms`=:sms,is_active=1,`last_update_by`=:user_id"
                . " where id=:id";
            $params = array(':id' => $id, ':date' => $date, ':subject' => $subject, ':sms' => $sms, ':user_id' => $user_id);
            $this->db->exec($sql, $params);
            $this->db->closeStmt();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E103]Error while update Reminder ' . $id . ' Error: ' . $e->getMessage());
        }
    }

    public function updatePaymentRequestStatus($payment_request_id, $status)
    {
        try {
            $sql = "update payment_request set payment_request_status=:status where payment_request_id=:request_id";
            $params = array(':status' => $status, ':request_id' => $payment_request_id);
            $this->db->exec($sql, $params);
            $this->db->closeStmt();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E103]Error while update payment request status Payment request id ' . $payment_request_id . ' Error: ' . $e->getMessage());
        }
    }

    public function getUserMobile($user_id)
    {
        try {
            $sql = "select mobile_no from user where user_id=:user_id";
            $params = array(':user_id' => $user_id);
            $this->db->exec($sql, $params);
            $result = $this->db->single();
            return $result['mobile_no'];
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E104]Error while fetching user mobile Error:  for user id[' . $user_id . ']' . $e->getMessage());
        }
    }

    public function saveInvoiceMetaValues($payment_request_id, $column_id, $value, $user_id)
    {
        try {
            $sql = "INSERT INTO `invoice_column_values` (`payment_request_id`, `column_id`, `value`,  `created_by`, `created_date`, `last_update_by`) 
            VALUES (:request_id, :column_id, :value, :user_id, now(),:user_id);";
            $params = array(':column_id' => $column_id, ':value' => $value, ':user_id' => $user_id, ':request_id' => $payment_request_id);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);
            SwipezLogger::error(__CLASS__, '[E103]Error while save Invoice Meta Values ' . $payment_request_id . ' Error: ' . $e->getMessage());
        }
    }

    public function saveInvoicePreview($merchant_id, $user_id, $payment_request_id, $invoice_type, $invoice_values, $invoice_number, $payment_request_status, $payment_request_type)
    {
        try {

            $values = implode('~', $invoice_values);

            $sql = "call `convert_draft_invoice`(:merchant_id,:user_id,:payment_request_id,:invoice_type,:invoice_values,:invoice_number,:payment_request_status,:payment_request_type);";
            $params = array(
                ':merchant_id' => $merchant_id,
                ':user_id' => $user_id,
                ':payment_request_id' => $payment_request_id,
                ':invoice_type' => $invoice_type,
                ':invoice_values' => $values,
                ':invoice_number' => $invoice_number,
                ':payment_request_status' => $payment_request_status,
                ':payment_request_type' => $payment_request_type
            );
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E101-1]Error while saving draft invoice Error:  for payment request id[' . $payment_request_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }
}
