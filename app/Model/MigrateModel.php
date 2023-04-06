<?php

namespace App\Model;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of payout
 *
 * @author Paresh
 */

use Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Exception;

class MigrateModel extends Model
{

    public function getTableData($table)
    {

        $retObj = DB::table($table)
            ->select(DB::raw('*'))
            ->get();
        return $retObj;
    }

    public function getTableRow($table, $col, $val)
    {

        $retObj = DB::table($table)
            ->select(DB::raw('*'))
            ->where($col, $val)
            ->first();
        return $retObj;
    }

    public function getTableRows($table, $col, $val)
    {

        $retObj = DB::table($table)
            ->select(DB::raw('*'))
            ->where($col, $val)
            ->where('is_active', 1)
            ->get();
        return $retObj;
    }

    public function getTableRowsIn($table, $col, $val)
    {

        $retObj = DB::table($table)
            ->select(DB::raw('*'))
            ->whereIn($col, $val)
            ->where('is_active', 1)
            ->get();
        return $retObj;
    }

    public function getTemplateDetail($template_id)
    {

        $retObj = DB::table('invoice_template_new')
            ->select(DB::raw('*'))
            ->where('template_id', $template_id)
            ->first();
        return $retObj;
    }

    public function getPaymentRequest($start, $limit)
    {
        $retObj = DB::select("select payment_request_id,p.merchant_id,p.template_id,t.template_type,p.created_by from payment_request_backup p inner join invoice_template t on p.template_id=t.template_id where p.payment_request_id>'R000537052' and p.payment_request_id<'R000537083' limit " . $start . "," . $limit);
        return $retObj;
    }

    public function getPaymentRequestPlugin($start, $limit)
    {
        $retObj = DB::select("select payment_request_id,merchant_id,template_id,franchise_name_invoice,coupon_id,custom_subject,custom_sms,has_custom_reminder,invoice_title,enable_partial_payment,partial_min_amount,created_date,last_update_date from payment_request where payment_request_id>'R000537052' limit " . $start . "," . $limit);
        return $retObj;
    }

    public function getPaymentRequestValidate($start, $limit)
    {
        $retObj = DB::select("select template_id,merchant_id,payment_request_id,basic_amount,tax_amount,created_date from payment_request where merchant_id>'M000000027' and payment_request_status<>3 and is_active=1 order by merchant_id limit " . $start . "," . $limit);
        return $retObj;
    }

    public function getParticularCol($template_id)
    {
        $retObj = DB::select("select column_name from invoice_column_metadata where column_type='PF' and is_template_column=1 and is_active=1 and template_id='" . $template_id . "'");
        return $retObj;
    }

    public function getTaxCol($template_id)
    {
        $retObj = DB::select("select column_name,default_column_value from invoice_column_metadata where column_type='TF' and is_template_column=1 and is_active=1 and template_id='" . $template_id . "'");
        return $retObj;
    }

    public function getSupplier($template_id)
    {
        $retObj = DB::select("select column_name,default_column_value from invoice_column_metadata where column_type='SP' and is_template_column=1 and is_active=1 and template_id='" . $template_id . "'");
        return $retObj;
    }

    public function getDeduct($template_id)
    {
        $retObj = DB::select("select column_name from invoice_column_metadata where column_type='DF' and is_template_column=1 and is_active=1 and template_id='" . $template_id . "'");
        return $retObj;
    }

    public function getCC($template_id)
    {
        $retObj = DB::select("select column_name from invoice_column_metadata where column_type='CC' and is_template_column=1 and is_active=1 and template_id='" . $template_id . "'");
        return $retObj;
    }

    public function getGroupID($type, $payment_request_id)
    {
        $retObj = DB::select("select v.value,m.column_name,m.column_group_id,v.created_date from invoice_column_values_backup v inner join invoice_column_metadata_backup m on m.column_id=v.column_id where m.column_type='" . $type . "' and v.payment_request_id='" . $payment_request_id . "' and v.is_active=1 ;");
        return $retObj;
    }

    public function getGroupData($group_id, $type, $payment_request_id)
    {
        $retObj = DB::select("select v.value,m.column_name,m.column_type,column_group_id from invoice_column_values_backup v inner join invoice_column_metadata_backup m on m.column_id=v.column_id where v.payment_request_id='" . $payment_request_id . "' and column_group_id='" . $group_id . "' and v.is_active=1;");
        return $retObj;
    }

