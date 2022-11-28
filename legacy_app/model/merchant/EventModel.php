<?php

/**
 * This class calls necessary db objects to handle event payment requests and event requests to payment gateway
 *
 * @author Paresh
 */
class EventModel extends Model
{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Get system event template details
     */
    public function getCloneTemplateDetails($template_id)
    {
        try {
            $sql = "SELECT template_type from system_template where system_template_id=:template_id";
            $params = array(':template_id' => $template_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();

            $sql = "SELECT system_column_id as column_id,column_position,is_delete_allow,column_name,column_datatype,is_mandatory
	 from system_template_column_metadata  where system_template_id=:template_id and is_active=1  order by column_position,system_column_id";
            $params = array(':template_id' => $template_id);
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            $template['columns'] = $rows;
            $template['type'] = $row['template_type'];
            return $template;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, 'Error while getting invoice breckup Error: for template id[' . $template_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    /**
     * Get event invoice breckup
     */
    public function getInvoiceBreakup($payment_request_id)
    {
        try {
            $int = 0;
            $sql = "SELECT icv.invoice_id,icv.column_id,icv.value,icm.is_delete_allow,icm.column_name,icm.column_type,icm.column_datatype,icm.is_mandatory,icm.column_position,icm.column_group_id,
	icm.template_id from invoice_column_values as icv right outer join invoice_column_metadata as icm on icv.column_id = icm.column_id where icv.payment_request_id=:id  and icv.is_active=1 order by icm.column_position,icv.invoice_id";
            $params = array(':id' => $payment_request_id);
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            $template = array();
            foreach ($rows as $row) {
                if ($row['column_type'] == 'H') {
                    $template[$int]['column_id'] = $row['column_id'];
                    $template[$int]['invoice_id'] = $row['invoice_id'];
                    $template[$int]['column_name'] = $row['column_name'];
                    $template[$int]['is_mandatory'] = $row['is_mandatory'];
                    $template[$int]['column_datatype'] = $row['column_datatype'];
                    $template[$int]['is_delete_allow'] = $row['is_delete_allow'];
                    $template[$int]['column_position'] = $row['column_position'];
                    $template[$int]['value'] = $row['value'];
                    $int++;
                }
            }
            return $template;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E113]Error while payment request invoice breckup Error:  for payment request id[' . $payment_request_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function updateShortURL($payment_request_id, $url)
    {
        try {
            $sql = "update event_request set short_url=:url where event_request_id=:request_id";
            $params = array(':url' => $url, ':request_id' => $payment_request_id);
            $this->db->exec($sql, $params);
            $this->db->closeStmt();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E103-14]Error while update short url request Payment request id ' . $payment_request_id . ' Error: ' . $e->getMessage());
        }
    }

    /**
     * Save event request
     */
    public function saveEvent($user_id, $merchant_id, $event_name, $title, $short_description, $venue, $description, $duration, $occurence, $column, $columnvalue, $mandatory, $datatype, $position, $banner, $start_date, $end_date, $start_time, $end_time, $package_name, $package_desc, $unitavailable, $min_seat, $max_seat, $min_price, $max_price, $unitcost, $tax_text, $tax, $package_coupon, $is_flexible, $capture_payee_details, $capture_attendees_details, $coupon_code, $franchise_id, $vendor_id, $event_type, $booking_unit, $artist, $artist_label, $category_name, $event_tnc, $cancellation_policy, $package_type, $package_occurence, $stop_booking_string, $currency, $currency_amount)
    {
        try {

            $column = implode('~', $column);
            $columnvalue = implode('~', $columnvalue);
            $mandatory = implode('~', $mandatory);
            $datatype = implode('~', $datatype);
            $position = implode('~', $position);

            $start_date = implode('~', $start_date);
            $end_date = implode('~', $end_date);
            $start_time = implode('~', $start_time);
            $end_time = implode('~', $end_time);
            $package_name = implode('~', $package_name);
            $package_desc = implode('~', $package_desc);
            $unitavailable = implode('~', $unitavailable);
            $unitcost = implode('~', $unitcost);
            $currency_amount = implode('~', $currency_amount);
            $min_price = implode('~', $min_price);
            $max_price = implode('~', $max_price);
            $min_seat = implode('~', $min_seat);
            $max_seat = implode('~', $max_seat);
            $package_coupon = implode('~', $package_coupon);
            $is_flexible = implode('~', $is_flexible);
            $tax_text = implode('~', $tax_text);
            $tax = implode('~', $tax);

            $category_name = implode('~', $category_name);
            $package_type = implode('~', $package_type);
            $package_occurence = implode('~', $package_occurence);

            $bannerimage = basename($banner['name']);
            $bannerext = '.' . substr($bannerimage, strrpos($bannerimage, '.') + 1);


            $sql = "call `event_save`(:user_id,:merchant_id,:event_name,:title,:short_description,:venue,:description,:duration,:occurence,:column,:columnvalue,:mandatory,:datatype,:position,:bannerext,:start_date,:end_date,:start_time,:end_time,:package_name,:package_desc,:unitavailable,:unitcost,:min_price,:max_price,:min_seat,:max_seat,:tax_text,:tax,:package_coupon,:is_flexible,:payee_capture,:attendees_capture,:coupon_code,:franchise_id,:vendor_id,:event_type"
                . ",:booking_unit,:artist,:artist_label,:category_name,:event_tnc,:cancellation_policy,:package_type,:package_occurence,:stop_booking_string,:currency,:currency_amount);";
            $params = array(
                ':user_id' => $user_id, ':merchant_id' => $merchant_id, ':event_name' => $event_name, ':title' => $title, ':short_description' => $short_description, ':venue' => $venue, ':description' => $description, ':column' => $column, ':duration' => $duration, ':occurence' => $occurence, ':columnvalue' => $columnvalue, ':mandatory' => $mandatory, ':datatype' => $datatype, ':position' => $position, ':bannerext' => $bannerext, ':start_date' => $start_date, ':start_date' => $start_date, ':end_date' => $end_date, ':start_time' => $start_time, ':end_time' => $end_time, ':package_name' => $package_name, ':package_desc' => $package_desc, ':unitavailable' => $unitavailable, ':unitcost' => $unitcost, ':min_price' => $min_price, ':max_price' => $max_price,
                ':min_seat' => $min_seat, ':max_seat' => $max_seat, ':tax_text' => $tax_text, ':tax' => $tax, ':package_coupon' => $package_coupon, ':is_flexible' => $is_flexible, ':payee_capture' => $capture_payee_details, ':attendees_capture' => $capture_attendees_details, ':coupon_code' => $coupon_code, ':franchise_id' => $franchise_id, ':vendor_id' => $vendor_id, ':event_type' => $event_type, ':booking_unit' => $booking_unit, ':artist' => $artist, ':artist_label' => $artist_label, ':category_name' => $category_name, ':event_tnc' => $event_tnc, ':cancellation_policy' => $cancellation_policy, ':package_type' => $package_type, ':package_occurence' => $package_occurence, ':stop_booking_string' => $stop_booking_string, ':currency' => $currency, ':currency_amount' => $currency_amount
            );

            $this->db->exec($sql, $params);
            $row = $this->db->single();
            $this->db->closeStmt();
            if ($row['message'] == 'success') {
                $this->uploadImage($banner, md5($row['template_id']), $row['template_id']);
                return $row;
            } else {

                SwipezLogger::error(__CLASS__, '[E133-1]Error while saving event template Error: for user id[' . $user_id . ']' . $row['Message']);
                return $this->db->error . $row['Message'];
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E133]Error while saving event template Error: for user id[' . $user_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    /**
     * Update exist event
     */
    public function updateEvent($payment_request_id, $event_name, $title, $short_description, $venue, $description, $duration, $occurence, $column, $columnvalue, $mandatory, $datatype, $position, $existvalue, $invoice_id, $banner, $start_date, $end_date, $start_time, $end_time, $epackage_id, $epackage_name, $epackage_desc, $eunitavailable, $esold_out, $emin_seat, $emax_seat, $emin_price, $emax_price, $eunitcost, $epackage_coupon, $ecategory_name, $category_name, $epackage_type, $eis_flexible, $epackage_occurence, $etax_text, $etax, $package_name, $package_desc, $unitavailable, $min_seat, $max_seat, $min_price, $max_price, $unitcost, $tax_text, $tax, $package_coupon, $franchise_id, $vendor_id, $is_flexible, $capture_payee_details, $capture_attendees_details, $coupon_code, $event_type, $package_type, $package_occurence, $tnc, $privacy, $booking_unit, $artist, $artist_label, $stop_booking_string, $currency, $currency_amount, $ecurrency_amount)
    {
        try {
            $column = implode('~', $column);
            $columnvalue = implode('~', $columnvalue);
            $mandatory = implode('~', $mandatory);
            $datatype = implode('~', $datatype);
            $position = implode('~', $position);
            $existvalue = implode('~', $existvalue);
            $invoice_id = implode('~', $invoice_id);

            $epackage_type = implode('~', $epackage_type);
            $epackage_occurence = implode('~', $epackage_occurence);
            $etax = implode('~', $etax);
            $etax_text = implode('~', $etax_text);
            $esold_out = implode('~', $esold_out);

            $package_type = implode('~', $package_type);
            $package_occurence = implode('~', $package_occurence);
            $ecategory_name = implode('~', $ecategory_name);
            $category_name = implode('~', $category_name);

            $tax = implode('~', $tax);
            $tax_text = implode('~', $tax_text);

            $start_date = implode('~', $start_date);
            $end_date = implode('~', $end_date);
            $start_time = implode('~', $start_time);
            $end_time = implode('~', $end_time);

            $epackage_id = implode('~', $epackage_id);
            $epackage_name = implode('~', $epackage_name);
            $epackage_desc = implode('~', $epackage_desc);
            $eunitavailable = implode('~', $eunitavailable);
            $eunitcost = implode('~', $eunitcost);
            $emin_price = implode('~', $emin_price);
            $emax_price = implode('~', $emax_price);
            $emin_seat = implode('~', $emin_seat);
            $emax_seat = implode('~', $emax_seat);
            $epackage_coupon = implode('~', $epackage_coupon);

            $package_name = implode('~', $package_name);
            $package_desc = implode('~', $package_desc);
            $unitavailable = implode('~', $unitavailable);
            $unitcost = implode('~', $unitcost);
            $min_price = implode('~', $min_price);
            $max_price = implode('~', $max_price);
            $min_seat = implode('~', $min_seat);
            $max_seat = implode('~', $max_seat);
            $package_coupon = implode('~', $package_coupon);
            $currency_amount = implode('~', $currency_amount);
            $ecurrency_amount = implode('~', $ecurrency_amount);

            $is_flexible = implode('~', $is_flexible);
            $eis_flexible = implode('~', $eis_flexible);



            $bannerimage = basename($banner['name']);
            $bannerext = '.' . substr($bannerimage, strrpos($bannerimage, '.') + 1);


            $sql = "call `event_update`(:payment_request_id,:event_name,:title,:short_description,:venue,:description,:duration,:occurence,:column,:columnvalue,:mandatory,:datatype,:position,:existvalue,"
                . ":invoice_id,:bannerext,:start_date,:end_date,:start_time,:end_time,:epackage_id,:epackage_name,:epackage_desc,:eunitavailable,:esold_out,:eunitcost,:emin_price,:emax_price,"
                . ":emin_seat,:emax_seat,:epackage_coupon,:ecategory_name,:category_name,"
                . ":epackage_type,:eis_flexible,:epackage_occurence,:etax_text,:etax,:package_type,:package_occurence,:tax_text,:tax,:package_name,:package_desc,:unitavailable,:unitcost,:min_price,:max_price,:min_seat,:max_seat,:package_coupon,:franchise_id,:vendor_id,:is_flexible,:payee_capture,:attendees_capture,:coupon_code,:event_type,:booking_unit,:artist,:artist_label,:tnc,:privacy,:stop_booking_string,:currency,:currency_amount,:ecurrency_amount);";

            $params = array(
                ':payment_request_id' => $payment_request_id, ':event_name' => $event_name, ':title' => $title, ':short_description' => $short_description, ':venue' => $venue, ':description' => $description, ':duration' => $duration, ':occurence' => $occurence, ':column' => $column, ':columnvalue' => $columnvalue, ':mandatory' => $mandatory, ':datatype' => $datatype, ':position' => $position, ':existvalue' => $existvalue, ':invoice_id' => $invoice_id, ':bannerext' => $bannerext, ':start_date' => $start_date, ':start_date' => $start_date, ':end_date' => $end_date, ':start_time' => $start_time, ':end_time' => $end_time, ':epackage_id' => $epackage_id, ':epackage_name' => $epackage_name, ':epackage_desc' => $epackage_desc, ':eunitavailable' => $eunitavailable, ':eunitcost' => $eunitcost, ':emin_price' => $emin_price, ':emax_price' => $emax_price,
                ':emin_seat' => $emin_seat, ':emax_seat' => $emax_seat, ':epackage_coupon' => $epackage_coupon, ':ecategory_name' => $ecategory_name, ':category_name' => $category_name,
                ':epackage_type' => $epackage_type, ':eis_flexible' => $eis_flexible, ':epackage_occurence' => $epackage_occurence, ':etax_text' => $etax_text, ':etax' => $etax, ':package_type' => $package_type, ':package_occurence' => $package_occurence, ':tax_text' => $tax_text, ':tax' => $tax, ':package_name' => $package_name, ':package_desc' => $package_desc, ':unitavailable' => $unitavailable, ':esold_out' => $esold_out, ':unitcost' => $unitcost, ':min_price' => $min_price, ':max_price' => $max_price,
                ':min_seat' => $min_seat, ':max_seat' => $max_seat, ':package_coupon' => $package_coupon, ':franchise_id' => $franchise_id, ':vendor_id' => $vendor_id, ':is_flexible' => $is_flexible, ':payee_capture' => $capture_payee_details, ':attendees_capture' => $capture_attendees_details, ':coupon_code' => $coupon_code, ':event_type' => $event_type, ':booking_unit' => $booking_unit, ':artist' => $artist, ':artist_label' => $artist_label, ':tnc' => $tnc, ':privacy' => $privacy, ':stop_booking_string' => $stop_booking_string, ':currency' => $currency, ':currency_amount' => $currency_amount, ':ecurrency_amount' => $ecurrency_amount
            );

            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if ($row['message'] == 'success') {
                if ($bannerext != '.') {
                    $this->uploadImage($banner, md5($row['template_id']), $row['template_id']);
                }

                if (empty($banner)) {
                    $this->update_logo('', $row['template_id']);
                }

                return $row;
            } else {
                $sql = "show errors";
                $params = array();
                $this->db->exec($sql, $params);
                $row = $this->db->single();
                return $this->db->error . $row['Message'];
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E133]Error while saving new template Error: for user id[' . $user_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    /**
     * Upload event logo and banner image
     */
    public function uploadImage($image_file, $name, $template_id)
    {
        try {
            $filename = basename($image_file['name']);

            $ext = substr($filename, strrpos($filename, '.') + 1);
            $newname = 'uploads/images/logos/' . $name . '.' . $ext;
            //Check if the file with the same name is already exists on the server
            while (file_exists($newname)) {
                $name = '1' . $name;
                $newname = 'uploads/images/logos/' . $name . '.' . $ext;
            }
            $name = $name . '.' . $ext;
            //Attempt to move the uploaded file to it's new place
            if ((move_uploaded_file($image_file['tmp_name'], $newname))) {
                $this->update_logo($name, $template_id);
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E136]Error while uploading template logo Error: ' . $e->getMessage());
        }
    }

    public function update_logo($logo, $template_id)
    {
        try {

            $sql = 'UPDATE `invoice_template` SET `banner_path`=:logo,last_update_by=`user_id`, last_update_date=CURRENT_TIMESTAMP() WHERE template_id=:template_id';
            $params = array(':template_id' => $template_id, ':logo' => $logo);
            $this->db->exec($sql, $params);
            $this->db->closeStmt();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E137]Error while deleting existing template Error: for template id[' . $template_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    /**
     * Get merchant event list
     */
    public function getEventList($user_id, $status, $franchise_id = 0)
    {
        try {
            $franchise_id = ($franchise_id > 0) ? $franchise_id : 0;
            switch ($status) {
                case 1:
                    $filter = " and date_format(event_to_date,'%Y-%m-%d') >= date_format(now(),'%Y-%m-%d') and e.is_active=1";
                    break;
                case 2:
                    $filter = " and date_format(event_to_date,'%Y-%m-%d') < date_format(now(),'%Y-%m-%d') and e.is_active=1";
                    break;
                case 3:
                    $filter = " and e.is_active=0";
                    break;
            }
            if ($franchise_id > 0) {
                $filter .= " and e.franchise_id=" . $franchise_id;
            }

            $sql = "select e.*,f.franchise_name from event_request e left outer join franchise f on e.franchise_id=f.franchise_id "
                . "where e.user_id=:user_id" . $filter;
            $params = array(':user_id' => $user_id);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E109]Error while getting payment request list Error:  for user id[' . $user_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function save_offline_event_transaction($payment_request_id, $customer_id, $merchant_id, $amount, $tax_amount, $discount, $bank_id, $date, $bank_ref_id, $cheque_no, $cash_paid_to, $response_type, $seat, $occurence_id, $price, $package_id, $attendee_name, $mobile, $age, $coupon_id, $cheque_status,$currency)
    {
        try {
            $attendee_name = implode('~', $attendee_name);
            $package_id = implode('~', $package_id);
            $occurence_id = implode('~', $occurence_id);
            $mobile = implode('~', $mobile);
            $price = implode('~', $price);
            $age = implode('~', $age);
            $cheque_no = ($cheque_no > 0) ? $cheque_no : 0;
            $coupon_id = ($coupon_id > 0) ? $coupon_id : 0;
            $tax_amount = ($tax_amount > 0) ? $tax_amount : 0;
            $discount = ($discount > 0) ? $discount : 0;

            $sql = "call save_offline_event_transaction(:payment_request_id,:customer_id,:merchant_id,:amount,:tax_amount,:discount_amount,:price,:bank_id,:date,:bank_ref_id,:cheque_no,:cash_paid_to,:response_type,:seat,:occurence_id,:package_id,:attendee_name,:mobile,:age,:coupon_id,:cheque_status,:currency)";
            $params = array(':payment_request_id' => $payment_request_id, ':customer_id' => $customer_id, ':merchant_id' => $merchant_id, ':amount' => $amount, ':tax_amount' => $tax_amount, ':discount_amount' => $discount, ':price' => $price, ':bank_id' => $bank_id, ':date' => $date, ':bank_ref_id' => $bank_ref_id, ':cheque_no' => $cheque_no, ':cash_paid_to' => $cash_paid_to, ':response_type' => $response_type, ':seat' => $seat, ':occurence_id' => $occurence_id, ':package_id' => $package_id, ':attendee_name' => $attendee_name, ':mobile' => $mobile, ':age' => $age, ':coupon_id' => $coupon_id, ':cheque_status' => $cheque_status,':currency'=>$currency);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            return $row['transaction_id'];
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E109-8]Error whil eoffline seat booking Error:  for user id[' . $merchant_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function deleteevent($event_id)
    {
        try {
            $sql = 'UPDATE `event_request` SET `is_active`=0,last_update_by=`user_id`, last_update_date=CURRENT_TIMESTAMP() WHERE event_request_id=:event_id';
            $params = array(':event_id' => $event_id);
            $this->db->exec($sql, $params);
            $this->db->closeStmt();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E137]Error while deleting existing template Error: for template id[' . $template_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getAttendees($event_id, $from_date, $to_date, $package_id, $occ_id)
    {
        try {
            $sql = 'call get_attendees_details(:event_id,:from_date,:to_date,:package_id,:occ_id);';
            $params = array(':event_id' => $event_id, ':from_date' => $from_date, ':to_date' => $to_date, ':package_id' => $package_id, ':occ_id' => $occ_id);
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            return $rows;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E137]Error while deleting existing template Error: for template id[' . $template_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getPackageSummary($event_id)
    {
        try {
            $sql = 'call get_event_package_summary(:event_id);';
            $params = array(':event_id' => $event_id);
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            return $rows;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E137]Error while deleting existing template Error: for template id[' . $template_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getCapturesdetails($event_id)
    {
        try {
            $sql = 'select m.system_column_name,v.value,v.transaction_id from event_capture_metadata m inner join event_capture_values v on m.column_id=v.column_id where m.event_id=:event_id';
            $params = array(':event_id' => $event_id);
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            return $rows;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E137]Error while deleting existing template Error: for template id[' . $template_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }
}
