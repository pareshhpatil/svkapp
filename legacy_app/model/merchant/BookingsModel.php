<?php

class BookingsModel extends Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function getBookingCategories($merchant_id)
    {
        try {
            $sql = "select * from booking_categories where merchant_id=:merchant_id and is_active=:active";
            $params = array(':merchant_id' => $merchant_id, ':active' => 1);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
            Sentry\captureException($e);
            SwipezLogger::error(__CLASS__, '[EB1001]Error while fetching booking_categories list Error: for merchant id[' . $merchant_id . ']' . $e->getMessage());
        }
    }

    public function getBookingPackages($user_id)
    {
        try {
            $sql = "select * from booking_packages where created_by=:user_id";
            $params = array(':user_id' => $user_id);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
            Sentry\captureException($e);
            SwipezLogger::error(__CLASS__, '[EB1001]Error while fetching booking_categories list Error: for merchant id[' . $merchant_id . ']' . $e->getMessage());
        }
    }

    public function savePageSetting($merchant_id, $banner, $title, $hide_menu)
    {
        try {
            $bannername = $this->uploadImage($banner);

            $row = array();
            $sql = "select merchant_landing_id,logo,banner from merchant_landing where merchant_id=:merchant_id and is_active=1";
            $params = array(':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $row = $this->db->single();

            if (empty($row)) {
                $sql = "INSERT INTO `merchant_landing`(`merchant_id`,`booking_background`,`booking_title`,`booking_hide_menu`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)"
                    . "Values(:merchant_id,:background,:booking_title,:booking_hide_menu,:created_by,CURRENT_TIMESTAMP(),:update_by,CURRENT_TIMESTAMP())";
                $params = array(':merchant_id' => $merchant_id, ':background' => $bannername, ':booking_title' => $title, ':booking_hide_menu' => $hide_menu, ':created_by' => $merchant_id, ':update_by' => $merchant_id);
                $this->db->exec($sql, $params);
            } else {
                $bannername = ($bannername == NULL) ? $row['booking_background'] : $bannername;
                $sql = "update merchant_landing set booking_background=:background,booking_title=:booking_title,booking_hide_menu=:booking_hide_menu,last_update_by=:last_update_by,last_update_date=CURRENT_TIMESTAMP() where merchant_landing_id=:landing_id;";
                $params = array(':background' => $bannername, ':booking_title' => $title, ':booking_hide_menu' => $hide_menu, ':last_update_by' => $merchant_id, ':landing_id' => $row['merchant_landing_id']);
                $this->db->exec($sql, $params);
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E098]Error while Updating notification Error:  for user id[' . $userid . ']' . $e->getMessage());
        }
    }

    public function getBookingMembership($merchant_id)
    {
        try {
            $sql = "select category_name,title,membership_id,amount,days from booking_membership m inner join booking_categories c on m.category_id=c.category_id"
                . " where m.merchant_id=:merchant_id and m.is_active=:active";
            $params = array(':merchant_id' => $merchant_id, ':active' => 1);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB1001]Error while fetching booking_categories list Error: for merchant id[' . $merchant_id . ']' . $e->getMessage());
        }
    }

    public function getBookingCalendars($merchant_id)
    {
        try {
            $sql = "select description,c.logo,calendar_title,booking_unit,calendar_email,calendar_id,max_booking,category_name,c.created_date,calendar_active,c.category_id from booking_calendars c inner join booking_categories t on c.category_id=t.category_id where c.merchant_id=:merchant_id and c.is_active=:active";
            $params = array(':merchant_id' => $merchant_id, ':active' => 1);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB1002]Error while fetching booking_categories list Error: for merchant id[' . $merchant_id . ']' . $e->getMessage());
        }
    }

    public function getCalendarReservation($merchant_id, $calendar_id, $from_date, $to_date)
    {
        try {
            $sql = "call get_calendar_reservation(:merchant_id,:id,:from_date,:to_date)";
            $params = array(':merchant_id' => $merchant_id, ':id' => $calendar_id, ':from_date' => $from_date, ':to_date' => $to_date);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB1003]Error while fetching booking_categories list Error: for merchant id[' . $merchant_id . ']' . $e->getMessage());
        }
    }

    public function getMembershipReservation($merchant_id, $from_date, $to_date)
    {
        try {
            $sql = "call get_membership_bookings(:merchant_id,:from_date,:to_date)";
            $params = array(':merchant_id' => $merchant_id, ':from_date' => $from_date, ':to_date' => $to_date);
            $this->db->exec($sql, $params);
            $list = $this->db->resultset();
            return $list;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB1003]Error while fetching booking_categories list Error: for merchant id[' . $merchant_id . ']' . $e->getMessage());
        }
    }

    public function createCategory($category_name, $membership, $merchant_id, $user_id)
    {
        try {
            $order = 0;
            $sql = "select max(category_order) as maxc from booking_categories where merchant_id=:merchant_id and is_active=:active";
            $params = array(':merchant_id' => $merchant_id, ':active' => 1);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if (isset($row['maxc'])) {
                $order = $row['maxc'] + 1;
            }
            $sql = "INSERT INTO `booking_categories`(`merchant_id`,`category_name`,`membership`,`category_order`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)
                VALUES(:merchant_id,:name,:membership,:order,:user_id,CURRENT_TIMESTAMP(),:user_id,CURRENT_TIMESTAMP())";
            $params = array(':merchant_id' => $merchant_id, ':name' => $category_name, ':membership' => $membership, ':order' => $order, ':user_id' => $user_id);
            $this->db->exec($sql, $params);
            $id = $this->db->lastInsertId();
            return $id;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB1004]Error while creating booking category Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function createMembership($title, $category, $days, $amount, $description, $merchant_id, $user_id)
    {
        try {
            $sql = "INSERT INTO `booking_membership`(`merchant_id`,`category_id`,`title`,`days`,`description`,`amount`,`created_by`,`created_date`,`last_update_by`)VALUES(:merchant_id,:category_id,:title,:days,:description,:amount,:user_id,CURRENT_TIMESTAMP(),:user_id)";
            $params = array(':merchant_id' => $merchant_id, ':category_id' => $category, ':title' => $title, ':description' => $description, ':days' => $days, ':amount' => $amount, ':user_id' => $user_id);
            $this->db->exec($sql, $params);
            $calendar_id = $this->db->lastInsertId();
            return $calendar_id;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB1005]Error while creating booking calendar Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function updateMembership($id, $title, $category, $days, $amount, $description, $user_id)
    {
        try {
            $sql = "update `booking_membership` set `category_id`=:category_id,`title`=:title,`days`=:days,`description`=:description,`amount`=:amount,`last_update_by`=:user_id where membership_id=:id";
            $params = array(':id' => $id, ':category_id' => $category, ':title' => $title, ':description' => $description, ':days' => $days, ':amount' => $amount, ':user_id' => $user_id);
            $this->db->exec($sql, $params);
            $calendar_id = $this->db->lastInsertId();
            return $calendar_id;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB1005]Error while creating booking calendar Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function createCalendar($category, $calendar_name, $max_booking, $description, $notification_email,
     $notification_mobile, $unit, $capture, $logo, $merchant_id, $user_id, $confirmation_message, $tandc
     , $cancellation_policy, $cancellation_type = 0, $cancellation_days = '0', $cancellation_hours = '0')
    {
        try {
            $order = 0;
            $sql = "select max(calendar_order) as maxc from booking_calendars where merchant_id=:merchant_id and is_active=:active";
            $params = array(':merchant_id' => $merchant_id, ':active' => 1);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if (isset($row['maxc'])) {
                $order = $row['maxc'] + 1;
            }
            $sql = "INSERT INTO `booking_calendars`(`merchant_id`,`category_id`,`max_booking`,`calendar_title`,`description`,
            `notification_email`,`notification_mobile`,`booking_unit`,`capture_details`,`logo`,`calendar_order`,`created_by`,
            `created_date`,`last_update_by`,`last_update_date`,`confirmation_message`,`tandc` ,`cancellation_policy`
            ,`cancellation_type`,`cancellation_days`,`cancellation_hours`)
                VALUES(:merchant_id,:category_id,:max_booking,:name,:description,
                :notification_email,:notification_mobile,:booking_unit,:capture_details,:logo,:order,:user_id,
                CURRENT_TIMESTAMP(),:user_id,CURRENT_TIMESTAMP(),:confirmation_message,:tandc,:cancellation_policy,
                :cancellation_type,:cancellation_days,:cancellation_hours)";
            $params = array(
                ':merchant_id' => $merchant_id, ':category_id' => $category, ':max_booking' => $max_booking, ':name' => $calendar_name,
                ':description' => $description, ':notification_email' => $notification_email, ':notification_mobile' => $notification_mobile,
                ':logo' => $logo, ':booking_unit' => $unit, ':capture_details' => $capture, ':order' => $order, ':user_id' => $user_id,
                ':confirmation_message' => $confirmation_message,  ':tandc' => $tandc,  ':cancellation_policy' => $cancellation_policy
                ,  ':cancellation_type' => $cancellation_type,  ':cancellation_days' => $cancellation_days,  ':cancellation_hours' => $cancellation_hours
            );
            $this->db->exec($sql, $params);
            $calendar_id = $this->db->lastInsertId();
            return $calendar_id;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB1005]Error while creating booking calendar Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function createPackage($package_name, $package_desc, $user_id, $package_image)
    {

        try {
            $sql = "INSERT INTO `booking_packages`(`package_name`,`package_desc`,`created_by`,`last_update_by`,`package_image`)
            VALUES(:package_name,:package_desc,:user_id,CURRENT_TIMESTAMP(),:package_image)";
            $params = array(':package_name' => $package_name, ':package_desc' => $package_desc, ':user_id' => $user_id, ':package_image' => $package_image);
            $this->db->exec($sql, $params);
            $calendar_id = $this->db->lastInsertId();
            return $calendar_id;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB1005]Error while creating booking calendar Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function createHoliday($merchant_id, $calendar_id, $date, $user_id)
    {
        try {
            $sql = "SELECT * FROM booking_holidays WHERE holiday_date =:holiday AND calendar_id=:calendar_id and is_active=1 limit 1";
            $params = array(':holiday' => $date, ':calendar_id' => $calendar_id,);
            $this->db->exec($sql, $params);
            $row = $this->db->single();
            if (empty($row)) {
                $sql = "INSERT INTO `booking_holidays`(`merchant_id`,`holiday_date`,`calendar_id`,`created_by`,`created_date`,`last_update_by`,`last_update_date`)
                    VALUES(:merchant_id,:date,:calendar_id,:user_id,CURRENT_TIMESTAMP(),:user_id,CURRENT_TIMESTAMP())";
                $params = array(':merchant_id' => $merchant_id, ':date' => $date, ':calendar_id' => $calendar_id, ':user_id' => $user_id);
                $this->db->exec($sql, $params);
                return true;
            } else {
                return False;
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB1006]Error while creating booking calendar Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function deleteSlots($calendar_id, $date, $user_id)
    {
        try {
            $sql = "UPDATE `booking_slots` SET `is_active` =0, last_update_by=:user_id 
                WHERE slot_date=:slot_date and calendar_id=:calendar_id";
            $params = array(':slot_date' => $date, ':calendar_id' => $calendar_id, ':user_id' => $user_id);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB1007]Error while creating booking calendar Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function updateSlots($slot_id, $date, $from_time, $to_time, $text, $price, $is_multiple, $unitavailable, $min_seat, $max_seat, $user_id, $title, $description)
    {
        try {
            $sql = 'select count(booking_transaction_detail_id) as countseat from booking_transaction_detail WHERE slot_id=:slot_id and is_paid=1 and is_cancelled = 0';
            $params = array(':slot_id' => $slot_id);
            $this->db->exec($sql, $params);
            $result = $this->db->single();
            if ($result['countseat'] > 0) {
                $available_seat = $unitavailable - $result['countseat'];
                if ($available_seat < 0) {
                    $available_seat = 0;
                }
            } else {
                $available_seat = $unitavailable;
            }
            $sql = "UPDATE `booking_slots` 
            SET `slot_date` =:date,
            `slot_time_from`=:from_time,
            `slot_time_to`=:to_time,
            `slot_special_text`=:text,
            `slot_title`=:title,
            `slot_description`=:description,
            `slot_price`=:price,
            is_multiple=:is_multiple,
            min_seat=:min_seat,
            max_seat=:max_seat,
            total_seat=:total_seat,
            available_seat=:available_seat, 
            last_update_by=:user_id  
            WHERE slot_id=:slot_id";
            $params = array(
                ':date' => $date, ':from_time' => $from_time, ':to_time' => $to_time,
                ':text' => $text, ':title' => $title, ':description' => $description, ':price' => $price,
                ':slot_id' => $slot_id, ':is_multiple' => $is_multiple,
                ':total_seat' => $unitavailable, ':available_seat' => $available_seat, ':min_seat' => $min_seat,
                ':max_seat' => $max_seat, ':user_id' => $user_id
            );
            $this->db->exec($sql, $params);

            $sql = "UPDATE `booking_slots` 
            SET `slot_available` ='1'
            WHERE available_seat >0
            AND slot_id=:slot_id";
            $params = [':slot_id' => $slot_id];
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB1008]Error while creating booking calendar Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function updateSlotsStatus($transaction_id)
    {
        try {
            $sql = "call update_booking_status(:transaction_id)";
            $params = array(':transaction_id' => $transaction_id);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB1008]Error while creating booking calendar Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function createSlot(
        $merchant_id,
        $slot_special_text,
        $slot_special_mode,
        $slot_date,
        $slot_time_from,
        $slot_time_to,
        $slot_price,
        $is_multiple,
        $unitavailable,
        $min_seat,
        $max_seat,
        $calendar_id,
        $user_id,
        $package_id = null,
        $slot_title,
        $slot_description,
        $slot_isprimary = '0'
    ) {
        try {

            $cat = $this->getCalendarDetails($calendar_id);
            $sql = "INSERT INTO `booking_slots`(`merchant_id`,`slot_special_text`,`slot_special_mode`,`slot_date`,`slot_time_from`,
            `slot_time_to`,`slot_price`,`is_primary`,`is_multiple`,`total_seat`,`available_seat`,`min_seat`,`max_seat`,`calendar_id`,
            `category_id`,`created_by`,`created_date`,`last_update_by`,`package_id`,`slot_title`,`slot_description`)
            VALUES(:merchant_id,:slot_special_text,:slot_special_mode,:slot_date,:slot_time_from,
            :slot_time_to,:slot_price,:is_primary,:is_multiple,:available_seat,:available_seat,:min_seat,:max_seat,:calendar_id,
            :category_id,:user_id,CURRENT_TIMESTAMP(),:user_id, :package_id, :slot_title, :slot_description);
";
            $params = array(
                ':merchant_id' => $merchant_id, ':slot_special_text' => $slot_special_text, ':slot_special_mode' => $slot_special_mode,
                ':slot_date' => $slot_date, ':slot_time_from' => $slot_time_from, ':slot_time_to' => $slot_time_to, ':slot_price' => $slot_price,
                ':is_primary' => $slot_isprimary, ':is_multiple' => $is_multiple, ':available_seat' => $unitavailable, ':min_seat' => $min_seat, ':max_seat' => $max_seat,
                ':calendar_id' => $calendar_id, ':category_id' => $cat['category_id'], ':user_id' => $user_id, ':package_id' => $package_id, ':slot_title' => $slot_title,
                ':slot_description' => $slot_description
            );
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB1009]Error while creating booking calendar params : ' . json_encode($params) . ' Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function isExistTemplate($name, $merchant_id)
    {
        try {
            $sql = 'select category_id from booking_categories WHERE category_name=:name and merchant_id=:merchant_id and is_active=1';
            $params = array(':name' => $name, ':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $result = $this->db->single();
            if (!empty($result)) {
                return TRUE;
            }
            $this->db->closeStmt();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB1010]Error while checking category exist Error:for param [' . json_encode($params) . '] ' . $e->getMessage());
        }
    }

    public function isExistCalendar($name, $category_id)
    {
        try {
            $sql = 'select calendar_id from booking_calendars WHERE calendar_title=:name and category_id=:category_id and is_active=1';
            $params = array(':name' => $name, ':category_id' => $category_id);
            $this->db->exec($sql, $params);
            $result = $this->db->single();
            if (!empty($result)) {
                return TRUE;
            }
            $this->db->closeStmt();
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB1011]Error while checking category exist Error:for param [' . json_encode($params) . '] ' . $e->getMessage());
        }
    }

    public function getDefaultCalendar($merchant_id, $category_id)
    {
        try {
            $sql = 'select calendar_id from booking_calendars WHERE category_id=:category_id and merchant_id=:merchant_id and is_active=1 and calendar_active=1 order by calendar_order limit 1';
            $params = array(':category_id' => $category_id, ':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $result = $this->db->single();
            if (!empty($result)) {
                return $result['calendar_id'];
            } else {
                return 0;
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB1012]Error while get default calendar id Error:for param [' . json_encode($params) . '] ' . $e->getMessage());
        }
    }

    public function getCalendarSlots($merchant_id, $category_id, $calendar_id)
    {
        try {
            $sql = "select count(slot_time_from) as count_slot,slot_date,DATE_FORMAT(slot_date,'%d') as slot_day,DATE_FORMAT(slot_date,'%m') as slot_month,DATE_FORMAT(slot_date,'%Y') as slot_year,calendar_id from booking_slots WHERE merchant_id=:merchant_id and slot_available=1 and is_active=1 and calendar_id=:calendar_id and 
                slot_date>=DATE_FORMAT(NOW(),'%Y-%m-%d') group by slot_date order by slot_date";
            $params = array(':merchant_id' => $merchant_id, ':calendar_id' => $calendar_id);
            $this->db->exec($sql, $params);
            $result = $this->db->resultset();
            return $result;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB1013]Error while get default calendar id Error:for param [' . json_encode($params) . '] ' . $e->getMessage());
        }
    }

    public function getSlots($date, $calendar_id)
    {
        try {
            $sql = "select slot_id,is_multiple,min_seat,max_seat,total_seat,available_seat,TIME_FORMAT(slot_time_from, '%h:%i %p') as time_from,
            slot_title, b.package_name,
            TIME_FORMAT(slot_time_to, '%h:%i %p') as time_to,slot_special_text,slot_price 
            from booking_slots a 
            join booking_packages b on a.package_id = b.package_id
            WHERE slot_available=1 
            and a.is_active=1 and calendar_id=:calendar_id and slot_date=:date ";
            $params = array(':date' => $date, ':calendar_id' => $calendar_id);
            $this->db->exec($sql, $params);
            $result = $this->db->resultset();
            return $result;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB1014]Error while get default calendar id Error:for param [' . json_encode($params) . '] ' . $e->getMessage());
        }
    }

    public function getSlotsv2($date, $calendar_id, $package_id)
    {
        try {
            $sql = "SELECT  group_concat(is_multiple) is_multiple,
            group_concat(min_seat) min_seat,
            group_concat(max_seat) max_seat,
            group_concat(total_seat) total_seat,
            group_concat(available_seat) available_seat,
            group_concat(slot_special_text) slot_special_text,
                        TIME_FORMAT(slot_time_from, '%h:%i %p') as time_from,
                        TIME_FORMAT(slot_time_to, '%h:%i %p') as time_to,
                        count(slot_price) slot_price_count,
                        group_concat(slot_title) slot_title,
                        group_concat(if(slot_description is null,'',slot_description)) slot_description,
                        group_concat(slot_price) slot_price,
                        group_concat(slot_id) slot_id
            FROM booking_slots a
            WHERE calendar_id=:calendar_id 
            AND slot_date=:date 
            AND slot_available=1
            AND a.is_active=1
            AND a.package_id=:package_id 
            GROUP BY time_from, time_to";
            $params = array(':date' => $date, ':calendar_id' => $calendar_id, ':package_id' => $package_id);

            $this->db->exec($sql, $params);
            $result = $this->db->resultset();
            return $result;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB1014]Error while get default calendar id Error:for param [' . json_encode($params) . '] ' . $e->getMessage());
        }
    }

    public function getSlotCount($date, $calendar_id)
    {
        try {
            $sql = "select count(slot_id) as scount from booking_slots WHERE slot_available=1 and is_active=1 and calendar_id=:calendar_id and slot_date=:date;";
            $params = array(':date' => $date, ':calendar_id' => $calendar_id);
            $this->db->exec($sql, $params);
            $result = $this->db->single();
            return $result['scount'];
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB1015]Error while get default calendar id Error:for param [' . json_encode($params) . '] ' . $e->getMessage());
        }
    }

    public function getCategorySlots($date, $category_id)
    {
        try {
            $sql = "select distinct TIME_FORMAT(slot_time_from, '%h:%i %p') as time_from,TIME_FORMAT(slot_time_to, '%h:%i %p') as time_to from booking_slots b inner join booking_calendars c on b.calendar_id=c.calendar_id WHERE slot_available=1 and b.is_active=1 and c.is_active=1 and b.category_id=:category_id and slot_date=:date and c.calendar_active=1 order by slot_time_from;";
            $params = array(':date' => $date, ':category_id' => $category_id);
            $this->db->exec($sql, $params);
            $result = $this->db->resultset();
            return $result;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB1016]Error while get default calendar id Error:for param [' . json_encode($params) . '] ' . $e->getMessage());
        }
    }

    public function getSlotCategoryCourt($date, $from_time, $to_time, $category_id)
    {
        try {
            $sql = "select calendar_title,b.calendar_id,logo from booking_slots b inner join booking_calendars c on b.calendar_id=c.calendar_id WHERE b.slot_available=1 and b.is_active=1 and c.is_active=1 and c.calendar_active=1 and b.category_id=:category_id and b.slot_date=:date and b.slot_time_from=:from_time and b.slot_time_to=:to_time;";
            $params = array(':date' => $date, ':category_id' => $category_id, ':from_time' => $from_time, ':to_time' => $to_time);
            $this->db->exec($sql, $params);
            $result = $this->db->resultset();
            return $result;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB1017]Error while get default calendar id Error:for param [' . json_encode($params) . '] ' . $e->getMessage());
        }
    }

    public function getSlotCategoryCourtID($date, $from_time, $to_time, $category_id, $calendar_id)
    {
        try {
            $sql = "select slot_id from booking_slots b inner join booking_calendars c on b.calendar_id=c.calendar_id WHERE b.slot_available=1 and b.is_active=1 and b.category_id=:category_id and b.calendar_id=:calendar_id and b.slot_date=:date and b.slot_time_from=:from_time and b.slot_time_to=:to_time;";
            $params = array(':date' => $date, ':category_id' => $category_id, ':calendar_id' => $calendar_id, ':from_time' => $from_time, ':to_time' => $to_time);
            $this->db->exec($sql, $params);
            $result = $this->db->single();
            return $result['slot_id'];
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB1018]Error while get default calendar id Error:for param [' . json_encode($params) . '] ' . $e->getMessage());
        }
    }

    public function getCategoryCourt($date, $category_id)
    {
        try {
            $sql = "select distinct calendar_title,c.logo,b.calendar_id,c.max_booking from booking_slots b inner join booking_calendars c on b.calendar_id=c.calendar_id WHERE b.slot_available=1 and c.calendar_active=1 and b.is_active=1 and c.is_active=1 and b.category_id=:category_id and b.slot_date=:date;";
            $params = array(':date' => $date, ':category_id' => $category_id);
            $this->db->exec($sql, $params);
            $result = $this->db->resultset();
            return $result;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB1019]Error while get default calendar id Error:for param [' . json_encode($params) . '] ' . $e->getMessage());
        }
    }

    public function getBookingSlots($calendar_id, $from_date, $to_date)
    {
        try {
            $sql = "select * from booking_slots WHERE is_active=1 and calendar_id=:calendar_id and 
                slot_date>=DATE_FORMAT(:from_date,'%Y-%m-%d') and slot_date<=DATE_FORMAT(:to_date,'%Y-%m-%d')";
            $params = array(':calendar_id' => $calendar_id, ':from_date' => $from_date, ':to_date' => $to_date);
            $this->db->exec($sql, $params);
            $result = $this->db->resultset();
            return $result;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB1020]Error while get default calendar id Error:for param [' . json_encode($params) . '] ' . $e->getMessage());
        }
    }

    public function getBookingSlotsv2($calendar_id, $from_date, $to_date)
    {
        try {
            $sql = "SELECT a.*, b.package_name
            from booking_slots a
            JOIN booking_packages b on a.package_id = b.package_id
            WHERE a.is_active=1 
            and a.calendar_id=:calendar_id
            and a.slot_date>=DATE_FORMAT(:from_date,'%Y-%m-%d') and a.slot_date<=DATE_FORMAT(:to_date,'%Y-%m-%d')";
            $params = array(':calendar_id' => $calendar_id, ':from_date' => $from_date, ':to_date' => $to_date);
            $this->db->exec($sql, $params);
            $result = $this->db->resultset();
            return $result;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB1020]Error while get default calendar id Error:for param [' . json_encode($params) . '] ' . $e->getMessage());
        }
    }



    public function isExistSlot($calendar_id, $date, $from_time, $to_time)
    {
        try {
            $sql = "select count(slot_id) as counts from booking_slots where calendar_id=:calendar_id and is_active=1 and slot_date=:date
                and (slot_time_from < :from_time AND slot_time_to > :from_time OR slot_time_from < :to_time AND slot_time_to > :to_time OR  slot_time_from = :from_time OR slot_time_to = :to_time)";
            $params = array(':calendar_id' => $calendar_id, ':date' => $date, ':from_time' => $from_time, ':to_time' => $to_time);
            $this->db->exec($sql, $params);
            $result = $this->db->single();
            if ($result['counts'] > 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB1021]Error while get getHolidays Error:for param [' . json_encode($params) . '] ' . $e->getMessage());
        }
    }

    public function isExistSlotv2($calendar_id, $date, $from_time, $to_time, $package_id, $slot_title)
    {
        try {
            $sql = "select count(slot_id) as counts from booking_slots 
            where calendar_id=:calendar_id 
            and is_active=1 
            and slot_date=:date
            and package_id =:package_id
            and slot_title = :slot_title
            and (slot_time_from < :from_time AND slot_time_to > :from_time OR slot_time_from < :to_time 
            AND slot_time_to > :to_time OR  slot_time_from = :from_time OR slot_time_to = :to_time)";
            $params = array(
                ':calendar_id' => $calendar_id, ':date' => $date,
                ':from_time' => $from_time, ':to_time' => $to_time,
                ':package_id' => $package_id, ':slot_title' => $slot_title
            );
            $this->db->exec($sql, $params);
            $result = $this->db->single();
            if ($result['counts'] > 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB1021]Error while get getHolidays Error:for param [' . json_encode($params) . '] ' . $e->getMessage());
        }
    }

    public function isExistSlotUpdate($calendar_id, $date, $from_time, $to_time, $slot_id, $package_id, $slot_title)
    {
        try {
            $sql = "select count(slot_id) as counts 
            from booking_slots 
            where calendar_id=:calendar_id 
            and is_active=1 
            and slot_date=:date
            and (slot_time_from < :from_time AND slot_time_to > :from_time 
            OR slot_time_from < :to_time AND slot_time_to > :to_time 
            OR  slot_time_from = :from_time OR slot_time_to = :to_time) 
            and slot_id<> :slot_id
            and package_id = :package_id
            and slot_title = :slot_title";
            $params = array(
                ':calendar_id' => $calendar_id, ':date' => $date,
                ':from_time' => $from_time, ':to_time' => $to_time, ':slot_id' => $slot_id, ':package_id' => $package_id, ':slot_title' => $slot_title
            );
            $this->db->exec($sql, $params);
            $result = $this->db->single();
            if ($result['counts'] > 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB1021]Error while get getHolidays Error:for param [' . json_encode($params) . '] ' . $e->getMessage());
        }
    }

    public function getHolidays($calendar_id)
    {
        try {
            $sql = "select * from booking_holidays where calendar_id=:calendar_id and is_active=1";
            $params = array(':calendar_id' => $calendar_id);
            $this->db->exec($sql, $params);
            $result = $this->db->resultset();
            return $result;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB1022]Error while get getHolidays Error:for param [' . json_encode($params) . '] ' . $e->getMessage());
        }
    }

    public function getCategoryCalendar($merchant_id, $category_id)
    {
        try {
            $sql = 'select calendar_id,calendar_title from booking_calendars WHERE category_id=:category_id and merchant_id=:merchant_id and calendar_active=1 and is_active=1 order by calendar_order';
            $params = array(':category_id' => $category_id, ':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $result = $this->db->resultset();
            return $result;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB1023]Error while get default calendar id Error:for param [' . json_encode($params) . '] ' . $e->getMessage());
        }
    }

    public function getMerchantCategoryDates($merchant_id, $category_id)
    {
        try {
            $sql = 'select distinct slot_date from booking_slots WHERE category_id=:category_id and '
                . 'merchant_id=:merchant_id and is_active=1 and slot_available=1  and slot_date>=CURDATE() order by slot_date';
            $params = array(':category_id' => $category_id, ':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            foreach ($rows as $row) {
                $date = new DateTime($row['slot_date']);
                $result[] = $date->format('d M Y');
            }
            return $result;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB1023]Error while get default calendar id Error:for param [' . json_encode($params) . '] ' . $e->getMessage());
        }
    }

    public function getCategoryDates($merchant_id, $category_id)
    {
        try {
            $sql = 'select distinct slot_date from booking_slots WHERE category_id=:category_id and '
                . 'merchant_id=:merchant_id and is_active=1 and slot_available=1 and slot_date>=CURDATE() order by slot_date';
            $params = array(':category_id' => $category_id, ':merchant_id' => $merchant_id);
            $this->db->exec($sql, $params);
            $rows = $this->db->resultset();
            foreach ($rows as $row) {
                $date = new DateTime($row['slot_date']);
                $m = intval($date->format('m')) - 1;
                $result[] = 'new Date(' . $date->format('Y') . ',' . $m . ',' . $date->format('d') . ')';
            }
            return $result;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB1023]Error while get default calendar id Error:for param [' . json_encode($params) . '] ' . $e->getMessage());
        }
    }

    public function getCalendarCategory($calendar_id)
    {
        try {
            $sql = 'select calendar_title,capture_details,booking_unit,category_name, confirmation_message , tandc ,
            cancellation_policy
            from booking_calendars l 
            inner join booking_categories c on l.category_id=c.category_id 
            WHERE calendar_id=:calendar_id';
            $params = array(':calendar_id' => $calendar_id);
            $this->db->exec($sql, $params);
            $result = $this->db->single();
            return $result;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB1024]Error while get default calendar id Error:for param [' . json_encode($params) . '] ' . $e->getMessage());
        }
    }

    public function getCalendarDetails($calendar_id)
    {
        try {
            $sql = 'select * from booking_calendars WHERE calendar_id=:calendar_id';
            $params = array(':calendar_id' => $calendar_id);
            $this->db->exec($sql, $params);
            $result = $this->db->single();
            return $result;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB1025]Error while get default calendar id Error:for param [' . json_encode($params) . '] ' . $e->getMessage());
        }
    }

    function updateNotification(
        $merchant_id,
        $calendar_id,
        $notification_email,
        $notification_mobile,
        $capture,
        $user_id,
        $cancellation_type = '1',
        $can_days = '0',
        $can_hours = '0'
    ) {
        try {
            $sql = "UPDATE `booking_calendars` SET `notification_email` = :notification_email 
            ,`notification_mobile`=:notification_mobile,`cancellation_type`=:cancellation_type,`cancellation_days`=:cancellation_days,
            `cancellation_hours`=:cancellation_hours,`capture_details`=:capture_details,last_update_by=:user_id
            WHERE merchant_id=:merchant_id and calendar_id=:calendar_id";
            $params = array(
                ':notification_email' => $notification_email, ':notification_mobile' => $notification_mobile,
                ':cancellation_type' => $cancellation_type, ':cancellation_days' => $can_days, ':cancellation_hours' => $can_hours,
                ':capture_details' => $capture, ':user_id' => $user_id, ':merchant_id' => $merchant_id, ':calendar_id' => $calendar_id
            );
            $this->db->exec($sql, $params);
            $this->db->closeStmt();
            return true;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB1026]Error while update bulk upload status Error: for bulk id [' . $bulk_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function updateCalendar($merchant_id, $calendar_id, $max_booking, $category, $calendar_name, $description, $booking_unit, $logo, $user_id)
    {
        try {
            $sql = "UPDATE `booking_calendars` SET `category_id` = :category_id ,`calendar_title`=:name,`booking_unit`=:booking_unit,`max_booking`=:max_booking,`description`=:description,`logo`=:logo, last_update_by=:user_id WHERE merchant_id=:merchant_id and calendar_id=:calendar_id";
            $params = array(':category_id' => $category, ':name' => $calendar_name, ':booking_unit' => $booking_unit, ':max_booking' => $max_booking, ':description' => $description, ':logo' => $logo, ':user_id' => $user_id, ':merchant_id' => $merchant_id, ':calendar_id' => $calendar_id);
            $this->db->exec($sql, $params);
            $this->db->closeStmt();
            return true;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB1026]Error while update bulk upload status Error: for bulk id [' . $bulk_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    function save_slot_transaction($type, $customer_id, $merchant_id, $user_id, $amount, $price, $bank_id, $date, $bank_ref_no, $cheque_no, $cash_paid_to, $response_type, $seat, $narrative)
    {
        try {
            $cheque_no = ($cheque_no > 0) ? $cheque_no : 0;
            $sql = "call save_slot_offline_transaction(:type,:customer_id,:merchant_id,:user_id,:amount,:price,:bank_id,:date,:bank_ref_no,:cheque_no,:cash_paid_to,:response_type,:seat,:narrative)";
            $params = array(':type' => $type, ':customer_id' => $customer_id, ':merchant_id' => $merchant_id, ':user_id' => $user_id, ':amount' => $amount, ':price' => $price, ':bank_id' => $bank_id, ':date' => $date, ':bank_ref_no' => $bank_ref_no, ':cheque_no' => $cheque_no, ':cash_paid_to' => $cash_paid_to, ':response_type' => $response_type, ':seat' => $seat, ':narrative' => $narrative);
            $this->db->exec($sql, $params);
            $result = $this->db->single();
            $this->db->closeStmt();
            return $result['transaction_id'];
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB1026]Error while update bulk upload status Error: for bulk id [' . $bulk_id . '] ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function saveBookingTransactionDetails($transaction_id, $calendar_id, $calendar_date, $slot_id, $slot, $category_name, $calendar_title, $amount, $coupon_code, $user_id, $is_paid = 0)
    {
        try {
            $sql = "INSERT INTO `booking_transaction_detail`
            (`transaction_id`,`calendar_id`,`calendar_date`,`slot_id`,`slot`,`category_name`,`calendar_title`,`amount`,`is_paid`,`coupon_code`,`created_by`,`created_date`,`last_update_by`)
            VALUES(:transaction_id,:calendar_id,:calendar_date,:slot_id,:slot,:category_name,:calendar_title,:amount,:is_paid,:coupon_code,:user_id,CURRENT_TIMESTAMP(),:user_id)";
            $params = array(':transaction_id' => $transaction_id, ':calendar_id' => $calendar_id, ':calendar_date' => $calendar_date, ':slot_id' => $slot_id, ':slot' => $slot, ':category_name' => $category_name, ':calendar_title' => $calendar_title, ':amount' => $amount, ':is_paid' => $is_paid, ':coupon_code' => $coupon_code, ':user_id' => $user_id);
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E105-127]Error while saveBookingTransactionDetails Json: ' . json_encode($params) . ' value Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function saveBookingMembershipDetails($transaction_id, $merchant_id, $user_id, $customer_id, $category_id, $membership_id, $days, $start_date, $description, $amount, $status)
    {
        try {
            $sql = "INSERT INTO `customer_membership`(`transaction_id`,`merchant_id`,`user_id`,`customer_id`,
`category_id`,`membership_id`,`start_date`,`end_date`,`days`,`description`,`amount`,status,`created_by`,`created_date`,`last_update_by`)
VALUES(:transaction_id,:merchant_id,:user_id,:customer_id,:category_id,:membership_id,:start_date,DATE_ADD(:start_date, INTERVAL " . $days . " DAY)," . $days . ",:description,:amount,:status,:user_id,CURRENT_TIMESTAMP(),:user_id)";
            $params = array(
                ':transaction_id' => $transaction_id, ':merchant_id' => $merchant_id, ':user_id' => $user_id, ':customer_id' => $customer_id, ':category_id' => $category_id,
                ':membership_id' => $membership_id, ':description' => $description, ':amount' => $amount, ':status' => $status, ':start_date' => $start_date, ':user_id' => $user_id
            );
            $this->db->exec($sql, $params);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E105-127]Error while saveBookingTransactionDetails Json: ' . json_encode($params) . ' value Error: ' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function getmembershipStartDate($category_id, $customer_id)
    {
        $sql = "select end_date from customer_membership where customer_id=:customer_id and category_id=:category_id and end_date>now();";
        $params = array(':customer_id' => $customer_id, ':category_id' => $category_id);
        $this->db->exec($sql, $params);
        $result = $this->db->single();
        if (empty($result)) {
            return date('Y-m-d');
        } else {
            return $result['end_date'];
        }
    }

    public function uploadImage($image_file)
    {
        try {
            $filename = basename($image_file['name']);
            $name = 'booking_' . time();
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
                return '/' . $newname;
            }
            return '';
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E136]Error while uploading template logo Error: ' . $e->getMessage());
        }
    }

    public function getAllPackages($date, $calendar_id)
    {
        try {
            $sql = "SELECT distinct b.package_id, b.package_name, b.package_desc,  b.package_image
            FROM booking_slots a
            JOIN booking_packages b on a.package_id  = b.package_id
            WHERE slot_available=1
            AND a.is_active=1 
            AND calendar_id=:calendar_id 
            AND slot_date=:date ";
            $params = array(':date' => $date, ':calendar_id' => $calendar_id);
            $this->db->exec($sql, $params);
            $result = $this->db->resultset();
            return $result;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB1014]Error while get default calendar id Error:for param [' . json_encode($params) . '] ' . $e->getMessage());
        }
    }

    public function getPackagesbyID($date, $calendar_id, $package_id)
    {
        try {
            $sql = "SELECT distinct b.package_id, b.package_name, b.package_desc
            FROM booking_slots a
            JOIN booking_packages b on a.package_id  = b.package_id
            WHERE slot_available=1
            AND a.is_active=1 
            AND calendar_id=:calendar_id 
            AND slot_date=:date
            AND a.package_id=:package_id ";
            $params = array(':date' => $date, ':calendar_id' => $calendar_id, ':package_id' => $package_id);
            $this->db->exec($sql, $params);
            $result = $this->db->resultset();
            return $result;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB1014]Error while get default calendar id Error:for param [' . json_encode($params) . '] ' . $e->getMessage());
        }
    }

    public function getPackagesbyCalendarID($calendar_id)
    {
        try {
            $sql = "SELECT distinct b.package_id, b.package_name, b.package_desc
            FROM booking_slots a
            JOIN booking_packages b on a.package_id  = b.package_id
            WHERE slot_available=1
            AND a.is_active=1 
            AND calendar_id=:calendar_id";
            $params = array(':calendar_id' => $calendar_id);
            $this->db->exec($sql, $params);
            $result = $this->db->resultset();
            return $result;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB1014]Error while get default calendar id Error:for param [' . json_encode($params) . '] ' . $e->getMessage());
        }
    }

    public function getPackageDetails($package_id)
    {
        try {
            $sql = "SELECT * 
            FROM booking_packages
            WHERE  package_id=:package_id";
            $params = array(':package_id' => $package_id);
            $this->db->exec($sql, $params);
            $result = $this->db->resultset();
            return $result;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB1014]Error while get default calendar id Error:for param [' . json_encode($params) . '] ' . $e->getMessage());
        }
    }

    public function getAvailableSlotbySlotID($slot_id)
    {
        try {
            $sql = 'SELECT * from booking_slots where slot_id=:slot_id';
            $params = array(':slot_id' => $slot_id);
            $this->db->exec($sql, $params);
            $result = $this->db->single();
            return $result;
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB1024]Error while get default calendar id Error:for param [' . json_encode($params) . '] ' . $e->getMessage());
        }
    }
}