    public function getTemplateType($template_id)
    {
        $retObj = DB::table('invoice_template')
            ->select(DB::raw('template_type'))
            ->where('template_id', $template_id)
            ->first();
        return $retObj->template_type;
    }

    public function getMetaTaxCol()
    {

        $retObj = DB::select("select t.user_id,t.template_id,m.column_id,column_name,m.created_date,m.last_update_date from invoice_column_metadata m inner join invoice_template t on m.template_id=t.template_id  where m.column_type='TF' and m.is_active=1 and is_template_column=1");
        return $retObj;
    }

    public function getTaxID($tax_name, $percentage, $merchant_id)
    {
        $retObj = DB::table('merchant_tax')
            ->select(DB::raw('tax_id'))
            ->where('percentage', $percentage)
            ->where('tax_name', $tax_name)
            ->where('merchant_id', $merchant_id)
            ->first();

        if (empty($retObj)) {
            $id = DB::table('merchant_tax')->insertGetId(
                [
                    'merchant_id' => $merchant_id,
                    'type' => 0,
                    'tax_name' => $tax_name,
                    'percentage' => $percentage,
                    'description' => '',
                    'created_by' => 'System',
                    'last_update_by' => 'System',
                    'created_date' => date('Y-m-d H:i:s')
                ]
            );
            return $id;
        } else {
            return $retObj->tax_id;
        }
    }

    public function updateTaxID($id, $tax_id, $created_date, $last_update_date)
    {

        DB::table('invoice_column_metadata')
            ->where('column_id', $id)
            ->update([
                'default_column_value' => $tax_id,
                'created_date' => $created_date,
                'last_update_date' => $last_update_date
            ]);
    }
    public function updateDefaultTax($template_id, $tax)
    {

        DB::table('temp_invoice_template')
            ->where('template_id', $template_id)
            ->update([
                'default_tax' => $tax
            ]);
    }

    public function updateInvoicePlugin($id, $plugin, $created_date, $last_update_date)
    {

        DB::table('payment_request_new')
            ->where('payment_request_id', $id)
            ->update([
                'plugin_value' => $plugin,
                'created_date' => $created_date,
                'last_update_date' => $last_update_date
            ]);
    }

    public function getMerchantID($user_id)
    {

        $retObj = DB::table('merchant')
            ->select(DB::raw('merchant_id'))
            ->where('user_id', $user_id)
            ->first();
        return $retObj->merchant_id;
    }

    public function getMetaColumnRow($column_id)
    {

        $retObj = DB::table('invoice_column_metadata')
            ->select(DB::raw('column_name,default_column_value'))
            ->where('column_id', $column_id)
            ->first();
        return $retObj;
    }

    public function saveTemplate($data)
    {

        $id = DB::table('invoice_template_new')->insertGetId(
            [
                'template_id' => $data->template_id,
                'user_id' => $data->user_id,
                'template_name' => $data->template_name,
                'template_type' => $data->template_type,
                'particular_column' => $data->particular_column,
                'particular_total' => $data->particular_total,
                'tax_total' => $data->tax_total,
                'default_particular' => $data->default_particular,
                'default_tax' => $data->default_tax,
                'plugin' => $data->plugin,
                'is_active' => $data->is_active,
                'image_path' => $data->image_path,
                'banner_path' => $data->banner_path,
                'invoice_title' => $data->invoice_title,
                'created_by' => $data->created_by,
                'created_date' => $data->created_date,
                'last_update_by' => $data->last_update_by,
                'last_update_date' => $data->last_update_date
            ]
        );
        return $id;
    }

    public function saveParticular($data)
    {

        $id = DB::table('invoice_particular_new')->insertGetId(
            [
                'payment_request_id' => $data->payment_request_id,
                'item' => $data->item,
                'annual_recurring_charges' => $data->annual_recurring_charges,
                'sac_code' => $data->sac_code,
                'description' => $data->description,
                'qty' => $data->qty,
                'rate' => $data->rate,
                'gst' => $data->gst,
                'tax_amount' => $data->tax_amount,
                'discount' => $data->discount,
                'total_amount' => $data->total_amount,
                'narrative' => $data->narrative,
                'created_by' => 'System',
                'created_date' => $data->created_date,
                'last_update_by' => 'System',
                'last_update_date' => $data->last_update_date
            ]
        );
        return $id;
    }

