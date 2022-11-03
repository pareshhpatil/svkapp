<?php

class Bookings extends Controller
{

    function __construct()
    {
        parent::__construct();
        //TODO : Check if using static function is causing any problems!
        $this->validateSession('merchant', 5);
        $this->view->selectedMenu = array(9);
    }

    function categories()
    {
        try {
            $this->hasRole(1, 18);
            $list = $this->model->getBookingCategories($this->merchant_id);
            $list = $this->generic->getEncryptedList($list, 'encrypted_id', 'category_id');
            $this->smarty->assign("list", $list);
            $this->smarty->assign("title", "Category list");
            $this->view->title = "Booking calendar category list";

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Booking calendar', 'url' => ''),
                array('title' => 'Configuration', 'url' => ''),
                array('title' => 'Category list', 'url' => '')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->datatablejs = 'table-small';
            $this->view->selectedMenu = array(9, 42, 86);
            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/booking/category/list.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB001]Error while listing gategories Error: for merchant id [' . $this->merchant_id . '] ' . $e->getMessage());
        }
    }


    function packages()
    {
        try {
            $this->hasRole(1, 18);
            $list = $this->model->getBookingPackages($this->user_id);
            $list = $this->generic->getEncryptedList($list, 'encrypted_id', 'package_id');
            $this->smarty->assign("list", $list);
            $this->smarty->assign("title", "Package list");
            $this->view->title = "Booking calendar package list";

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Booking calendar', 'url' => ''),
                array('title' => 'Configuration', 'url' => ''),
                array('title' => 'Package list', 'url' => '')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->datatablejs = 'table-small';
            $this->view->selectedMenu = array(9, 42, 173);
            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/booking/Package/list.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB001]Error while listing Package Error: for merchant id [' . $this->merchant_id . '] ' . $e->getMessage());
        }
    }

    function membership()
    {
        try {
            $this->validatePackage();
            $this->hasRole(1, 18);
            $list = $this->common->getListValue('booking_membership', 'merchant_id', $this->merchant_id, 1);
            $list = $this->generic->getEncryptedList($list, 'encrypted_id', 'membership_id');

            $this->smarty->assign("list", $list);
            $this->smarty->assign("title", "Membership list");
            $this->view->title = "Membership list";

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Booking calendar', 'url' => ''),
                array('title' => 'Configuration', 'url' => ''),
                array('title' => 'Membership list', 'url' => '')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->datatablejs = 'table-small';
            $this->view->selectedMenu = array(9, 42, 87);
            $this->view->header_file = ['list'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/booking/membership/list.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB001]Error while listing gategories Error: for merchant id [' . $this->merchant_id . '] ' . $e->getMessage());
        }
    }

    function addmembership()
    {
        try {
            $this->validatePackage();
            $this->hasRole(2, 18);
            $list = $this->model->getBookingCategories($this->merchant_id);
            $list = $this->generic->getEncryptedList($list, 'encrypted_id', 'category_id');
            $this->smarty->assign("category_list", $list);
            $this->smarty->assign("title", "Add membership");
            $this->view->title = "Add membership";

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Booking calendar', 'url' => ''),
                array('title' => 'Configuration', 'url' => ''),
                array('title' => 'Membership list', 'url' => '/merchant/bookings/membership'),
                array('title' => $this->view->title, 'url' => '')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->datatablejs = 'table-small';
            $this->view->selectedMenu = array(9, 42, 87);
            $this->view->header_file = ['booking'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/booking/membership/create.tpl');
            $this->view->render('footer/booking');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB001]Error while listing gategories Error: for merchant id [' . $this->merchant_id . '] ' . $e->getMessage());
        }
    }

    function addcategory()
    {
        try {
            $this->hasRole(2, 18);
            $list = $this->model->getBookingCategories($this->merchant_id);
            $list = $this->generic->getEncryptedList($list, 'encrypted_id', 'category_id');
            $this->smarty->assign("list", $list);
            $this->smarty->assign("title", "Add category");
            $this->view->title = "Booking categories";

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Booking calendar', 'url' => ''),
                array('title' => 'Configuration', 'url' => ''),
                array('title' => 'Category list', 'url' => '/merchant/bookings/categories'),
                array('title' => 'Add category', 'url' => '')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->datatablejs = 'table-small';
            $this->view->selectedMenu = array(9, 42, 86);
            $this->setAjaxDatatableSession();
            $this->view->header_file = ['booking'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/booking/category/create.tpl');
            $this->view->render('footer/booking');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB001]Error while listing gategories Error: for merchant id [' . $this->merchant_id . '] ' . $e->getMessage());
        }
    }

    function addpackage()
    {
        try {
            $this->hasRole(2, 18);
            $list = $this->model->getBookingPackages($this->user_id);
            $list = $this->generic->getEncryptedList($list, 'encrypted_id', 'package_id');
            $this->smarty->assign("list", $list);
            $this->smarty->assign("title", "Add package");
            $this->view->title = "Booking Packages";

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Booking calendar', 'url' => ''),
                array('title' => 'Configuration', 'url' => ''),
                array('title' => 'Package list', 'url' => '/merchant/bookings/packages'),
                array('title' => 'Add Package', 'url' => '')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->datatablejs = 'table-small';
            $this->view->selectedMenu = array(9, 42, 86);
            $this->setAjaxDatatableSession();
            $this->view->header_file = ['booking'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/booking/package/create.tpl');
            $this->view->render('footer/booking');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB001]Error while listing gategories Error: for merchant id [' . $this->merchant_id . '] ' . $e->getMessage());
        }
    }

    function savecategory($type = null)
    {
        try {
            if (empty($_POST)) {
                header("Location:/merchant/bookings/categories");
            }

            require_once CONTROLLER . 'merchant/Bookingvalidator.php';
            $validator = new Bookingvalidator($this->model);
            $validator->validateCategorySave($this->merchant_id);
            $hasErrors = $validator->fetchErrors();
            $membership = ($_POST['membership'] > 0) ? 1 : 0;
            if ($hasErrors == false) {
                $id = $this->model->createCategory($_POST['category_name'], $membership, $this->merchant_id, $this->user_id);
                $array['status'] = 1;
                $array['id'] = $id;
                $array['name'] = $_POST['category_name'];
                if ($type != null) {
                    $this->session->set('successMessage', 'Category has been added.');
                    header("Location:/merchant/bookings/categories");
                    die();
                }
            } else {
                if ($type != null) {
                    $this->smarty->assign("haserrors", $hasErrors);
                    $this->addcategory();
                    die();
                }
                $array['status'] = 0;
                $array['error'] = $hasErrors['category_name'][0] . ': ' . $hasErrors['category_name'][1];
            }
            echo json_encode($array);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB002]Error while creating supplier Error: ' . $e->getMessage());
            header("Location:/error");
        }
    }

    function savepackage($type = null)
    {
        try {
            if (empty($_POST)) {
                header("Location:/merchant/bookings/packages");
            }

            //require_once CONTROLLER . 'merchant/Bookingvalidator.php';
            //$validator = new Bookingvalidator($this->model);
            //$validator->validateCategorySave($this->merchant_id);
            //$hasErrors = $validator->fetchErrors();
            $membership = ($_POST['membership'] > 0) ? 1 : 0;
            $hasErrors = false;
            if ($hasErrors == false) {
                $id = $this->model->createPackage($_POST['package_name'], $_POST['package_desc'], $this->user_id);
                $array['status'] = 1;
                $array['id'] = $id;
                $array['name'] = $_POST['package_name'];
                if ($type != null) {
                    $this->session->set('successMessage', 'PACKAGE has been added.');
                    header("Location:/merchant/bookings/packages");
                    die();
                }
            }
            echo json_encode($array);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB002]Error while creating supplier Error: ' . $e->getMessage());
            header("Location:/error");
        }
    }

    function savemembership()
    {
        try {
            require_once CONTROLLER . 'merchant/Bookingvalidator.php';
            $validator = new Bookingvalidator($this->model);
            $validator->validateMembershipSave($this->merchant_id);
            $hasErrors = $validator->fetchErrors();
            if ($hasErrors == false) {
                $id = $this->model->createMembership($_POST['title'], $_POST['category'], $_POST['days'], $_POST['amount'], $_POST['description'], $this->merchant_id, $this->user_id);
                $this->session->set('successMessage', 'Membership has been added.');
                header("Location:/merchant/bookings/membership");
            } else {
                $this->smarty->assign("haserrors", $hasErrors);
                $this->addmembership();
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB002]Error while creating supplier Error: ' . $e->getMessage());
            header("Location:/error");
        }
    }

    function updatemembershipsave()
    {
        try {
            require_once CONTROLLER . 'merchant/Bookingvalidator.php';
            $validator = new Bookingvalidator($this->model);
            $validator->validateMembershipSave($this->merchant_id);
            $hasErrors = $validator->fetchErrors();
            if ($hasErrors == false) {
                $id = $this->model->updateMembership($_POST['membership_id'], $_POST['title'], $_POST['category'], $_POST['days'], $_POST['amount'], $_POST['description'], $this->user_id);
                $this->session->set('successMessage', 'Membership has been updated.');
                header("Location:/merchant/bookings/membership");
            } else {
                $this->smarty->assign("haserrors", $hasErrors);
                $this->addmembership();
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB002]Error while creating supplier Error: ' . $e->getMessage());
            header("Location:/error");
        }
    }

    function updatecategory($link)
    {
        try {
            $id = $this->encrypt->decode($link);
            $this->hasRole(2, 18);

            $detail = $this->common->getSingleValue('booking_categories', 'category_id', $id);

            $this->smarty->assign("detail", $detail);
            $this->smarty->assign("title", "Update category");
            $this->view->title = "Update category";

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Booking calendar', 'url' => ''),
                array('title' => 'Configuration', 'url' => ''),
                array('title' => 'Category list', 'url' => '/merchant/bookings/categories'),
                array('title' => $detail['category_name'], 'url' => '')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->datatablejs = 'table-small';
            $this->view->selectedMenu = array(9, 42, 86);
            $this->setAjaxDatatableSession();
            $this->view->header_file = ['booking'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/booking/category/update.tpl');
            $this->view->render('footer/booking');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB003]Error while updatecategory Error: ' . $e->getMessage());
            header("Location:/error");
        }
    }

    function updatepackage($link)
    {
        try {
            $id = $this->encrypt->decode($link);
            $this->hasRole(2, 18);

            $detail = $this->common->getSingleValue('booking_packages', 'package_id', $id);

            $this->smarty->assign("detail", $detail);
            $this->smarty->assign("title", "Update package");
            $this->view->title = "Update package";

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Booking calendar', 'url' => ''),
                array('title' => 'Configuration', 'url' => ''),
                array('title' => 'package list', 'url' => '/merchant/bookings/packages'),
                array('title' => $detail['package_name'], 'url' => '')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->datatablejs = 'table-small';
            $this->view->selectedMenu = array(9, 42, 86);
            $this->setAjaxDatatableSession();
            $this->view->header_file = ['booking'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/booking/package/update.tpl');
            $this->view->render('footer/booking');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB003]Error while updatepackage Error: ' . $e->getMessage());
            header("Location:/error");
        }
    }

    function updatemembership($link)
    {
        try {
            $id = $this->encrypt->decode($link);
            $this->hasRole(2, 18);
            $detail = $this->common->getSingleValue('booking_membership', 'membership_id', $id);
            $list = $this->model->getBookingCategories($this->merchant_id);
            $this->smarty->assign("category_list", $list);
            $this->smarty->assign("detail", $detail);
            $this->smarty->assign("title", "Update membership");
            $this->view->title = "Update membership";

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Booking calendar', 'url' => ''),
                array('title' => 'Configuration', 'url' => ''),
                array('title' => 'Membership list', 'url' => '/merchant/bookings/membership'),
                array('title' => 'Update membership', 'url' => '/merchant/bookings/updatemembership/' . $link),
                array('title' => $detail['title'], 'url' => '')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->datatablejs = 'table-small';
            $this->view->selectedMenu = array(9, 42, 87);
            $this->setAjaxDatatableSession();
            $this->view->header_file = ['booking'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/booking/membership/update.tpl');
            $this->view->render('footer/booking');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB003]Error while updatecategory Error: ' . $e->getMessage());
            header("Location:/error");
        }
    }

    function updatecategorysave()
    {
        try {
            $this->hasRole(2, 18);
            if (empty($_POST)) {
                header("Location:/merchant/bookings/categories");
            }
            $membership = ($_POST['membership'] > 0) ? 1 : 0;
            $this->common->genericupdate('booking_categories', 'category_name', $_POST['category'], 'category_id', $_POST['category_id'], $this->user_id);
            $this->common->genericupdate('booking_categories', 'membership', $membership, 'category_id', $_POST['category_id'], $this->user_id);
            $this->session->set('successMessage', 'Category has been updated.');
            header("Location:/merchant/bookings/categories");
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB003]Error while updatecategory Error: ' . $e->getMessage());
            header("Location:/error");
        }
    }

    function updatepackagesave()
    {
        try {
            $this->hasRole(2, 18);
            if (empty($_POST)) {
                header("Location:/merchant/bookings/packages");
            }
            $membership = ($_POST['membership'] > 0) ? 1 : 0;
            $this->common->genericupdate('booking_packages', 'package_name', $_POST['package_name'], 'package_id', $_POST['package_id'], $this->user_id);
            $this->common->genericupdate('booking_packages', 'package_desc', $_POST['package_desc'], 'package_id', $_POST['package_id'], $this->user_id);
            $this->session->set('successMessage', 'Package has been updated.');
            header("Location:/merchant/bookings/packages");
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB003]Error while updatecategory Error: ' . $e->getMessage());
            header("Location:/error");
        }
    }

    function updatenotification()
    {
        try {
            $this->hasRole(2, 18);
            if (empty($_POST)) {
                header("Location:/merchant/bookings/calendars");
            }
            require_once CONTROLLER . 'merchant/Bookingvalidator.php';
            $validator = new Bookingvalidator($this->model);
            $cancellation_type  = isset($_POST['cancellation_type']) ? $_POST['cancellation_type'] : '1';
            $can_days  = isset($_POST['can_days']) ? $_POST['can_days'] : '1';
            $can_hours  = isset($_POST['can_hours']) ? $_POST['can_hours'] : '1';
            $validator->validateNotificationUpdate();
            $hasErrors = $validator->fetchErrors();
            if ($hasErrors == false) {
                $calendar_id = $this->encrypt->decode($_POST['calendar_id']);
                $this->model->updateNotification($this->merchant_id, $calendar_id, $_POST['notification_email'], 
                $_POST['notification_mobile'], implode($_POST['capture_detail'], ','), $this->user_id,  
                $cancellation_type,$can_days, $can_hours);
                $this->session->set('successMessage', 'Notification has been updated.');
                header("Location:/merchant/bookings/managecalendar/notification/" . $_POST['calendar_id']);
            } else {
                $this->smarty->assign("haserrors", $hasErrors);
                $this->managecalendar('notification', $_POST['calendar_id']);
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB007]Error while updatecalendar Error: ' . $e->getMessage());
            header("Location:/error");
        }
    }

    function updatecalendar()
    {
        try {
            $this->hasRole(2, 18);
            if (empty($_POST)) {
                header("Location:/merchant/bookings/calendars");
            }
            require_once CONTROLLER . 'merchant/Bookingvalidator.php';
            $validator = new Bookingvalidator($this->model);
            $validator->validateCalendarUpdate();
            $hasErrors = $validator->fetchErrors();
            if ($hasErrors == false) {
                $image_name = $this->model->uploadImage($_FILES["uploaded_file"]);
                if ($image_name == '') {
                    $image_name = $_POST['image_name'];
                }
                $this->model->updateCalendar($this->merchant_id, $_POST['calendar_id'], $_POST['max_booking'], $_POST['category'], $_POST['calendar_name'], $_POST['description'], $_POST['booking_unit'], $image_name, $this->user_id);
                $this->session->set('successMessage', 'Calendar has been updated.');
                header("Location:/merchant/bookings/calendars");
            } else {
                $this->smarty->assign("haserrors", $hasErrors);
                $this->calendars();
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB007]Error while updatecalendar Error: ' . $e->getMessage());
            header("Location:/error");
        }
    }

    function publishstatus($link, $val)
    {
        try {
            $status = substr($val, 0, 1);
            $type = substr($val, 1);
            switch ($type) {
                case 1:
                    $table = 'booking_categories';
                    $col = 'category_id';
                    $column_name = 'category_active';
                    $name = 'Category';
                    $redirect = 'categories';
                    break;
                case 2:
                    $table = 'booking_calendars';
                    $col = 'calendar_id';
                    $name = 'Calendar';
                    $column_name = 'calendar_active';
                    $redirect = 'calendars';
                    break;
                case 3:
                    $table = 'booking_packages';
                    $col = 'package_id';
                    $name = 'Package';
                    $column_name = 'is_active';
                    $redirect = 'packages';
                    break;
            }
            $id = $this->encrypt->decode($link);
            $this->common->genericupdate($table, $column_name, $status, $col, $id, $this->user_id);
            $this->session->set('successMessage', $name . ' status has been updated.');
            header("Location:/merchant/bookings/" . $redirect);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB004]Error while creating supplier Error: ' . $e->getMessage());
            header("Location:/error");
        }
    }

    function delete($type, $link)
    {
        try {
            $this->hasRole(3, 18);
            $id = $this->encrypt->decode($link);
            switch ($type) {
                case 1:
                    $table = 'booking_categories';
                    $col = 'category_id';
                    $name = 'Category';
                    $redirect = 'categories';
                    break;
                case 2:
                    $table = 'booking_calendars';
                    $col = 'calendar_id';
                    $name = 'Calendar';
                    $redirect = 'calendars';
                    break;
                case 3:
                    $table = 'booking_holidays';
                    $col = 'holiday_id';
                    $name = 'Holiday';
                    $holidayCheck = $this->common->querysingle("SELECT calendar_id FROM booking_holidays WHERE holiday_id =" . $id . "");
                    $calendar_id = $holidayCheck['calendar_id'];
                    $link = $this->encrypt->encode($calendar_id);
                    $redirect = 'managecalendar/holiday/' . $link;
                    break;
                case 4:
                    $table = 'booking_slots';
                    $col = 'slot_id';
                    $name = 'Slot';
                    $holidayCheck = $this->common->querysingle("SELECT calendar_id FROM booking_slots WHERE slot_id =" . $id . "");
                    $calendar_id = $holidayCheck['calendar_id'];
                    $link = $this->encrypt->encode($calendar_id);
                    $redirect = 'managecalendar/slot/' . $link;
                    break;
                case 5:
                    $table = 'booking_membership';
                    $col = 'membership_id';
                    $name = 'Membership';
                    $redirect = 'membership';
                    break;
                case 6:
                    $table = 'booking_packages';
                    $col = 'package_id';
                    $name = 'Package';
                    $redirect = 'packages';
                    break;
            }

            $this->common->genericupdate($table, 'is_active', 0, $col, $id, $this->user_id);
            $this->session->set('successMessage', $name . ' has been deleted.');
            header("Location:/merchant/bookings/" . $redirect);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB004]Error while creating supplier Error: ' . $e->getMessage());
            header("Location:/error");
        }
    }

    function calendars()
    {
        try {
            $this->hasRole(1, 18);
            $category_list = $this->model->getBookingCategories($this->merchant_id);
            $list = $this->model->getBookingCalendars($this->merchant_id);
            $list = $this->generic->getEncryptedList($list, 'encrypted_id', 'calendar_id');
            $list = $this->generic->getDateFormatList($list, 'created_date', 'created_date');
            $mer_det = $this->common->getMerchantProfile($this->merchant_id);

            $merchant = $this->common->getSingleValue('merchant', 'merchant_id', $this->merchant_id);
            $booking_link = $this->app_url . '/m/' . $merchant['display_url'] . '/booking';

            $this->smarty->assign("company_name", $this->session->get('company_name'));
            $this->smarty->assign("booking_link", $booking_link);
            $this->smarty->assign("category_list", $category_list);
            $this->smarty->assign("list", $list);
            $this->smarty->assign("title", "Booking calendar list");
            $this->view->js = array('booking');
            $this->smarty->assign("billing_email", $mer_det['business_email']);
            $this->view->title = "Booking calendar list";

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Booking calendar', 'url' => ''),
                array('title' => $this->view->title, 'url' => '')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->datatablejs = 'table-no-export';
            $this->view->selectedMenu = array(9, 38);
            $this->view->header_file = ['list', 'template'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/booking/calendar/list.tpl');
            $this->view->render('footer/list');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB005]Error while listing gategories Error: for merchant id [' . $this->merchant_id . '] ' . $e->getMessage());
        }
    }

    function addcalendar()
    {
        try {
            $this->validatePackage();
            $this->hasRole(2, 18);
            $category_list = $this->model->getBookingCategories($this->merchant_id);
            $category_list = $this->generic->getEncryptedList($category_list, 'encrypted_id', 'category_id');
            $list = $this->model->getBookingCalendars($this->merchant_id);
            $list = $this->generic->getEncryptedList($list, 'encrypted_id', 'calendar_id');
            $mer_det = $this->common->getMerchantProfile($this->merchant_id);
            $this->smarty->assign("category_list", $category_list);
            $this->smarty->assign("list", $list);
            $this->smarty->assign("title", "Create a new booking calendar");
            $this->view->title = "Create a new booking calendar";

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Booking calendar', 'url' => ''),
                array('title' => 'Calendars', 'url' => '/merchant/bookings/calendars'),
                array('title' => $this->view->title, 'url' => '')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->smarty->assign("billing_email", $mer_det['business_email']);
            $this->view->selectedMenu = array(9, 38);
            $this->setAjaxDatatableSession();
            $this->view->datatablejs = 'table-small';
            $this->view->header_file = ['booking'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/booking/calendar/addcalendar.tpl');
            $this->view->render('footer/booking');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB005]Error while listing gategories Error: for merchant id [' . $this->merchant_id . '] ' . $e->getMessage());
        }
    }

    function updatesetting()
    {
        if (!empty($_POST)) {
            $merchant_id = $this->session->get('merchant_id');
            require_once CONTROLLER . 'merchant/Registervalidator.php';
            $validator = new Registervalidator($this->model);
            $validator->validateImageBanner();
            $hasErrors = $validator->fetchErrors();
            if ($hasErrors == false) {
                $hide_menu = ($_POST['hide_menu'] > 0) ? 0 : 1;
                $this->model->savePageSetting($this->merchant_id, $_FILES['banner'], $_POST['booking_title'], $hide_menu);
                $this->session->set('successMessage', 'Settings have been updated.');
                header("Location:/merchant/bookings/setting");
            } else {
                $this->smarty->assign("haserrors", $hasErrors);
                $this->setting();
            }
        }
    }

    function setting()
    {
        try {
            $this->hasRole(2, 18);
            $mer_det = $this->common->getSingleValue('merchant_landing', 'merchant_id', $this->merchant_id);
            $this->smarty->assign("det", $mer_det);
            $this->smarty->assign("title", "Langing page settings");
            $this->view->title = "Langing page settings";

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Booking calendar', 'url' => ''),
                array('title' => 'Configuration', 'url' => ''),
                array('title' => $this->view->title, 'url' => ''),
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->selectedMenu = array(9, 42, 140);
            $this->setAjaxDatatableSession();
            $this->view->header_file = ['booking'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/booking/setting.tpl');
            $this->view->render('footer/booking');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB005]Error while listing gategories Error: for merchant id [' . $this->merchant_id . '] ' . $e->getMessage());
        }
    }

    function reservations($calendar_id = 0, $export = null)
    {
        try {
            $this->hasRole(1, 18);
            $last_date = date('d M Y');
            $current_date = $this->date_add(30);

            if (isset($_POST['from_date'])) {
                $from_date = $_POST['from_date'];
                $to_date = $_POST['to_date'];
            } else {
                $from_date = $last_date;
                $to_date = $current_date;
            }
            if (isset($_POST['export'])) {
                $export = 1;
            }

            if ($calendar_id > 0) {
                $this->view->selectedMenu = array(9, 38);
            } else {
                $this->view->selectedMenu = array(9, 40, 84);
            }

            $fromdate = new DateTime($from_date);
            $todate = new DateTime($to_date);
            $list = $this->model->getCalendarReservation($this->merchant_id, $calendar_id, $fromdate->format('Y-m-d'), $todate->format('Y-m-d'));
            if ($export != NULL) {
                $this->common->excelexport('Reservations', $list);
            }

            $list = $this->generic->getEncryptedList($list, 'encrypted_id', 'transaction_id');
            $list = $this->generic->getMoneyFormatList($list, 'amount', 'amount');
            $list = $this->generic->getDateFormatList($list, 'calendar_date', 'calendar_date');


            $this->smarty->assign("from_date", $from_date);
            $this->smarty->assign("to_date", $to_date);
            $this->smarty->assign("calendar_id", $calendar_id);
            $this->smarty->assign("list", $list);
            $this->view->title = "Reservations";
            $this->smarty->assign("title", "Reservations");
            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Booking calendar', 'url' => ''),
                array('title' => 'Booking calendar list', 'url' => '/merchant/bookings/calendars'),
                array('title' => $this->view->title, 'url' => '')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->datatablejs = 'table-no-export';
            $this->setAjaxDatatableSession();
            $this->view->header_file = ['booking'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/booking/calendar/reservation.tpl');
            $this->view->render('footer/booking');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB005]Error while listing gategories Error: for merchant id [' . $this->merchant_id . '] ' . $e->getMessage());
        }
    }

    function membershipdetails()
    {
        try {
            $this->hasRole(1, 18);
            $last_date = $this->getLast_date();
            $current_date = date('d M Y');

            if (isset($_POST['from_date'])) {
                $from_date = $_POST['from_date'];
                $to_date = $_POST['to_date'];
            } else {
                $from_date = $last_date;
                $to_date = $current_date;
            }
            if (isset($_POST['export'])) {
                $export = 1;
            }


            $this->view->selectedMenu = array(9, 40, 85);

            $fromdate = new DateTime($from_date);
            $todate = new DateTime($to_date);
            $list = $this->model->getMembershipReservation($this->merchant_id, $fromdate->format('Y-m-d'), $todate->format('Y-m-d'));
            if ($export != NULL) {
                $this->common->excelexport('Membership', $list);
            }

            $list = $this->generic->getEncryptedList($list, 'encrypted_id', 'transaction_id');

            $this->smarty->assign("from_date", $from_date);
            $this->smarty->assign("current_date", date('Y-m-d'));
            $this->smarty->assign("to_date", $to_date);
            $this->smarty->assign("list", $list);
            $this->smarty->assign("title", "Memembership bookings list");
            $this->view->title = "Memembership bookings list";

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Booking calendar', 'url' => ''),
                array('title' => 'Bookings', 'url' => ''),
                array('title' => $this->view->title, 'url' => '')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->datatablejs = 'table-no-export';
            $this->setAjaxDatatableSession();
            $this->view->header_file = ['booking'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/booking/membership/membership.tpl');
            $this->view->render('footer/booking');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB005]Error while listing gategories Error: for merchant id [' . $this->merchant_id . '] ' . $e->getMessage());
        }
    }

    function getslotstable($calendar_id)
    {
        $calendar_id = $this->encrypt->decode($calendar_id);
        $to_date = $this->date_add(30);
        $from_date = date('d M Y');
        $fromdate = new DateTime($from_date);
        $todate = new DateTime($to_date);
        $list = $this->model->getBookingSlotsv2($calendar_id, $fromdate->format('Y-m-d'), $todate->format('Y-m-d'));
        $list = $this->generic->getEncryptedList($list, 'encrypted_id', 'slot_id');
        $this->smarty->assign("list", $list);
    }

    function managecalendar($type, $link)
    {
        try {
            $this->hasRole(2, 18);
            $id = $this->encrypt->decode($link);
            if (is_numeric($id)) {
                $calendar_id = $this->encrypt->decode($link);

                $this->smarty->assign("link", $link);
                $this->smarty->assign("type", $type);
                if ($type == 'holiday') {
                    $this->view->title = "Manage holidays";
                    $list = $this->model->getHolidays($calendar_id);
                    $list = $this->generic->getEncryptedList($list, 'encrypted_id', 'holiday_id');
                    $this->smarty->assign("list", $list);

                    $breadcumbs_array = array(
                        array('title' => 'Booking calendar', 'url' => ''),
                        array('title' => 'Booking calendar list', 'url' => '/merchant/bookings/calendars'),
                        array('title' => 'Manage holidays', 'url' => '')
                    );
                } elseif ($type == 'notification') {
                    $this->view->title = "Manage notifications & data";
                    $detail = $this->common->getSingleValue('booking_calendars', 'calendar_id', $calendar_id);
                    $detail['capture'] = explode(',', $detail['capture_details']);
                    $this->smarty->assign("detail", $detail);

                    $breadcumbs_array = array(
                        array('title' => 'Booking calendar', 'url' => ''),
                        array('title' => 'Booking calendar list', 'url' => '/merchant/bookings/calendars'),
                        array('title' => 'Manage notifications & data', 'url' => '')
                    );
                } else {
                    $next_date = $this->date_add(30);
                    $current_date = date('d M Y');

                    if (isset($_POST['from_date'])) {
                        $from_date = $_POST['from_date'];
                        $to_date = $_POST['to_date'];
                    } else {
                        $from_date = $current_date;
                        $to_date = $next_date;
                    }
                    $this->smarty->assign("from_date", $from_date);
                    $this->smarty->assign("to_date", $to_date);

                    $fromdate = new DateTime($from_date);
                    $todate = new DateTime($to_date);
                    if (isset($_POST['from_date'])) {
                        $_SESSION['from_datee'] = $fromdate->format('Y-m-d');
                        $_SESSION['to_datee'] = $todate->format('Y-m-d');
                    } else {
                        $_SESSION['from_datee'] = null;
                        $_SESSION['to_datee'] = null;
                    }
                    $_SESSION['calendar_idd'] = $calendar_id;

                    $this->view->title = "Manage time slots";
                    $breadcumbs_array = array(
                        array('title' => 'Booking calendar', 'url' => ''),
                        array('title' => 'Booking calendar list', 'url' => '/merchant/bookings/calendars'),
                        array('title' => 'Manage time slots', 'url' => '')
                    );
                }

                $this->smarty->assign("title", $this->view->title);
                //Breadcumbs array start
                $this->smarty->assign("links", $breadcumbs_array);
                //Breadcumbs array end

                $this->view->datatablejs = 'table-no-export';
                $this->view->selectedMenu = array(9, 38);
                $this->setAjaxDatatableSession();
                if ($type == 'slot') {
                    $this->view->header_file = ['list'];
                    $this->view->render('header/app');
                    $this->smarty->display(VIEW . 'merchant/booking/slot/manage_' . $type . '.tpl');
                    $this->view->render('footer/slot_list');
                } else {
                    $this->view->header_file = ['booking'];
                    $this->view->render('header/app');
                    $this->smarty->display(VIEW . 'merchant/booking/slot/manage_' . $type . '.tpl');
                    $this->view->render('footer/booking');
                }
            } else {
                $this->setGenericError();
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB005]Error while listing gategories Error: for merchant id [' . $this->merchant_id . '] ' . $e->getMessage());
        }
    }

    function createslot($type, $link)
    {
        try {
            $this->hasRole(2, 18);
            $id = $this->encrypt->decode($link);
            if (is_numeric($id)) {
                $packages = $this->model->getPackagesbyCalendarID($id);

                $this->smarty->assign("title", "Create time slot");
                $this->view->title = "Create time slot";
                $this->smarty->assign("link", $link);
                $this->smarty->assign("calendar_id", $link);
                $this->smarty->assign("type", $type);
                $this->smarty->assign("packages", $packages);
                $this->view->datatablejs = 'table-no-export';
                $this->view->selectedMenu = array(9, 38);
                $breadcumbs_array = array(
                    array('title' => 'Booking calendar', 'url' => ''),
                    array('title' => 'Booking calendar list', 'url' => '/merchant/bookings/calendars'),
                    array('title' => 'Manage time slots', 'url' => '/merchant/bookings/managecalendar/slot/' . $link),
                    array('title' => $this->view->title, 'url' => '')
                );
                $this->smarty->assign("links", $breadcumbs_array);
                $this->view->header_file = ['booking'];
                $this->view->render('header/app');
                $this->smarty->display(VIEW . 'merchant/booking/slot/create_' . $type . '.tpl');
                $this->view->render('footer/booking');
            } else {
                $this->setGenericError();
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB005]Error while listing gategories Error: for merchant id [' . $this->merchant_id . '] ' . $e->getMessage());
        }
    }

    function createslotpackages($type, $link)
    {
        try {
            $this->hasRole(2, 18);
            $id = $this->encrypt->decode($link);
            if (is_numeric($id)) {

                $this->smarty->assign("title", "Create time slot");
                $this->view->title = "Create time slot";
                $this->smarty->assign("link", $link);
                $this->smarty->assign("calendar_id", $link);
                $this->smarty->assign("type", $type);
                $this->view->datatablejs = 'table-no-export';
                $this->view->selectedMenu = array(9, 38);
                $breadcumbs_array = array(
                    array('title' => 'Booking calendar', 'url' => ''),
                    array('title' => 'Booking calendar list', 'url' => '/merchant/bookings/calendars'),
                    array('title' => 'Manage time slots', 'url' => '/merchant/bookings/managecalendar/slot/' . $link),
                    array('title' => $this->view->title, 'url' => '')
                );
                $this->smarty->assign("links", $breadcumbs_array);
                $this->view->header_file = ['booking'];
                $this->view->render('header/app');
                $this->smarty->display(VIEW . 'merchant/booking/slot/create_' . $type . '_package.tpl');
                $this->view->render('footer/booking');
            } else {
                $this->setGenericError();
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB005]Error while listing gategories Error: for merchant id [' . $this->merchant_id . '] ' . $e->getMessage());
        }
    }

    function savecalendar()
    {
        try {
            if (empty($_POST)) {
                header("Location:/merchant/bookings/calendars");
            }

            require_once CONTROLLER . 'merchant/Bookingvalidator.php';

            $validator = new Bookingvalidator($this->model);
            $validator->validateCalendarSave($_POST['category']);
            $hasErrors = $validator->fetchErrors();
            if ($hasErrors == false) {
                $image_name = $this->model->uploadImage($_FILES["uploaded_file"]);
                if ($image_name == '') {
                    $image_name = '/assets/admin/layout/img/court-1.jpg';
                }
                $calendar_id = $this->model->createCalendar($_POST['category'], $_POST['calendar_name'], 
                $_POST['max_booking'], $_POST['description'], $_POST['notification_email'], 
                $_POST['notification_mobile'], $_POST['booking_unit'],
                 implode($_POST['capture_detail'], ','), $image_name, 
                 $this->merchant_id, $this->user_id, $_POST['confirmation_message'], 
                 $_POST['tandc'], $_POST['cancellation_policy'], $_POST['cancellation_type'], 
                 $_POST['can_days'], $_POST['can_hours']);
                 
                $total_slot_count = 0;
                foreach ($_POST["package_number"] as $package_number) {
                    $package_image = $this->model->uploadImage($_FILES["package_image" . $package_number]);
                    $package_id = $this->model->createPackage($_POST['package_name' . $package_number], $_POST['package_desc' . $package_number],  $this->user_id,  $package_image);

                    $slots = $this->getslotsv2(
                        $_POST['slot_date_from' . $package_number],
                        $_POST['slot_date_to' . $package_number],
                        $_POST['slot_weekday' . $package_number],
                        $_POST['slot_interval' . $package_number],
                        $_POST['slot_interval_custom_from_hour' . $package_number],
                        $_POST['slot_interval_custom_to_hour' . $package_number],
                        $_POST['slot_interval_custom_from_minute' . $package_number],
                        $_POST['slot_interval_custom_to_minute' . $package_number],
                        $_POST['slot_interval_minutes' . $package_number],
                        $_POST['slot_time_from_hour' . $package_number],
                        $_POST['slot_time_from_minute' . $package_number],
                        $_POST['slot_time_to_hour' . $package_number],
                        $_POST['slot_time_to_minute' . $package_number],
                        $_POST['slot_pause' . $package_number],
                        $_POST['slot_interval_custom_from_ampm' . $package_number],
                        $_POST['slot_interval_custom_to_ampm' . $package_number],
                        $_POST['slot_time_from_ampm' . $package_number],
                        $_POST['slot_time_to_ampm' . $package_number]
                    );

                    $holidays = $_POST['holiday'];
                    if (strlen($holidays) > 7) {
                        $holidays = str_replace(' ', '', $holidays);
                        $holidays = explode(',', $holidays);
                    } else {
                        $holidays = array();
                    }
                    foreach ($holidays as $holid) {
                        $holiday = new DateTime($holid);
                        $holiday = $holiday->format('Y-m-d');
                        $res = $this->model->createHoliday($this->merchant_id, $calendar_id, $holiday, $this->user_id);
                    }

                    $resultDate = $slots['dates'];
                    $resultTime = $slots['slots'];
                    $datesarray = $resultDate;
                    foreach ($resultDate as $key => $date) {
                        $sdate = strtotime($date);
                        $check_date = date('m/d/Y', $sdate);
                        if (in_array($check_date, $holidays)) {
                            unset($datesarray[$key]);
                        }
                    }

                    $resultTime = array_filter($resultTime);
                    foreach ($_POST["slot_title" . $package_number] as $skey => $slot_title) {
                        $slot_description = $_POST["slot_description" . $package_number][$skey];
                        $slot_price = $_POST["slot_price" . $package_number][$skey];
                        $slot_isprimary = $_POST["slot_isprimary" . $package_number][$skey];
                        $count = $this->addslotsv2(
                            $datesarray,
                            $resultTime,
                            $calendar_id,
                            $package_id,
                            $slot_title,
                            $slot_description,
                            $slot_price,
                            $_POST['is_multiple' . $package_number],
                            $_POST['unitavailable' . $package_number],
                            $_POST['min_seat' . $package_number],
                            $_POST['max_seat' . $package_number],
                            $slot_isprimary
                        );
                        $total_slot_count = $total_slot_count + $count;
                    }
                }
                $this->session->set('successMessage', 'Calendar has been saved. ' . $total_slot_count . ' Slots have been added.');
                header("Location:/merchant/bookings/calendars");
            } else {
                $this->smarty->assign("haserrors", $hasErrors);
                $this->smarty->assign("post", $_POST);
                $this->calendars();
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB006]Error while creating supplier Error: ' . $e->getMessage());
            header("Location:/error");
        }
    }

    function savecalendarv2()
    {
        try {
            if (empty($_POST)) {
                header("Location:/merchant/bookings/calendars");
            }
            $calendar_id = $this->encrypt->decode($_POST["calendar_id"]);
            $hasErrors = false;
            if ($hasErrors == false) {
                $image_name = $this->model->uploadImage($_FILES["uploaded_file"]);
                if ($image_name == '') {
                    $image_name = '/assets/admin/layout/img/court-1.jpg';
                }

                $total_slot_count = 0;
                foreach ($_POST["package_number"] as $package_number) {
                    $package_image = $this->model->uploadImage($_FILES["package_image" . $package_number]);
                    $package_id = $this->model->createPackage($_POST['package_name' . $package_number], $_POST['package_desc' . $package_number],  $this->user_id,  $package_image);

                    $slots = $this->getslotsv2(
                        $_POST['slot_date_from' . $package_number],
                        $_POST['slot_date_to' . $package_number],
                        $_POST['slot_weekday' . $package_number],
                        $_POST['slot_interval' . $package_number],
                        $_POST['slot_interval_custom_from_hour' . $package_number],
                        $_POST['slot_interval_custom_to_hour' . $package_number],
                        $_POST['slot_interval_custom_from_minute' . $package_number],
                        $_POST['slot_interval_custom_to_minute' . $package_number],
                        $_POST['slot_interval_minutes' . $package_number],
                        $_POST['slot_time_from_hour' . $package_number],
                        $_POST['slot_time_from_minute' . $package_number],
                        $_POST['slot_time_to_hour' . $package_number],
                        $_POST['slot_time_to_minute' . $package_number],
                        $_POST['slot_pause' . $package_number],
                        $_POST['slot_interval_custom_from_ampm' . $package_number],
                        $_POST['slot_interval_custom_to_ampm' . $package_number],
                        $_POST['slot_time_from_ampm' . $package_number],
                        $_POST['slot_time_to_ampm' . $package_number]
                    );

                    $resultDate = $slots['dates'];
                    $resultTime = $slots['slots'];
                    $datesarray = $resultDate;

                    $resultTime = array_filter($resultTime);
                    foreach ($_POST["slot_title" . $package_number] as $skey => $slot_title) {
                        $slot_description = $_POST["slot_description" . $package_number][$skey];
                        $slot_price = $_POST["slot_price" . $package_number][$skey];
                        $slot_isprimary = $_POST["slot_isprimary" . $package_number][$skey];
                        $count = $this->addslotsv2(
                            $datesarray,
                            $resultTime,
                            $calendar_id,
                            $package_id,
                            $slot_title,
                            $slot_description,
                            $slot_price,
                            $_POST['is_multiple' . $package_number],
                            $_POST['unitavailable' . $package_number],
                            $_POST['min_seat' . $package_number],
                            $_POST['max_seat' . $package_number],
                            $slot_isprimary
                        );
                        $total_slot_count = $total_slot_count + $count;
                    }
                }
                $this->session->set('successMessage', 'Calendar has been saved. ' . $total_slot_count . ' Slots have been added.');
                header("Location:/merchant/bookings/calendars");
            } else {
                $this->smarty->assign("haserrors", $hasErrors);
                $this->smarty->assign("post", $_POST);
                $this->calendars();
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB006]Error while creating supplier Error: ' . $e->getMessage());
            header("Location:/error");
        }
    }

    function saveslots()
    {
        try {
            if (empty($_POST)) {
                header("Location:/merchant/bookings/calendars");
            }
            $calendar_id = $this->encrypt->decode($_POST['calendar_id']);
            $package_id = $_POST['package_id'];
            //$slots = $this->getslots();
            $total_slot_count = 0;
            foreach ($_POST["package_number"] as $package_number) {
                $slots = $this->getslotsv2(
                    $_POST['slot_date_from' . $package_number],
                    $_POST['slot_date_to' . $package_number],
                    $_POST['slot_weekday' . $package_number],
                    $_POST['slot_interval' . $package_number],
                    $_POST['slot_interval_custom_from_hour' . $package_number],
                    $_POST['slot_interval_custom_to_hour' . $package_number],
                    $_POST['slot_interval_custom_from_minute' . $package_number],
                    $_POST['slot_interval_custom_to_minute' . $package_number],
                    $_POST['slot_interval_minutes' . $package_number],
                    $_POST['slot_time_from_hour' . $package_number],
                    $_POST['slot_time_from_minute' . $package_number],
                    $_POST['slot_time_to_hour' . $package_number],
                    $_POST['slot_time_to_minute' . $package_number],
                    $_POST['slot_pause' . $package_number],
                    $_POST['slot_interval_custom_from_ampm' . $package_number],
                    $_POST['slot_interval_custom_to_ampm' . $package_number],
                    $_POST['slot_time_from_ampm' . $package_number],
                    $_POST['slot_time_to_ampm' . $package_number]
                );


                $holidays = array();
                $rows = $this->model->getHolidays($calendar_id);
                foreach ($rows as $row) {
                    $holidays[] = $row['holiday_date'];
                }

                $resultDate = $slots['dates'];
                $resultTime = $slots['slots'];
                $datesarray = $resultDate;
                foreach ($resultDate as $key => $date) {
                    $sdate = strtotime($date);
                    $check_date = date('Y-m-d', $sdate);
                    if (in_array($check_date, $holidays)) {
                        unset($datesarray[$key]);
                    }
                }
                $resultTime = array_filter($resultTime);
                //$count = $this->addslots($datesarray, $resultTime, $calendar_id);
                foreach ($_POST["slot_title" . $package_number] as $skey => $slot_title) {
                    $slot_description = $_POST["slot_description" . $package_number][$skey];
                    $slot_price = $_POST["slot_price" . $package_number][$skey];
                    $slot_isprimary = $_POST["slot_isprimary" . $package_number][$skey];
                    $count = $this->addslotsv2(
                        $datesarray,
                        $resultTime,
                        $calendar_id,
                        $package_id,
                        $slot_title,
                        $slot_description,
                        $slot_price,
                        $_POST['is_multiple' . $package_number],
                        $_POST['unitavailable' . $package_number],
                        $_POST['min_seat' . $package_number],
                        $_POST['max_seat' . $package_number],
                        $slot_isprimary
                    );
                    $total_slot_count = $total_slot_count + $count;
                }
            }


            $this->session->set('successMessage', $total_slot_count . ' Slots have been added.');
            header("Location:/merchant/bookings/managecalendar/slot/" . $_POST['calendar_id']);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB006]Error while creating supplier Error: ' . $e->getMessage());
            header("Location:/error");
        }
    }

    function addholidays()
    {
        try {
            $holiday_array = explode(',', str_replace(' ', '', $_POST["holiday"]));
            $calendar_id = $this->encrypt->decode($_POST['calendar_id']);
            $int = 0;
            foreach ($holiday_array as $holidays) {
                $holiday = new DateTime($holidays);
                $holiday = $holiday->format('Y-m-d');
                $res = $this->model->createHoliday($this->merchant_id, $calendar_id, $holiday, $this->user_id);
                if ($res == true) {
                    $this->model->deleteSlots($calendar_id, $holiday, $this->user_id);
                    $int++;
                }
            }
            if ($int > 0) {
                $this->session->set('successMessage', $int . ' Holiday has been added');
            } else {
                $this->session->set('successMessage', ' Holiday already exist');
            }
            header("Location:/merchant/bookings/managecalendar/holiday/" . $_POST['calendar_id']);
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB00698]Error while holiday Error: ' . $e->getMessage());
            header("Location:/error");
        }
    }

    public function updateslot()
    {
        try {
            $this->hasRole(2, 18);
            $calendar_id = $this->encrypt->decode($_POST['calendar_id']);
            $slot_id = $_POST['slot_id'];
            $from = $_POST['from_hr'] . ':' . $_POST['from_min'] . ' ' . $_POST['from_ampm'];
            $from_time = new DateTime($from);
            $from_time = $from_time->format('H:i:s');
            $to = $_POST['to_hr'] . ':' . $_POST['to_min'] . ' ' . $_POST['to_ampm'];
            $to_time = new DateTime($to);
            $to_time = $to_time->format('H:i:s');

            $slot_date = new DateTime($_POST["slot_date"]);
            $slot_date = $slot_date->format('Y-m-d');
            $checkQry = $this->model->isExistSlotUpdate($calendar_id, $slot_date, $from_time, $to_time, $slot_id, $_POST['package_id'], $_POST['slot_title']);
            if ($checkQry == false) {
                $is_multiple = (isset($_POST['is_multiple'])) ? $_POST['is_multiple'] : 0;
                $is_multiple = ($is_multiple > 0) ? $is_multiple : 0;
                $unitavailable = (isset($_POST['unitavailable'])) ? $_POST['unitavailable'] : 0;
                $unitavailable = ($unitavailable > 0) ? $unitavailable : 0;
                $min_seat = (isset($_POST['min_seat'])) ? $_POST['min_seat'] : 0;
                $max_seat = (isset($_POST['max_seat'])) ? $_POST['max_seat'] : 0;
                $this->model->updateSlots($slot_id, $slot_date, $from_time, $to_time, $_POST['slot_text'], $_POST['slot_price'], $is_multiple, $unitavailable, $min_seat, $max_seat, $this->user_id, $_POST['slot_title'], $_POST['slot_description']);
                $this->session->set('successMessage', ' Slot has been updated.');
                echo 'success';
            } else {
                echo 'Slot alredy exist';
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB00699]Error while update slot Error: ' . $e->getMessage());
            header("Location:/error");
        }
    }

    function validatecalendar()
    {
        require_once CONTROLLER . 'merchant/Bookingvalidator.php';
        $validator = new Bookingvalidator($this->model);
        $validator->validateCalendarSave($_POST['category']);
        $hasErrors = $validator->fetchErrors();
        $res['page'] = '';

        if ($hasErrors == false) {
            $count_total_days = 0;
            $count_slot_per_day = 0;
            $count_total_slot = 0;
            //$slots = $this->getslots();
            foreach ($_POST["package_number"] as $package_number) {
                $slots = $this->getslotsv2(
                    $_POST['slot_date_from' . $package_number],
                    $_POST['slot_date_to' . $package_number],
                    $_POST['slot_weekday' . $package_number],
                    $_POST['slot_interval' . $package_number],
                    $_POST['slot_interval_custom_from_hour' . $package_number],
                    $_POST['slot_interval_custom_to_hour' . $package_number],
                    $_POST['slot_interval_custom_from_minute' . $package_number],
                    $_POST['slot_interval_custom_to_minute' . $package_number],
                    $_POST['slot_interval_minutes' . $package_number],
                    $_POST['slot_time_from_hour' . $package_number],
                    $_POST['slot_time_from_minute' . $package_number],
                    $_POST['slot_time_to_hour' . $package_number],
                    $_POST['slot_time_to_minute' . $package_number],
                    $_POST['slot_pause' . $package_number],
                    $_POST['slot_interval_custom_from_ampm' . $package_number],
                    $_POST['slot_interval_custom_to_ampm' . $package_number],
                    $_POST['slot_time_from_ampm' . $package_number],
                    $_POST['slot_time_to_ampm' . $package_number]
                );


                $resultDate = $slots['dates'];
                $resultTime = $slots['slots'];
                $datesarray = $resultDate;
                foreach ($resultDate as $key => $date) {
                    $sdate = strtotime($date);
                    $check_date = date('m/d/Y', $sdate);
                    if (in_array($check_date, $holidays)) {
                        unset($datesarray[$key]);
                    }
                }

                $resultTime = array_filter($resultTime);

                $count_total_days = $count_total_days + count($resultDate);;
                $count_slot_per_day = $count_slot_per_day + count($resultTime);
                $count_total_slot =  $count_total_slot + (count($datesarray) * count($resultTime));
            }

            $holidays = $_POST['holiday'];
            if (strlen($holidays) > 7) {
                $holidays = str_replace(' ', '', $holidays);
                $holidays = explode(',', $holidays);
            } else {
                $holidays = array();
            }

            $res['status'] = 1;
            $res['total_holiday'] = count($holidays);
            $res['total_days'] =  $count_total_days;
            $res['slots_per_day'] = $count_slot_per_day;
            $res['total_slots'] = $count_total_slot;
            if ($res['total_slots'] > 1500) {
                $res['status'] = 0;
                $res['first_page_error'] = 0;
                $res['page'] = '2';
                $res['error'] = $error = 'Maximum 1500 slots allowed at a time';
            }
        } else {
            $res['status'] = 0;
            $first_page_error = 0;
            foreach ($hasErrors as $errors) {
                $error .= $errors[0] . ': ' . $errors[1] . '<br>';
                if (substr($errors[0], 0, 12) != 'Notification') {
                    $first_page_error = 1;
                }
            }
            $res['first_page_error'] = $first_page_error;
            $res['error'] = $error;
        }
        echo json_encode($res);
    }

    public function addslots($resultDate, $resultTime, $calendar_id, $package_id = null)
    {
        $count = 0;
        $slot_special_text = "";
        $slot_special_mode = 1;
        $holidays = 0;
        $is_multiple = (isset($_POST['is_multiple'])) ? $_POST['is_multiple'] : 0;
        $is_multiple = ($is_multiple > 0) ? $is_multiple : 0;
        $unitavailable = (isset($_POST['unitavailable'])) ? $_POST['unitavailable'] : 0;
        $unitavailable = ($unitavailable > 0) ? $unitavailable : 0;
        $min_seat = (isset($_POST['min_seat'])) ? $_POST['min_seat'] : 0;
        $max_seat = (isset($_POST['max_seat'])) ? $_POST['max_seat'] : 0;
        foreach ($resultDate as $resdate) {
            for ($j = 0; $j < count($resultTime); $j++) {
                //before insert have to check if there's a slot with same values
                //echo $resultTime[$j]["time_from"] . ' TO ' . $resultTime[$j]["time_to"] . '<br>';
                $checkQry = $this->model->isExistSlot($calendar_id, $resdate, $resultTime[$j]["time_from"], $resultTime[$j]["time_to"]);
                if ($checkQry == false) {
                    $slot_special_text = '';
                    $slot_special_mode = 0;
                    $price = 0;

                    if (isset($_POST["slot_price"]) && str_replace(",", ".", $_POST["slot_price"]) > 0) {
                        $price = str_replace(",", ".", $_POST["slot_price"]);
                    }
                    $this->model->createSlot($this->merchant_id, $slot_special_text, $slot_special_mode, $resdate, $resultTime[$j]["time_from"], $resultTime[$j]["time_to"], $price, $is_multiple, $unitavailable, $min_seat, $max_seat, $calendar_id, $this->user_id,  $package_id, $_POST["slot_title"], $_POST["slot_description"]);

                    $count++;
                }
            }
            if ($count > 2000) {
                break;
            }
        }
        if ($holidays > 0 && $count == 0) {
            $count = -1;
        }
        return $count;
    }

    public function addslotsv2(
        $resultDate,
        $resultTime,
        $calendar_id,
        $package_id = null,
        $slot_title,
        $slot_description,
        $slot_price,
        $is_multiple,
        $unitavailable,
        $min_seat,
        $max_seat,
        $slot_isprimary = '0'
    ) {
        $count = 0;
        $slot_special_text = "";
        $slot_special_mode = 1;
        $holidays = 0;
        $is_multiple = (isset($is_multiple)) ? $is_multiple : 0;
        $is_multiple = ($is_multiple > 0) ? $is_multiple : 0;
        $unitavailable = (isset($unitavailable)) ? $unitavailable : 0;
        $unitavailable = ($unitavailable > 0) ? $unitavailable : 0;
        $min_seat = (isset($min_seat)) ? $min_seat : 0;
        $max_seat = (isset($max_seat)) ? $max_seat : 0;
        foreach ($resultDate as $resdate) {
            for ($j = 0; $j < count($resultTime); $j++) {
                //before insert have to check if there's a slot with same values
                //echo $resultTime[$j]["time_from"] . ' TO ' . $resultTime[$j]["time_to"] . '<br>';
                $checkQry = $this->model->isExistSlotv2($calendar_id, $resdate, $resultTime[$j]["time_from"], $resultTime[$j]["time_to"], $package_id, $slot_title);
                if ($checkQry == false) {
                    $slot_special_text = '';
                    $slot_special_mode = 0;
                    $price = 0;

                    if (isset($slot_price) && str_replace(",", ".", $slot_price) > 0) {
                        $price = str_replace(",", ".", $slot_price);
                    }
                    $this->model->createSlot(
                        $this->merchant_id,
                        $slot_special_text,
                        $slot_special_mode,
                        $resdate,
                        $resultTime[$j]["time_from"],
                        $resultTime[$j]["time_to"],
                        $price,
                        $is_multiple,
                        $unitavailable,
                        $min_seat,
                        $max_seat,
                        $calendar_id,
                        $this->user_id,
                        $package_id,
                        $slot_title,
                        $slot_description,
                        $slot_isprimary
                    );

                    $count++;
                }
            }
            if ($count > 2000) {
                break;
            }
        }
        if ($holidays > 0 && $count == 0) {
            $count = -1;
        }
        return $count;
    }

    public function getslots()
    {
        try {
            $_POST["from_date"] = $_POST["slot_date_from"];
            $fromdate = new DateTime($_POST["slot_date_from"]);
            $_POST["slot_date_from"] = $fromdate->format('Y,m,d');
            $_POST["to_date"] = $_POST["from_date"];
            if ($_POST["slot_date_to"] != '') {
                $todate = new DateTime($_POST["slot_date_to"]);
                $_POST["to_date"] = $_POST["slot_date_to"];
                $_POST["slot_date_to"] = $todate->format('Y,m,d');
            }
            $slot_av = 1;
            if (isset($_POST["slot_av"])) {
                $slot_av = $_POST["slot_av"];
            }
            $slot_av_max = $slot_av;
            if (isset($_POST["slot_av_max"])) {
                $slot_av_max = $_POST["slot_av_max"];
            }
            /*             * ********analyzing weekdays through date range******** */
            //separate day, month and year
            $arrDateFrom = explode(",", $_POST["slot_date_from"]);
            if ($_POST["slot_date_to"] != '') {
                $arrDateTo = explode(",", $_POST["slot_date_to"]);
            } else {
                $arrDateTo = explode(",", $_POST["slot_date_from"]);
            }
            //get an int for the two dates
            $dateFrom = str_replace(",", "", $_POST["slot_date_from"]);
            if ($_POST["slot_date_to"] != '') {
                $dateTo = str_replace(",", "", $_POST["slot_date_to"]);
            } else {
                $dateTo = str_replace(",", "", $_POST["slot_date_from"]);
            }
            //loop over weekdays selected
            $resultDate = array();

            for ($i = 0; $i < count($_POST["slot_weekday"]); $i++) {

                $newdateFrom = $dateFrom;

                $year = $arrDateFrom[0];
                $day = $arrDateFrom[2];
                $mo = $arrDateFrom[1];

                $date = strtotime(date('Y-m-d', mktime(0, 0, 0, $mo, $day, $year)));
                $weekday = date("N", $date);

                $j = 1;

                while ($weekday != $_POST["slot_weekday"][$i] && date("Ymd", $date) < $dateTo) {

                    $date = strtotime(date("Y-m-d", $date) . "+ 1 day");

                    $weekday = date("N", $date);
                }

                if (date("N", $date) == $_POST["slot_weekday"][$i]) {
                    array_push($resultDate, date('Y-m-d', $date));
                }

                while ($newdateFrom <= $dateTo) {
                    $test = strtotime(date("Y-m-d", $date) . "+" . $j . " week");
                    $j++;
                    if (date("Ymd", $test) <= $dateTo) {
                        array_push($resultDate, date("Y-m-d", $test));
                    }

                    $newdateFrom = date("Ymd", $test);
                }
            }

            $resultTime = array();
            if ($_POST["slot_interval"] == 0) {
                /*                 * *********custom times********** */
                //loop over custom times
                $int = 0;
                for ($i = 0; $i < count($_POST["slot_interval_custom_from_hour"]); $i++) {
                    if ($_POST["slot_interval_custom_from_hour"][$i] != "" && $_POST["slot_interval_custom_to_hour"][$i] != "") {
                        $resultTime[$i] = array();
                        $from_time = $_POST["slot_interval_custom_from_hour"][$i] . ":" . $_POST["slot_interval_custom_from_minute"][$i] . ' ' . $_POST["slot_interval_custom_from_ampm"][$i];
                        $to_time = $_POST["slot_interval_custom_to_hour"][$i] . ":" . $_POST["slot_interval_custom_to_minute"][$i] . ' ' . $_POST["slot_interval_custom_to_ampm"][$i];
                        $fromtimestamp = strtotime($from_time);
                        $totimestamp = strtotime($to_time);
                        if ($totimestamp > $fromtimestamp) {
                            $resultTime[$int]["time_to"] = date('H:i:s', $totimestamp);
                            $resultTime[$int]["time_from"] = date('H:i:s', $fromtimestamp);
                            $int++;
                        }
                    }
                }
                $resultTime = array_filter($resultTime);
            } else {
                $_POST["slot_interval_minutes"] = ($_POST["slot_interval_minutes"] > 0) ? $_POST["slot_interval_minutes"] : 0;
                /*                 * ********one or more intervals chosen*********** */
                $i = 0;
                for ($r = 0; $r < count($_POST["slot_time_from_hour"]); $r++) {
                    if ($_POST["slot_time_from_hour"][$r] != '' && $_POST["slot_time_from_minute"][$r] != '' && $_POST["slot_time_to_hour"][$r] != '' && $_POST["slot_time_to_minute"][$r] != '' && $_POST["slot_interval_minutes"] > 0) {

                        $from_time = $_POST["slot_time_from_hour"][$r] . ":" . $_POST["slot_time_from_minute"][$r] . ' ' . $_POST["slot_time_from_ampm"][$r];
                        $fromtimestamp = strtotime($from_time);
                        $_POST["slot_time_from_hour"][$r] = date('H', $fromtimestamp);

                        $to_time = $_POST["slot_time_to_hour"][$r] . ":" . $_POST["slot_time_to_minute"][$r] . ' ' . $_POST["slot_time_to_ampm"][$r];
                        $totimestamp = strtotime($to_time);
                        $_POST["slot_time_to_hour"][$r] = date('H', $totimestamp);

                        $arrTimeFrom = array($_POST["slot_time_from_hour"][$r], $_POST["slot_time_from_minute"][$r]);
                        $arrTimeTo = array($_POST["slot_time_to_hour"][$r], $_POST["slot_time_to_minute"][$r]);
                        //get an int for the two times

                        $timeFrom = $_POST["slot_time_from_hour"][$r] . $_POST["slot_time_from_minute"][$r];
                        $timeTo = $_POST["slot_time_to_hour"][$r] . $_POST["slot_time_to_minute"][$r];

                        $newtimeFrom = $timeFrom;
                        $hour = $arrTimeFrom[0];
                        $minute = $arrTimeFrom[1];
                        if ($_POST["slot_pause"] == 0 || $_POST["slot_pause"] == '') {
                            $j = $_POST["slot_interval_minutes"];
                            $totinterval = $_POST["slot_interval_minutes"];
                        } else {
                            $j = $_POST["slot_interval_minutes"] + $_POST["slot_pause"];
                            $totinterval = $_POST["slot_interval_minutes"] + $_POST["slot_pause"];
                        }


                        $resultTime[$i] = array();
                        $resultTime[$i]["time_from"] = date("H:i:s", mktime($hour, $minute, 0));
                        $resultTime[$i]["time_to"] = date("H:i:s", strtotime(date("H:i:s", mktime($hour, $minute, 0)) . "+" . $_POST["slot_interval_minutes"] . " minutes"));
                        $i++;

                        while ($newtimeFrom < $timeTo) {
                            // echo $newtimeFrom .' aa '. $timeTo.'<br>';
                            if (date('Hi', strtotime(date("H:i:s", mktime($hour, $minute, 0)) . "+" . $j . " minutes")) <= "2359" && date('Hi', strtotime(date("H:i:s", mktime($hour, $minute, 0)) . "+" . $j . " minutes")) > $timeFrom && date('Hi', strtotime(date("H:i:s", mktime($hour, $minute, 0)) . "+" . $j . " minutes")) <= $timeTo) {
                            } else {
                                break;
                            }
                            $test = strtotime(date("H:i:s", mktime($hour, $minute, 0)) . "+" . $j . " minutes");
                            if ($_POST["slot_pause"] == 0 || $_POST["slot_pause"] == '') {
                                $j = $j + $_POST["slot_interval_minutes"];
                            } else {
                                $j = $j + $_POST["slot_interval_minutes"] + $_POST["slot_pause"];
                            }

                            if (date("Hi", $test) < $timeTo) {
                                $totime = strtotime(date("H:i:s", $test) . "+" . $_POST["slot_interval_minutes"] . " minutes");
                                if ($totimestamp >= $totime) {
                                    $resultTime[$i] = array();
                                    $resultTime[$i]["time_from"] = date("H:i:s", $test);
                                    $resultTime[$i]["time_to"] = date("H:i:s", $totime);
                                }
                            }
                            $newtimeFrom = date("Hi", $test);
                            if ($newtimeFrom < $timeTo) {
                                $i++;
                            }
                        }
                    }
                }
            }
            return array('dates' => $resultDate, 'slots' => $resultTime);
            //now that I have date and times I can insert the slots
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB006]Error while creating supplier Error: ' . $e->getMessage());
            header("Location:/error");
        }
    }

    public function getslotsv2(
        $slot_date_from,
        $slot_date_to,
        $slot_weekday,
        $slot_interval,
        $slot_interval_custom_from_hour,
        $slot_interval_custom_to_hour,
        $slot_interval_custom_from_minute,
        $slot_interval_custom_to_minute,
        $slot_interval_minutes,
        $slot_time_from_hour,
        $slot_time_from_minute,
        $slot_time_to_hour,
        $slot_time_to_minute,
        $slot_pause,
        $slot_interval_custom_from_ampm,
        $slot_interval_custom_to_ampm,
        $slot_time_from_ampm,
        $slot_time_to_ampm
    ) {
        try {
            $_POST["from_date"] = $slot_date_from;
            $fromdate = new DateTime($slot_date_from);
            $slot_date_from = $fromdate->format('Y,m,d');
            $_POST["to_date"] = $_POST["from_date"];
            if ($slot_date_to != '') {
                $todate = new DateTime($slot_date_to);
                $_POST["to_date"] = $slot_date_to;
                $slot_date_to = $todate->format('Y,m,d');
            }
            $slot_av = 1;
            if (isset($_POST["slot_av"])) {
                $slot_av = $_POST["slot_av"];
            }
            $slot_av_max = $slot_av;
            if (isset($_POST["slot_av_max"])) {
                $slot_av_max = $_POST["slot_av_max"];
            }
            /*             * ********analyzing weekdays through date range******** */
            //separate day, month and year
            $arrDateFrom = explode(",", $slot_date_from);
            if ($slot_date_to != '') {
                $arrDateTo = explode(",", $slot_date_to);
            } else {
                $arrDateTo = explode(",", $slot_date_from);
            }
            //get an int for the two dates
            $dateFrom = str_replace(",", "", $slot_date_from);
            if ($slot_date_to != '') {
                $dateTo = str_replace(",", "", $slot_date_to);
            } else {
                $dateTo = str_replace(",", "", $slot_date_from);
            }
            //loop over weekdays selected
            $resultDate = array();

            for ($i = 0; $i < count($slot_weekday); $i++) {

                $newdateFrom = $dateFrom;

                $year = $arrDateFrom[0];
                $day = $arrDateFrom[2];
                $mo = $arrDateFrom[1];

                $date = strtotime(date('Y-m-d', mktime(0, 0, 0, $mo, $day, $year)));
                $weekday = date("N", $date);

                $j = 1;

                while ($weekday != $slot_weekday[$i] && date("Ymd", $date) < $dateTo) {

                    $date = strtotime(date("Y-m-d", $date) . "+ 1 day");

                    $weekday = date("N", $date);
                }

                if (date("N", $date) == $slot_weekday[$i]) {
                    array_push($resultDate, date('Y-m-d', $date));
                }

                while ($newdateFrom <= $dateTo) {
                    $test = strtotime(date("Y-m-d", $date) . "+" . $j . " week");
                    $j++;
                    if (date("Ymd", $test) <= $dateTo) {
                        array_push($resultDate, date("Y-m-d", $test));
                    }

                    $newdateFrom = date("Ymd", $test);
                }
            }

            $resultTime = array();

            if ($slot_interval == 0) {
                /*                 * *********custom times********** */
                //loop over custom times
                $int = 0;
                for ($i = 0; $i < count($slot_interval_custom_from_hour); $i++) {
                    if ($slot_interval_custom_from_hour[$i] != "" && $slot_interval_custom_to_hour[$i] != "") {
                        $resultTime[$i] = array();
                        $from_time = $slot_interval_custom_from_hour[$i] . ":" . $slot_interval_custom_from_minute[$i] . ' ' . $slot_interval_custom_from_ampm[$i];
                        $to_time = $slot_interval_custom_to_hour[$i] . ":" . $slot_interval_custom_to_minute[$i] . ' ' . $slot_interval_custom_to_ampm[$i];
                        $fromtimestamp = strtotime($from_time);
                        $totimestamp = strtotime($to_time);
                        if ($totimestamp > $fromtimestamp) {
                            $resultTime[$int]["time_to"] = date('H:i:s', $totimestamp);
                            $resultTime[$int]["time_from"] = date('H:i:s', $fromtimestamp);
                            $int++;
                        }
                    }
                }
                $resultTime = array_filter($resultTime);
            } else {

                $slot_interval_minutes = ($slot_interval_minutes > 0) ? $slot_interval_minutes : 0;
                /*                 * ********one or more intervals chosen*********** */
                $i = 0;
                for ($r = 0; $r < count($slot_time_from_hour); $r++) {
                    if ($slot_time_from_hour[$r] != '' && $slot_time_from_minute[$r] != '' && $slot_time_to_hour[$r] != '' && $slot_time_to_minute[$r] != '' && $slot_interval_minutes > 0) {

                        $from_time = $slot_time_from_hour[$r] . ":" . $slot_time_from_minute[$r] . ' ' . $slot_time_from_ampm[$r];
                        $fromtimestamp = strtotime($from_time);
                        $slot_time_from_hour[$r] = date('H', $fromtimestamp);

                        $to_time = $slot_time_to_hour[$r] . ":" . $slot_time_to_minute[$r] . ' ' . $slot_time_to_ampm[$r];
                        $totimestamp = strtotime($to_time);
                        $slot_time_to_hour[$r] = date('H', $totimestamp);

                        $arrTimeFrom = array($slot_time_from_hour[$r], $slot_time_from_minute[$r]);
                        $arrTimeTo = array($slot_time_to_hour[$r], $slot_time_to_minute[$r]);
                        //get an int for the two times

                        $timeFrom = $slot_time_from_hour[$r] . $slot_time_from_minute[$r];
                        $timeTo = $slot_time_to_hour[$r] . $slot_time_to_minute[$r];

                        $newtimeFrom = $timeFrom;
                        $hour = $arrTimeFrom[0];
                        $minute = $arrTimeFrom[1];
                        if ($slot_pause == 0 || $slot_pause == '') {
                            $j = $slot_interval_minutes;
                            $totinterval = $slot_interval_minutes;
                        } else {
                            $j = $slot_interval_minutes + $slot_pause;
                            $totinterval = $slot_interval_minutes + $slot_pause;
                        }


                        $resultTime[$i] = array();
                        $resultTime[$i]["time_from"] = date("H:i:s", mktime($hour, $minute, 0));
                        $resultTime[$i]["time_to"] = date("H:i:s", strtotime(date("H:i:s", mktime($hour, $minute, 0)) . "+" . $slot_interval_minutes . " minutes"));
                        $i++;

                        while ($newtimeFrom < $timeTo) {
                            // echo $newtimeFrom .' aa '. $timeTo.'<br>';
                            if (date('Hi', strtotime(date("H:i:s", mktime($hour, $minute, 0)) . "+" . $j . " minutes")) <= "2359" && date('Hi', strtotime(date("H:i:s", mktime($hour, $minute, 0)) . "+" . $j . " minutes")) > $timeFrom && date('Hi', strtotime(date("H:i:s", mktime($hour, $minute, 0)) . "+" . $j . " minutes")) <= $timeTo) {
                            } else {
                                break;
                            }
                            $test = strtotime(date("H:i:s", mktime($hour, $minute, 0)) . "+" . $j . " minutes");
                            if ($slot_pause == 0 || $slot_pause == '') {
                                $j = $j + $slot_interval_minutes;
                            } else {
                                $j = $j + $slot_interval_minutes + $slot_pause;
                            }

                            if (date("Hi", $test) < $timeTo) {
                                $totime = strtotime(date("H:i:s", $test) . "+" . $slot_interval_minutes . " minutes");
                                if ($totimestamp >= $totime) {
                                    $resultTime[$i] = array();
                                    $resultTime[$i]["time_from"] = date("H:i:s", $test);
                                    $resultTime[$i]["time_to"] = date("H:i:s", $totime);
                                }
                            }
                            $newtimeFrom = date("Hi", $test);
                            if ($newtimeFrom < $timeTo) {
                                $i++;
                            }
                        }
                    }
                }
            }
            return array('dates' => $resultDate, 'slots' => $resultTime);
            //now that I have date and times I can insert the slots
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[EB006]Error while creating supplier Error: ' . $e->getMessage());
            header("Location:/error");
        }
    }

    function selectslot()
    {
        $this->validatePackage();
        $category_list = $this->model->getBookingCategories($this->merchant_id);
        $date = new DateTime($_POST['booking_date']);

        $last_date = strtotime($_POST['booking_date'] . ' -1 day');
        $last_date = date('d M Y', $last_date);
        $next_date = strtotime($_POST['booking_date'] . ' 1 day');
        $next_date = date('d M Y', $next_date);
        require_once MODEL . 'merchant/BookingsModel.php';
        $bookingModel = new BookingsModel();
        $slots_array = $bookingModel->getCategorySlots($date->format('Y-m-d'), $_POST['category_id']);
        $slot_id = 0;
        if ($_POST['calendar_id'] != '') {
            $from_time = substr($_POST['slot_time'], 0, 8);
            $to_time = substr($_POST['slot_time'], 12, 8);
            $from_time = new DateTime($from_time);
            $to_time = new DateTime($to_time);
            $slot_id = $bookingModel->getSlotCategoryCourtID($date->format('Y-m-d'), $from_time->format('H:i:s'), $to_time->format('H:i:s'), $_POST['category_id'], $_POST['calendar_id']);
        }
        foreach ($slots_array as $slot) {
            $slots[] = $slot['time_from'] . ' To ' . $slot['time_to'];
        }
        $this->smarty->assign("max_booking", 0);
        $court_list = array();
        if (isset($_POST['booking_date'])) {
            $court_list = $this->model->getCategoryCourt($date->format('Y-m-d'), $_POST['category_id']);

            $date_slected = $_POST['booking_date'];
            $this->view->category_id = $_POST['category_id'];
            $this->view->booking_date = $_POST['booking_date'];
            $this->smarty->assign("max_booking", $court_list[0]['max_booking']);
        } else {
            $date_slected = date('d M Y');
        }

        $int = 0;
        $this->view->js = array('booking');
        //$slotsdet = $bookingModel->getSlots($date->format('Y-m-d'), $_POST['calendar_id']);
        //dd($date->format('Y-m-d').','. $_POST['calendar_id'].','.$_POST['package_id']);
        $slotsdet = $bookingModel->getSlotsv2($date->format('Y-m-d'), $_POST['calendar_id'], $_POST['package_id']);
        $package = $bookingModel->getPackageDetails($_POST['package_id']);
        //$packages = $bookingModel->getPackagesbyID($date->format('Y-m-d'), $_POST['calendar_id'],'75' );
        //dd(json_encode($slotsdet));
        foreach ($slotsdet as &$data) {
            $slots_package_array = [];
            if ($data["slot_price_count"] > 1) {
                $slot_title_array = explode(",", $data["slot_title"]);
                $slot_description_array = explode(",", $data["slot_description"]);
                $slot_price_array = explode(",", $data["slot_price"]);
                $slot_id_array = explode(",", $data["slot_id"]);
                $is_multiple_array = explode(",", $data["is_multiple"]);
                $min_seat_array = explode(",", $data["min_seat"]);
                $max_seat_array = explode(",", $data["max_seat"]);
                $total_seat_array = explode(",", $data["total_seat"]);
                $available_seat_array = explode(",", $data["available_seat"]);
                $slot_special_text_array = explode(",", $data["slot_special_text"]);
                foreach ($slot_title_array as $lkey => $slot_title) {
                    $slot_price = $slot_price_array[$lkey];
                    $slot_description = $slot_description_array[$lkey];
                    $slot_ids = $slot_id_array[$lkey];
                    $is_multiple = $is_multiple_array[$lkey];
                    $min_seat = $min_seat_array[$lkey];
                    $max_seat = $max_seat_array[$lkey];
                    $total_seat = $total_seat_array[$lkey];
                    $available_seat = $available_seat_array[$lkey];
                    $slot_special_text = $slot_special_text_array[$lkey];
                    $array = [
                        'slot_id' =>  $slot_ids,
                        'slot_title' =>  $slot_title,
                        'slot_description' => $slot_description,
                        'is_multiple' => $is_multiple,
                        'slot_price' => $slot_price,
                        'min_seat' => $min_seat,
                        'max_seat' => $max_seat,
                        'total_seat' => $total_seat,
                        'available_seat' => $available_seat,
                        'slot_special_text' => $slot_special_text
                    ];
                    array_push($slots_package_array, $array);
                }
            } else if ($data["slot_price_count"] == 1) {
                $array = [
                    'slot_id' =>  $data["slot_id"],
                    'slot_title' => $data["slot_title"],
                    'slot_description' => $data["slot_description"],
                    'slot_price' => $data["slot_price"],
                    'is_multiple' => $data["is_multiple"],
                    'slot_price' =>  $data["slot_price"],
                    'min_seat' =>  $data["min_seat"],
                    'max_seat' =>  $data["max_seat"],
                    'total_seat' => $data["total_seat"],
                    'available_seat' => $data["available_seat"],
                    'slot_special_text' =>  $data["slot_special_text"]
                ];

                array_push($slots_package_array, $array);
            }
            $data["slots_package_array"] = $slots_package_array;
        }
        $this->smarty->assign("merchant_id", $this->merchant_id);
        $this->smarty->assign("category_id", $_POST['category_id']);
        $this->smarty->assign("court_name", $_POST['court_name']);
        $this->smarty->assign("calendar_id", $_POST['calendar_id']);
        $this->smarty->assign("package_id", $_POST['package_id']);
        $this->smarty->assign("description", $description);
        $this->smarty->assign("slot_id", $slot_id);
        $this->smarty->assign("packages", $packages);
        $this->smarty->assign("next_date", $next_date);
        $this->smarty->assign("last_date", $last_date);
        $this->smarty->assign("category_list", $category_list);
        $this->smarty->assign("slots", $slotsdet);
        $this->smarty->assign("slots_array", $slots);
        $this->smarty->assign("package", $package);
        $this->smarty->assign("slot_time", $_POST['slot_time']);
        $this->smarty->assign("current_date", $_POST['booking_date']);
        $this->smarty->assign("merchant_id", $this->merchant_id);
        $this->view->selectedMenu = array(9, 39, 82);
        $this->view->js = array('booking');
        $this->session->set('valid_ajax', 'calendarJson');
        $this->smarty->assign("title", 'Slots');
        $this->view->title = "Slots";

        //Breadcumbs array start
        $breadcumbs_array = array(
            array('title' => 'Booking calendar', 'url' => ''),
            array('title' => 'Book now', 'url' => ''),
            array('title' => $this->view->title, 'url' => '/merchant/bookings/selectslot')
        );
        $this->smarty->assign("links", $breadcumbs_array);
        //Breadcumbs array end

        $this->view->selected = 'booking';
        $this->view->header_file = ['profile'];
        $this->view->render('header/app');
        $this->smarty->display(VIEW . 'merchant/booking/select_slot.tpl');
        $this->view->render('footer/profile');
    }

    public function slotbooking()
    {
        try {
            $merchant_id = $this->session->get('merchant_id');
            if (count($_POST['booking_slots']) < 2) {
                header('location: /merchant/bookings/selectslot');
                die();
            }

            require MODEL . 'merchant/CustomerModel.php';
            $Customermodel = new CustomerModel();
            $customer_list = $Customermodel->getCustomerallList($merchant_id);
            $merchant_setting = $this->common->getSingleValue('merchant_setting', 'merchant_id', $merchant_id);
            $customer_column = $Customermodel->getCustomerBreakup($merchant_id);
            $this->smarty->assign("column", $customer_column);
            $this->smarty->assign("merchant_setting", $merchant_setting);
            $this->smarty->assign("customer_list", $customer_list);
            $this->smarty->assign("current_date", date('d M Y'));
            $banklist = $this->common->querylist("select config_key, config_value from config where config_type='Bank_name'");
            if (empty($banklist)) {
                SwipezLogger::warn(__CLASS__, 'Fetching empty bank list');
            }
            foreach ($banklist as $value) {
                $bank_ids[] = $value['config_key'];
                $bank_values[] = $value['config_value'];
            }
            $this->smarty->assign("bank_id", $bank_ids);
            $this->smarty->assign("bank_value", $bank_values);

            $sint = 0;
            foreach ($_POST['booking_slot_id'] as $slots_id) {
                if ($_POST['booking_slot_id'][$sint] > 0) {
                    $booking_slots_ids[] = $_POST['booking_slot_id'][$sint];
                    $booking_slots_qty[] = $_POST['booking_qty'][$sint];
                }
                $sint++;
            }

            $cal_info = $this->model->getCalendarCategory($_POST['calendar_id']);
            $slots_array = $this->model->getSlots($_POST['date'], $_POST['calendar_id']);
            $int = 0;
            foreach ($slots_array as $slot) {
                if (in_array($slot['slot_id'], $booking_slots_ids)) {
                    $booking_slots[$int]['fromto'] = 'From ' . $slot['time_from'] . ' To ' . $slot['time_to'];
                    $booking_slots[$int]['amount'] = $slot['slot_price'];
                    $booking_slots[$int]['slot_id'] = $slot['slot_id'];
                    $booking_slots[$int]['category'] = $cal_info['category_name'];
                    $booking_slots[$int]['calendar'] = $cal_info['calendar_title'];
                    $keys = array_keys($booking_slots_ids, $slot['slot_id']);
                    $qty = $booking_slots_qty[$keys[0]];
                    $booking_slots[$int]['booking_qty'] = $qty;
                    $amount = $amount + ($slot['slot_price'] * $qty);
                    $int++;
                }
            }
            $this->smarty->assign("booking_slots", $booking_slots);
            $this->smarty->assign("category_name", $cal_info['category_name']);
            $this->smarty->assign("calendar_title", $cal_info['calendar_title']);
            $this->smarty->assign("date", $_POST['date']);
            $this->smarty->assign("calendar_id", $_POST['calendar_id']);
            $this->smarty->assign("grand_total", $amount);
            $this->smarty->assign("absolute_cost", $amount);
            $this->smarty->assign("package_type", $this->session->get('package_type'));
            $this->smarty->assign("company_name", $this->session->get('company_name'));
            $this->session->set('confirm_payment', TRUE);
            $this->view->selectedMenu = array(9, 39);
            $this->view->selected = $this->session->get('package_type');
            $this->smarty->assign("title", $cal_info['category_name'] . ' ' . $cal_info['calendar_title']);
            $this->smarty->assign("booking_title", $cal_info['category_name'] . ' ' . $cal_info['calendar_title']);
            $this->view->title = 'Confirm your payment';
            $this->view->header_file = ['profile'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/booking/confirm.tpl');
            $this->view->render('footer/invoice');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E042]Error while payment request pay initiate Error:for user id [' . $this->session->get('userid') . '] and for payment request id [' . $payment_request_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function bookmembership()
    {
        try {
            $this->validatePackage();
            $merchant_id = $this->session->get('merchant_id');

            require MODEL . 'merchant/CustomerModel.php';
            $Customermodel = new CustomerModel();
            $customer_list = $Customermodel->getCustomerallList($merchant_id);
            $merchant_setting = $this->common->getSingleValue('merchant_setting', 'merchant_id', $merchant_id);
            $customer_column = $Customermodel->getCustomerBreakup($merchant_id);
            $list = $this->model->getBookingMembership($this->merchant_id);
            $this->smarty->assign("list", $list);
            $this->smarty->assign("column", $customer_column);
            $this->smarty->assign("merchant_setting", $merchant_setting);
            $this->smarty->assign("customer_list", $customer_list);
            $this->smarty->assign("current_date", date('d M Y'));
            $banklist = $this->common->querylist("select config_key, config_value from config where config_type='Bank_name'");
            if (empty($banklist)) {
                SwipezLogger::warn(__CLASS__, 'Fetching empty bank list');
            }
            foreach ($banklist as $value) {
                $bank_ids[] = $value['config_key'];
                $bank_values[] = $value['config_value'];
            }
            $this->session->set('valid_ajax', 'bookingMembership');
            $this->view->js = array('booking');
            $this->smarty->assign("bank_id", $bank_ids);
            $this->smarty->assign("bank_value", $bank_values);
            $this->smarty->assign("company_name", $this->session->get('company_name'));
            $this->session->set('confirm_payment', TRUE);
            $this->view->selectedMenu = array(9, 39, 83);
            $this->view->selected = $this->session->get('package_type');
            $this->smarty->assign("title", 'Membership booking');
            $this->smarty->assign("booking_title", 'Member booking');
            $this->view->title = 'Confirm your payment';

            //Breadcumbs array start
            $breadcumbs_array = array(
                array('title' => 'Booking calendar', 'url' => ''),
                array('title' => 'Book now', 'url' => ''),
                array('title' => 'Membership booking', 'url' => '')
            );
            $this->smarty->assign("links", $breadcumbs_array);
            //Breadcumbs array end

            $this->view->header_file = ['profile'];
            $this->view->render('header/app');
            $this->smarty->display(VIEW . 'merchant/booking/membership/confirm.tpl');
            $this->view->render('footer/invoice');
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, '[E042]Error while payment request pay initiate Error:for user id [' . $this->session->get('userid') . '] and for payment request id [' . $payment_request_id . ']' . $e->getMessage());
            $this->setGenericError();
        }
    }

    public function respond()
    {
        if (empty($_POST)) {
            header('Location:/error');
        }
        require CONTROLLER . 'Paymentvalidator.php';
        $validator = new Paymentvalidator($this->model);
        $validator->validateEventMerchantRespond();
        $hasErrors = $validator->fetchErrors();
        if ($hasErrors == false) {
            $date = new DateTime($_POST['date']);

            $booking_date = new DateTime($_POST['booking_date']);
            $_POST['bank_transaction_no'] = (isset($_POST['bank_transaction_no'])) ? $_POST['bank_transaction_no'] : '';
            $_POST['cheque_no'] = (isset($_POST['cheque_no'])) ? $_POST['cheque_no'] : '';
            $_POST['cash_paid_to'] = (isset($_POST['cash_paid_to'])) ? $_POST['cash_paid_to'] : '';
            $amount = $_POST['amount'];
            $transaction_id = $this->model->save_slot_transaction(5, $_POST['customer_id'], $this->merchant_id, $this->user_id, $amount, 0, $_POST['bank_name'], $date->format('Y-m-d'), $_POST['bank_transaction_no'], $_POST['cheque_no'], $_POST['cash_paid_to'], $_POST['response_type'], count($_POST['booking_slots']), $_POST['narrative']);
            if (isset($transaction_id)) {

                $int = 0;
                foreach ($_POST['booking_slots'] as $slot_) {
                    $this->model->saveBookingTransactionDetails($transaction_id, $_POST['calendar_id'], $booking_date->format('Y-m-d'), $_POST['booking_slots'][$int], $_POST['booking_fromto'][$int], $_POST['category_name'], $_POST['calendar_title'], $_POST['booking_amount'][$int], 0, $this->merchant_id, 1);
                    $int++;
                }
                $this->model->updateSlotsStatus($transaction_id);


                $receipt_info = $this->common->getReceipt($transaction_id, 'Offline');
                require_once CONTROLLER . '/Secure.php';
                $secure = new Secure();
                $receipt_info['BillingEmail'] = $receipt_info['patron_email'];
                $receipt_info['MerchantRefNo'] = $receipt_info['transaction_id'];
                $receipt_info['BillingName'] = $receipt_info['patron_name'];
                $receipt_info['merchant_name'] = $receipt_info['company_name'];
                $receipt_info['is_offline'] = 1;
                $secure->sendMailReceipt($receipt_info, 'booking');
                $logo = '';
                if ($receipt_info['image'] == '') {
                    if ($receipt_info['merchant_logo'] != '') {
                        $logo = '/uploads/images/landing/' . $receipt_info['merchant_logo'];
                    }
                } else {
                    $logo = '/uploads/images/logos/' . $receipt_info['image'];
                }
                $this->smarty->assign("logo", $logo);
                $this->view->selectedMenu = array(9, 39);
                $this->view->title = 'Confirm your payment';
                $attendee_details = $this->common->getBookingDetails($transaction_id);
                $this->smarty->assign("response", $receipt_info);

                $this->smarty->assign("booking_details", $attendee_details);
                $this->view->header_file = ['profile'];
                $this->view->render('header/app');
                $this->smarty->display(VIEW . 'merchant/transaction/offlinereceipt.tpl');
                $this->view->render('footer/profile');
            }
        }
    }

    public function membershiprespond()
    {
        if (empty($_POST)) {
            header('Location:/error');
        }
        require CONTROLLER . 'Paymentvalidator.php';
        $validator = new Paymentvalidator($this->model);
        $validator->validateEventMerchantRespond();
        $hasErrors = $validator->fetchErrors();
        if ($hasErrors == false) {
            $date = new DateTime($_POST['date']);
            $amount = $_POST['amount'];
            $booking_date = new DateTime($_POST['booking_date']);

            $transaction_id = $this->model->save_slot_transaction(6, $_POST['customer_id'], $this->merchant_id, $this->user_id, $amount, 0, $_POST['bank_name'], $date->format('Y-m-d'), $_POST['bank_transaction_no'], $_POST['cheque_no'], $_POST['cash_paid_to'], $_POST['response_type'], 1, $_POST['narrative']);
            if (isset($transaction_id)) {
                $mem_det = $this->common->getSingleValue('booking_membership', 'membership_id', $_POST['membership_id']);
                $patron_id = $this->common->getRowValue('user_id', 'customer', 'customer_id', $_POST['customer_id']);
                $this->model->saveBookingMembershipDetails($transaction_id, $this->merchant_id, $this->user_id, $_POST['customer_id'], $mem_det['category_id'], $_POST['membership_id'], $mem_det['days'], $date->format('Y-m-d'), $mem_det['title'], $_POST['amount'], 1);

                $receipt_info = $this->common->getReceipt($transaction_id, 'Offline');
                require_once CONTROLLER . '/Secure.php';
                $secure = new Secure();
                $receipt_info['BillingEmail'] = $receipt_info['patron_email'];
                $receipt_info['MerchantRefNo'] = $receipt_info['transaction_id'];
                $receipt_info['BillingName'] = $receipt_info['patron_name'];
                $receipt_info['merchant_name'] = $receipt_info['company_name'];
                $receipt_info['is_offline'] = 1;
                $secure->sendMailReceipt($receipt_info, 'booking');
                $logo = '';
                if ($receipt_info['image'] == '') {
                    if ($receipt_info['merchant_logo'] != '') {
                        $logo = '/uploads/images/landing/' . $receipt_info['merchant_logo'];
                    }
                } else {
                    $logo = '/uploads/images/logos/' . $receipt_info['image'];
                }
                $this->smarty->assign("logo", $logo);
                $this->view->selectedMenu = array(9, 39);
                $this->smarty->assign("response", $receipt_info);
                $this->view->header_file = ['profile'];
                $this->view->render('header/app');
                $this->smarty->display(VIEW . 'merchant/transaction/offlinereceipt.tpl');
                $this->view->render('footer/profile');
            }
        }
    }
}