    public function saveTax($data)
    {

        $id = DB::table('invoice_tax_new')->insertGetId(
            [
                'payment_request_id' => $data->payment_request_id,
                'tax_id' => $data->tax_id,
                'tax_percent' => $data->tax_percent,
                'applicable' => $data->applicable,
                'tax_amount' => $data->tax_amount,
                'narrative' => $data->narrative,
                'created_by' => 'System',
                'created_date' => $data->created_date,
                'last_update_by' => 'System',
                'last_update_date' => $data->last_update_date
            ]
        );
        return $id;
    }

    public function getParticularSum($payment_request_id)
    {
        $sum = DB::table('invoice_particular')
            ->where('payment_request_id', $payment_request_id)
            ->where('is_active', 1)
            ->sum('total_amount');
        return $sum;
    }

    public function getTaxSum($payment_request_id)
    {
        $sum = DB::table('invoice_tax')
            ->where('payment_request_id', $payment_request_id)
            ->where('is_active', 1)
            ->sum('tax_amount');
        return $sum;
    }

    public function getTemplateDetails()
    {

        $retObj = DB::select("select t.*,m.merchant_id,m.new_merchant_id,m.new_user_id from e_invoice_template t inner join e_merchant m on m.user_id=t.user_id");
        return $retObj;
    }

    public function getInvoiceDetails()
    {

        $retObj = DB::select("select distinct p.*,m.new_merchant_id,m.new_user_id,t.template_id,t.plugin,c.customer_id,b.cycle_name from e_payment_request p inner join e_billing_cycle_detail b on p.billing_cycle_id=b.billing_cycle_id inner join customer c on c.e_customer_id=p.customer_id inner join e_merchant m on m.merchant_id=p.merchant_id inner join invoice_template t on t.e_template_id=p.template_id");
        return $retObj;
    }

    public function getMerchantDetails()
    {

        $retObj = DB::select("select m.merchant_id,m.user_id,email_id,first_name,last_name,mobile_no,company_name from e_user u inner join e_merchant m on u.user_id=m.user_id");
        return $retObj;
    }

    public function updateMerchantId($merchant_id, $new_mid, $newuid)
    {

        DB::table('e_merchant')
            ->where('merchant_id', $merchant_id)
            ->update([
                'new_merchant_id' => $new_mid,
                'new_user_id' => $newuid
            ]);
    }

    public function updateUserStatus($user_id)
    {

        DB::table('user')
            ->where('user_id', $user_id)
            ->update([
                'user_status' => 11
            ]);
    }

    public function setUserAddr($user_id, $new_user_id)
    {

        $retObj = DB::select("update user_addr u , e_user_addr eu set u.address1=eu.address1,u.city=eu.city,u.zipcode=eu.zipcode,u.state=eu.state,u.mobile_no=eu.mobile_no where u.user_id = '" . $new_user_id . "' and eu.user_id='" . $user_id . "'");
        return $retObj;
    }
    public function getEasybizUsers()
    {

        $retObj = DB::select("select email_id,new_merchant_id,new_user_id,first_name,last_name from e_merchant m inner join e_user u on u.user_id=m.user_id where email_id<>'';");
        return $retObj;
    }

    public function setMerchantAddr($merchant_id, $new_merchant_id)
    {

        $retObj = DB::select("update merchant_addr u , e_merchant_addr eu set u.address1=eu.address1,u.city=eu.city,u.zipcode=eu.zipcode,u.state=eu.state,u.business_email=eu.business_email,u.business_phone=eu.business_phone where u.merchant_id = '" . $new_merchant_id . "' and eu.merchant_id='" . $merchant_id . "';");
        return $retObj;
    }

    public function setMerchantTax($merchant_id, $new_merchant_id)
    {

        $retObj = DB::select("INSERT INTO `merchant_tax`(`e_tax_id`,`merchant_id`,`type`,`tax_name`,`tax_type`,`percentage`,`description`,`is_active`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)SELECT tax_id,'" . $new_merchant_id . "',    `type`,    `tax_name`,	`tax_type`,    `percentage`,    `description`,    `is_active`,    '" . $new_merchant_id . "',    `created_date`,    '" . $new_merchant_id . "',    `last_update_date` FROM `e_merchant_tax` where merchant_id ='" . $merchant_id . "';");
        return $retObj;
    }

    public function saveMerchantCustomer($merchant_id, $new_merchant_id, $new_user_id)
    {

        $retObj = DB::select("INSERT INTO `customer`(`e_customer_id`,`merchant_id`,`user_id`,`customer_code`,`first_name`,`last_name`,`email`,`mobile`,`address`,`address2`,`city`,`state`,`zipcode`,`customer_group`,`is_active`,`customer_status`,`payment_status`,`email_comm_status`,`sms_comm_status`,`qr_image_path`,`password`,`gst_number`,`bulk_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)SELECT `customer_id`, '" . $new_merchant_id . "','" . $new_user_id . "',`customer_code`,`first_name`,`last_name`,`email`,`mobile`,`address`,`address2`,`city`,`state`,`zipcode`,`customer_group`,`is_active`,`customer_status`,`payment_status`,`email_comm_status`,`sms_comm_status`,`qr_image_path`,`password`,`gst_number`,`bulk_id`,'" . $new_merchant_id . "',`created_date`,'" . $new_merchant_id . "',`last_update_date` FROM `e_customer` where merchant_id ='" . $merchant_id . "';");
        return $retObj;
    }

    public function saveMerchantCustomerGroup($merchant_id, $new_merchant_id)
    {

        $retObj = DB::select("INSERT INTO `customer_group`(`merchant_id`,`group_name`,`is_active`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)SELECT '" . $new_merchant_id . "',`group_name`,`is_active`,'" . $new_merchant_id . "',`created_date`,'" . $new_merchant_id . "',`last_update_date` FROM `e_customer_group`  where merchant_id ='" . $merchant_id . "';");
        return $retObj;
    }

    public function saveMerchantCustomerMetadata($merchant_id, $new_merchant_id)
    {

        $retObj = DB::select("INSERT INTO `customer_column_metadata` (`e_column_id`,`merchant_id`,`column_datatype`,`position`,`column_name`,`default_column_value`,`column_type`,`is_active`,`created_date`,`created_by`,`last_update_date`,`last_update_by`)"
            . "SELECT `column_id`,'" . $new_merchant_id . "',`column_datatype`,`position`,`column_name`,`default_column_value`,`column_type`,`is_active`,`created_date`,'" . $new_merchant_id . "',`last_update_date`,'" . $new_merchant_id . "' FROM `e_customer_column_metadata` where merchant_id ='" . $merchant_id . "';");
        return $retObj;
    }

    public function saveMerchantCustomerValues($merchant_id, $new_merchant_id)
    {

        $retObj = DB::select("INSERT INTO `customer_column_values`(`customer_id`,`column_id`,`value`,`is_active`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)select c.customer_id,m.column_id,v.value,v.is_active,'" . $new_merchant_id . "',v.created_date,'" . $new_merchant_id . "',v.last_update_date from e_customer_column_values v inner join customer c on v.customer_id=c.e_customer_id inner join customer_column_metadata m on v.column_id=m.e_column_id where m.merchant_id ='" . $new_merchant_id . "';");
        return $retObj;
    }

    public function saveMerchantAutoInvoiceNo($merchant_id, $new_merchant_id)
    {

        $retObj = DB::select("INSERT INTO `merchant_auto_invoice_number`(`merchant_id`,`prefix`,`val`,`type`,`is_active`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)SELECT '" . $new_merchant_id . "',`prefix`,`val`,`type`,`is_active`,'" . $new_merchant_id . "',`created_date`,'" . $new_merchant_id . "',`last_update_date` FROM `e_merchant_auto_invoice_number` where merchant_id ='" . $merchant_id . "';");
        return $retObj;
    }

    public function saveMerchantLanding($merchant_id, $new_merchant_id)
    {

        $retObj = DB::select("INSERT INTO `merchant_landing`(`merchant_id`,`overview`,`terms_condition`,`cancellation_policy`,`about_us`,`office_location`,`contact_no`,`email_id`,`logo`,`banner`,`is_active`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) SELECT '" . $new_merchant_id . "',`overview`,`terms_condition`,`cancellation_policy`,`about_us`,`office_location`,`contact_no`,`email_id`,`logo`,`banner`,`is_active`,'" . $new_merchant_id . "',`created_date`,'" . $new_merchant_id . "',`last_update_date` FROM `e_merchant_landing` where merchant_id ='" . $merchant_id . "';");
        return $retObj;
    }

    public function saveMerchantBank($merchant_id, $new_merchant_id)
    {

        $retObj = DB::select("INSERT INTO `merchant_bank_detail`(`merchant_id`,`account_holder_name`,`account_no`,`ifsc_code`,`account_type`,`bank_name`,`adhar_card`,`pan_card`,`cancelled_cheque`,`gst_certificate`,`address_proof`,`company_incorporation_certificate`,`business_reg_proof`,`partnership_deed`,`partner_pan_card`,`is_active`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)SELECT '" . $new_merchant_id . "',`account_holder_name`,`account_no`,`ifsc_code`,`account_type`,`bank_name`,`adhar_card`,`pan_card`,`cancelled_cheque`,`gst_certificate`,`address_proof`,`company_incorporation_certificate`,`business_reg_proof`,`partnership_deed`,`partner_pan_card`,`is_active`,'" . $new_merchant_id . "',`created_date`,'" . $new_merchant_id . "',`last_update_date` FROM `e_merchant_bank_detail` where merchant_id ='" . $merchant_id . "';");
        return $retObj;
    }

    public function saveTemplateMetadata($template_id, $new_template_id, $new_merchant_id)
    {

        $retObj = DB::select("INSERT INTO `invoice_column_metadata`(`e_column_id`,`template_id`,`column_datatype`,`column_position`,`sort_order`,`position`,`column_name`,`default_column_value`,`column_type`,`customer_column_id`,`is_mandatory`,`is_delete_allow`,`save_table_name`,`is_template_column`,`function_id`,`column_group_id`,`is_active`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)SELECT `column_id`,'" . $new_template_id . "',`column_datatype`,`column_position`,`sort_order`,`position`,`column_name`,`default_column_value`,`column_type`,`customer_column_id`,`is_mandatory`,`is_delete_allow`,`save_table_name`,`is_template_column`,`function_id`,`column_group_id`,`is_active`,'" . $new_merchant_id . "',`created_date`,'" . $new_merchant_id . "',`last_update_date` FROM `e_invoice_column_metadata` where template_id ='" . $template_id . "' and column_type<>'PC';");
        return $retObj;
    }

    public function saveInvoiceParticular($payment_request_id, $new_payment_request_id, $new_merchant_id)
    {

        $retObj = DB::select("INSERT INTO `invoice_particular`(`payment_request_id`,`item`,`sac_code`,`description`,`qty`,`rate`,`gst`,`tax_amount`,`discount`,`total_amount`,`is_active`,`narrative`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)SELECT '" . $new_payment_request_id . "',`item`,`sac_code`,`description`,`qty`,`rate`,`taxin`,`tax_amount`,`discount`,`total_amount`,`is_active`,`narrative`,'" . $new_merchant_id . "',`created_date`,'" . $new_merchant_id . "',`last_update_date` FROM `e_invoice_particular` where payment_request_id='" . $payment_request_id . "'");
        return $retObj;
    }

    public function saveInvoiceTax($payment_request_id, $new_payment_request_id, $new_merchant_id)
    {

        $retObj = DB::select("INSERT INTO `invoice_tax`(`payment_request_id`,`tax_id`,`tax_percent`,`applicable`,`tax_amount`,`narrative`,`is_active`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) SELECT '" . $new_payment_request_id . "',mt.tax_id,`tax_percent`,`applicable`,`tax_amount`,`narrative`,t.`is_active`,'" . $new_merchant_id . "',t.`created_date`,'" . $new_merchant_id . "',t.`last_update_date` FROM e_invoice_tax t inner join merchant_tax mt on t.tax_id=mt.e_tax_id where payment_request_id='" . $payment_request_id . "';");
        return $retObj;
    }

    public function saveInvoiceColValue($payment_request_id, $new_payment_request_id, $new_merchant_id)
    {

        $retObj = DB::select("INSERT INTO `invoice_column_values`(`payment_request_id`,`column_id`,`value`,`is_active`,`created_by`,`created_date`,`last_update_by`,`last_update_date`) SELECT '" . $new_payment_request_id . "',m.column_id,`value`,t.`is_active`,'" . $new_merchant_id . "',t.`created_date`,'" . $new_merchant_id . "',t.`last_update_date` FROM e_invoice_column_values t inner join invoice_column_metadata m on t.column_id=m.e_column_id where payment_request_id='" . $payment_request_id . "';");
    }

    public function saveMerchant($email, $f_name, $l_name, $mobile, $company_name)
    {

        $retObj = DB::select("call `merchant_register`('" . $email . "','" . $f_name . "','" . $l_name . "','+91','" . $mobile . "','','" . $company_name . "',2,32,0,2)");
        return $retObj[0];
    }

    public function savePatron($merchant_id, $email, $f_name, $l_name, $mobile, $password)
    {
        $retObj = DB::select("call `patron_register`('" . $merchant_id . "','','" . $f_name . "','" . $l_name . "','" . $email . "','+91','" . $mobile . "','" . $password . "',0,'',2)");
        return $retObj[0];
    }

    public function getEasybizTaxID($tax_id)
    {
        $retObj = DB::table('merchant_tax')
            ->select(DB::raw('tax_id'))
            ->where('e_tax_id', $tax_id)
            ->first();
        return $retObj->tax_id;
    }

    public function getProductUnit()
    {
        $retObj = DB::table('merchant_product')
            ->select(DB::raw('unit_type,merchant_id'))
            ->whereNotNull('unit_type')
            ->whereRaw("unit_type <>''")
            ->groupBy('unit_type')
            ->groupBy('merchant_id')
            ->get();
        return $retObj;
    }

    public function getInvoiceParticular()
    {
        $retObj = DB::table('invoice_particular as i')
            ->select(DB::raw('item,unit_type,p.merchant_id'))
            ->join('payment_request as p', 'i.payment_request_id', '=', 'p.payment_request_id')
            ->groupBy('i.item')
            ->groupBy('i.unit_type')
            ->groupBy('p.merchant_id')
            ->get();
        return $retObj;
    }
    public function getStagingInvoiceParticular()
    {
        $retObj = DB::table('staging_invoice_particular as i')
            ->select(DB::raw('item,unit_type,p.merchant_id'))
            ->join('bulk_upload as p', 'i.bulk_id', '=', 'p.bulk_upload_id')
            ->where('p.status', 3)
            ->groupBy('i.item')
            ->groupBy('i.unit_type')
            ->groupBy('p.merchant_id')
            ->get();
        return $retObj;
    }

    public function getInvoiceParticularInfo($merchant_id, $name, $table)
    {
        $retObj = DB::table($table . 'invoice_particular as i')
            ->select(DB::raw('i.*'))
            ->join($table . 'payment_request as p', 'i.payment_request_id', '=', 'p.payment_request_id')
            ->where('item', $name)
            ->where('p.merchant_id', $merchant_id)
            ->where('i.is_active', 1)
            ->orderBy('i.id', 'DESC')
            ->first();
        if ($retObj == null) {
            return false;
        }
        return $retObj;
    }

    public function updateInvoiceParticularProductId($merchant_id, $item, $id, $table)
    {
        DB::table($table . 'invoice_particular as i')
            ->join($table . 'payment_request as p', 'i.payment_request_id', '=', 'p.payment_request_id')
            ->where('p.merchant_id', $merchant_id)
            ->where('i.item', $item)
            ->update([
                'i.product_id' => $id
            ]);
    }

    public function getExpenseParticular()
    {
        $retObj = DB::table('expense_detail as i')
            ->select(DB::raw('particular_name,p.merchant_id'))
            ->join('expense as p', 'i.expense_id', '=', 'p.expense_id')
            ->groupBy('i.particular_name')
            ->groupBy('p.merchant_id')
            ->get();
        return $retObj;
    }

    public function getExpenseParticularInfo($merchant_id, $name)
    {
        $retObj = DB::table('expense_detail as i')
            ->select(DB::raw('i.*'))
            ->join('expense as p', 'i.expense_id', '=', 'p.expense_id')
            ->where('particular_name', $name)
            ->where('p.merchant_id', $merchant_id)
            ->where('i.is_active', 1)
            ->orderBy('i.id', 'DESC')
            ->first();
        if ($retObj == null) {
            return false;
        }
        return $retObj;
    }

    public function updateExpenseDetailProductId($merchant_id, $item, $id)
    {
        DB::table('expense_detail as i')
            ->join('expense as p', 'i.expense_id', '=', 'p.expense_id')
            ->where('p.merchant_id', $merchant_id)
            ->where('i.particular_name', $item)
            ->update([
                'i.product_id' => $id
            ]);
    }

    public function getUnitTypeId($merchant_id, $unit_type)
    {
        $retObj = DB::table('merchant_unit_type')
            ->select(DB::raw('id'))
            ->where('name', $unit_type)
            ->whereRaw("merchant_id in ('" . $merchant_id . "','system')")
            ->first();
        if ($retObj == null) {
            return false;
        }
        return $retObj->id;
    }
    public function getProductId($merchant_id, $name)
    {
        $retObj = DB::table('merchant_product')
            ->select(DB::raw('product_id'))
            ->where('product_name', $name)
            ->where('merchant_id', $merchant_id)
            ->where('is_active', 1)
            ->first();
        if ($retObj == null) {
            return false;
        }
        return $retObj->product_id;
    }



    public function addProduct($merchant_id, $name, $sac, $unit_type, $description, $gst, $price, $purchase_cost = 0)
    {
        $id = DB::table('merchant_product')->insertGetId(
            [
                'product_name' => $name,
                'merchant_id' => $merchant_id,
                'sac_code' => $sac,
                'unit_type' => $unit_type,
                'description' => $description,
                'gst_percent' => $gst,
                'price' => $price,
                'purchase_cost' => $purchase_cost,
                'created_by' => 'system',
                'created_date' => date('Y-m-d H:i:s'),
                'last_update_by' => 'system'
            ]
        );
        return $id;
    }
    public function addUnitType($merchant_id, $unittype)
    {
        $id = DB::table('merchant_unit_type')->insertGetId(
            [
                'name' => $unittype,
                'merchant_id' => $merchant_id,
                'created_by' => 'system',
                'created_date' => date('Y-m-d H:i:s'),
                'last_update_by' => 'system'
            ]
        );
        return $id;
    }

    public function updateUnitType($merchant_id, $unittype, $unit_type_id)
    {
        DB::table('merchant_product')
            ->where('merchant_id', $merchant_id)
            ->where('unit_type', $unittype)
            ->update([
                'unit_type_id' => $unit_type_id
            ]);
    }


    public function saveEasybizTemplate($data)
    {

        $retObj = DB::select("select generate_sequence('Template_id') as template_id;");
        $template_id = $retObj[0]->template_id;
        DB::table('invoice_template')->insertGetId(
            [
                'template_id' => $template_id,
                'e_template_id' => $data->template_id,
                'user_id' => $data->user_id,
                'merchant_id' => $data->merchant_id,
                'template_name' => $data->template_name,
                'template_type' => $data->template_type,
                'particular_column' => $data->particular_column,
                'particular_total' => $data->particular_total_label,
                'tax_total' => $data->tax_total_label,
                'default_particular' => $data->default_particular,
                'default_tax' => $data->default_tax,
                'plugin' => $data->plugin,
                'is_active' => $data->is_active,
                'image_path' => 'easybiz_' . $data->image_path,
                'banner_path' => $data->banner_path,
                'invoice_title' => $data->invoice_title,
                'created_by' => $data->created_by,
                'created_date' => $data->created_date,
                'last_update_by' => $data->last_update_by,
                'last_update_date' => $data->last_update_date
            ]
        );
        return $template_id;
    }

    public function saveEasybizBillingCycle($name, $user_id)
    {

        $retObj = DB::table('billing_cycle_detail')
            ->select(DB::raw('billing_cycle_id'))
            ->where('cycle_name', $name)
            ->where('user_id', $user_id)
            ->first();
        if (!empty($retObj)) {
            return $retObj->billing_cycle_id;
        } else {
            $retObj = DB::select("select generate_sequence('Billing_cycle_id') as id;");
            $Billing_cycle_id = $retObj[0]->id;
            DB::table('billing_cycle_detail')->insertGetId(
                [
                    'billing_cycle_id' => $Billing_cycle_id,
                    'cycle_name' => $name,
                    'user_id' => $user_id,
                    'created_by' => $user_id,
                    'created_date' => date('Y-m-d H:i:s'),
                    'last_update_by' => $user_id
                ]
            );
            return $Billing_cycle_id;
        }
    }

    public function saveEasybizInvoice($data)
    {

        $retObj = DB::select("select generate_sequence('Pay_Req_Id') as req_id;");
        $req_id = $retObj[0]->req_id;
        DB::table('payment_request')->insertGetId(
            [
                'payment_request_id' => $req_id,
                'e_payment_request_id' => $data->payment_request_id,
                'user_id' => $data->user_id,
                'merchant_id' => $data->merchant_id,
                'customer_id' => $data->customer_id,
                'payment_request_type' => $data->payment_request_type,
                'invoice_type' => $data->invoice_type,
                'template_id' => $data->template_id,
                'billing_cycle_id' => $data->billing_cycle_id,
                'absolute_cost' => $data->absolute_cost,
                'basic_amount' => $data->basic_amount,
                'tax_amount' => $data->tax_amount,
                'invoice_total' => $data->invoice_total,
                'swipez_total' => $data->swipez_total,
                'convenience_fee' => $data->convenience_fee,
                'late_payment_fee' => $data->late_payment_fee,
                'grand_total' => $data->grand_total,
                'previous_due' => $data->previous_due,
                'advance_received' => $data->advance_received,
                'invoice_number' => $data->invoice_number,
                'estimate_number' => $data->estimate_number,
                'payment_request_status' => $data->payment_request_status,
                'bill_date' => $data->bill_date,
                'due_date' => $data->due_date,
                'expiry_date' => $data->expiry_date,
                'narrative' => $data->narrative,
                'bulk_id' => $data->bulk_id,
                'parent_request_id' => $data->parent_request_id,
                'notify_patron' => $data->notify_patron,
                'notification_sent' => $data->notification_sent,
                'short_url' => '',
                'plugin_value' => $data->plugin_value,
                'created_by' => $data->created_by,
                'created_date' => $data->created_date,
                'last_update_by' => $data->last_update_by,
                'last_update_date' => $data->last_update_date
            ]
        );
        return $req_id;
    }

    public function saveHSNSAC($type, $code, $desc, $gst, $chapter, $link)
    {

        $id = DB::table('import_hsn_code')->insertGetId(
            [
                'type' => $type,
                'code' => $code,
                'description' => $desc,
                'gst' => $gst,
                'chapter' => $chapter,
                'link' => $link
            ]
        );
        return $id;
    }

    public function getCustomerMetadata()
    {
        $retObj = DB::select("select column_id,merchant_id,column_name,column_datatype from customer_column_metadata where is_active=1 and (
        column_name like '%Company Name%' OR 
        column_name like '%Company%' OR 
        column_name like '%Client Name%' OR 
        column_name like '%Business Name%' OR 
        column_name like '%Party Name%')");
        return $retObj;
    }

    public function setCustomerCompanyName($column_id)
    {
        $retObj = DB::select("Update customer c, customer_column_values v set c.company_name = v.value where c.customer_id=v.customer_id and v.column_id='" . $column_id . "'");
        return $retObj;
    }

    public function updateCompanyMetadata($column_id)
    {
        $retObj = DB::select("update customer_column_metadata set column_datatype='company_name' where column_id='" . $column_id . "'");
        return $retObj;
    }


    public function saveTable($table_name, $data)
    {
        $id = DB::table($table_name)->insertGetId(
            $data
        );
        return $id;
    }
    public function saveSeq($name)
    {
        $retObj = DB::select("select generate_sequence('" . $name . "') as id;");
        return $retObj[0]->id;
    }
}
