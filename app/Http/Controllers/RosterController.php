<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DashboardController
 *
 * @author Paresh
 */

namespace App\Http\Controllers;

use App\Model\Roster;
use App\Model\Trip;
use App\Model\Master;
use App\Model\Employee;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use PDF;
use Excel;

class RosterController extends Controller {

    private $roster_model;
    private $master_model;
    private $schedulerMobile = array('9730946150');
    private $tripAsignMobile = array('9730946150');
    private $reviewMobile = array('9730946150', '8879391658');

    function __construct() {
        parent::__construct();
        $this->roster_model = new Roster();
        $this->master_model = new Master();
    }

    public function trip($type, $roster_link) {
        if (Session::get('success_message')) {
            $data['success_message'] = Session::get('success_message');
            $_SESSION['success_message'] = '';
            CustomSession::remove('success_message');
        }
		
        $data['login_type'] = '';
        if ($type == 'admin') {
            if (Session::get('logged_in') != 1) {
                $type = 'nonloginadmin';
            } else {
                $this->validateSession(array(1, 4, 5));
                $data['login_type'] = 'admin';
            }
        }

        if ($type == 'employee') {
            $id = $this->encrypt->decode($roster_link);
            $data['roster_emp_link'] = $roster_link;
            $detail = $this->master_model->getMasterDetail('roster_employee', 'id', $id);
            $this->master_model->updateTableColumn('roster_employee', 'seen', '1', 'id', $id, $detail->employee_id);
            $data['pdet'] = $detail;
            $empdetail = $this->master_model->getMasterDetail('passenger', 'id', $detail->employee_id);
            $data['edet'] = $empdetail;
            $roster_id = $detail->roster_id;
            $link = $this->encrypt->encode($roster_id);
        } else {
            $roster_id = $this->encrypt->decode($roster_link);
            $link = $roster_link;
        }
        $roster_emp = $this->roster_model->rosterEmployee($roster_id);

        $rosterdetail = $this->master_model->getMasterDetail('roster', 'roster_id', $roster_id);

        $driverdetail = $this->master_model->getMasterDetail('driver', 'driver_id', $rosterdetail->driver_id);
        $vehidetail = $this->master_model->getMasterDetail('vehicle', 'vehicle_id', $rosterdetail->vehicle_id);

        $route = $this->master_model->getMasterDetail('route', 'route_id', $rosterdetail->route_id);
        $pkgroute = $this->master_model->getMasterDetail('packageroute', 'id', $rosterdetail->package_route_id);
		

        $data['route_no'] = $route->name;
        $data['route_name'] = '';
		
        $data['roster_emp'] = $roster_emp;
        $data['ddet'] = $driverdetail;
        $data['vdet'] = $vehidetail;
        $data['det'] = $rosterdetail;
        $data['link'] = $link;
		
        $data['roster_id'] = $roster_id;
        $data['title'] = 'Trip Details';
		
        return view('roster.trip' . $type, $data);
    }

    public function printroster($link) {
        $this->validateSession(array(1, 4, 5));
        $roster_id = $this->encrypt->decode($link);
        $roster_emp = $this->roster_model->rosterEmployee($roster_id);

        $rosterdetail = $this->master_model->getMasterDetail('roster', 'roster_id', $roster_id);

        $driverdetail = $this->master_model->getMasterDetail('driver', 'driver_id', $rosterdetail->driver_id);
        $vehidetail = $this->master_model->getMasterDetail('vehicle', 'vehicle_id', $rosterdetail->vehicle_id);

        $route = $this->master_model->getMasterDetail('route', 'route_id', $rosterdetail->route_id);
        $pkgroute = $this->master_model->getMasterDetail('packageroute', 'id', $rosterdetail->package_route_id);

        $data['route_no'] = $route->name;
        if (isset($pkgroute->location)) {
            $data['route_name'] = $pkgroute->location;
        }

        $data['first_time'] = $roster_emp[0]->pickup_time;
        $data['roster_emp'] = $roster_emp;
        $data['ddet'] = $driverdetail;
        $data['vdet'] = $vehidetail;
        $data['det'] = $rosterdetail;
        $data['link'] = $link;
        $data['roster_id'] = $roster_id;
        $data['title'] = 'Roster Print';

        return view('roster.print', $data);
    }

    public function savereview() {
        $trip_model = new Trip();
        $id = $trip_model->saveReview($_POST, 'Guest');
        $this->setSuccess($_POST['type'] . ' has been send successfully');
        $link = $this->encrypt->encode($id);
        $link = 'http://gurgaon.svktrv.in/review/' . $link;
        $short_url = $this->master_model->getShortUrl($link);
        $sms = "Passenger Send  " . $_POST['type'] . " for Trip check below " . $short_url;
        foreach ($this->reviewMobile as $mobile) {
            $this->sms_send($mobile, $sms);
        }
        header('Location: /roster/trip/employee/' . $_POST['link']);
        exit;
    }

    public function rating($link, $rating) {
        $id = $this->encrypt->decode($link);
        switch ($rating) {
            case 1:
                $text = 'Disaster';
                break;
            case 2:
                $text = 'Bad';
                break;
            case 3:
                $text = 'Ok';
                break;
            case 4:
                $text = 'Good';
                break;
            case 5:
                $text = 'Awesome';
                break;
        }
        $this->roster_model->updateTripRating($rating, $id);
        echo $text;
    }

    public function create() {
        $this->validateSession(array(1, 4, 5));
        $this->employee_model = new Employee();
        $mis_employee = $this->employee_model->getPassengers();
        $route = $this->master_model->getMaster('route', 0,'company_id');
        $data['title'] = 'Roster';
        $data['current_date'] = date('d-m-Y');
        $data['mis_employee'] = $mis_employee;
        $data['route_list'] = $route;
        $data['user_type'] = $this->user_type;
        return view('roster.create', $data);
    }

    public function sms() {
        $this->validateSession(array(1, 4, 5));
        $data['title'] = 'SMS';
        $data['user_type'] = $this->user_type;
        return view('roster.sms', $data);
    }

    public function sendsms() {
        $this->validateSession(array(1, 4, 5));
        $mobiles = explode(',', $_POST['mobile']);
        foreach ($mobiles as $mobile) {
            $this->sms_send($mobile, $_POST['sms']);
        }
        $this->setSuccess('SMS Sent successfully');
        header('Location: /admin/sms');
        exit;
    }

    public function saveroster(Request $request) {
        $this->validateSession(array(1, 4, 5));
        $date = date('Y-m-d', strtotime($_POST['date']));
        $roster_id = $this->roster_model->saveRoster($date, $_POST['pickup'], $_POST['route_id'], $_POST['narrative'], $this->user_id);
        foreach ($_POST['employee_id'] as $key => $employee_id) {
            if ($employee_id > 0) {
                $shift_time = date('H:i:s', strtotime($_POST['shift_time'][$key]));
                $this->roster_model->saveRosterEmployee($roster_id, $employee_id, $shift_time, $this->user_id);
            }
        }
        echo 'Roster has been saved successfully';
    }

    public function updatesaveroster(Request $request) {
        $this->validateSession(array(1, 4, 5));
        $date = date('Y-m-d', strtotime($_POST['date']));
        $roster_id = $_POST['roster_id'];
        $this->roster_model->updateRoster($roster_id, $date, $_POST['pickup'], $_POST['route_id'], $_POST['narrative'], $this->user_id);
        $this->roster_model->deleteRosterEmployee($roster_id, $this->user_id);
        foreach ($_POST['employee_id'] as $key => $employee_id) {
            if ($employee_id > 0) {
                $shift_time = date('H:i:s', strtotime($_POST['shift_time'][$key]));
                $this->roster_model->saveRosterEmployee($roster_id, $employee_id, $shift_time, $this->user_id);
            }
        }

        $this->setSuccess('Roster has been updated successfully');
        header('Location: /admin/roster/list');
        exit;
    }

    public function asignsaveroster(Request $request) {
        $this->validateSession(array(1, 4, 5));
        foreach ($_POST['roster_id'] as $key => $roster_id)
         $this->roster_model->asignRoster($roster_id, $_POST['driver_id'][$key], $_POST['vehicle_id'][$key], 0, $this->user_id);
        $this->setSuccess('Roster has been asigned successfully');
        header('Location: /admin/roster/list');
        exit;
    }

    public function rosterlist() {
        $this->validateSession(array(1, 4, 5));
        $vehicle_id = 0;
        $from_date_ = date('d M Y');
        $to_date_ = date('d M Y');
		
        if (empty($_POST)) {
			$date = date('Y-m-d');
            $date = strtotime($date . ' 1 days');
            $roster_list2 = $this->roster_model->upcomingRoster(date('Y-m-d', $date));
			$roster_list=array();
			foreach($roster_list2 as $key=>$val)
			{
				if($val->date==date('Y-m-d') && $val->pickupdrop=='Drop')
				{
					$roster_list[]=$val;
				}
				
				if($val->date==date('Y-m-d', $date) && $val->pickupdrop=='Pickup')
				{
					$roster_list[]=$val;
				}
				
			}
        } else {
            $from_date_ = $_POST['from_date'];
            $to_date_ = $_POST['to_date'];
            $from_date = date('Y-m-d', strtotime($_POST['from_date']));
            $to_date = date('Y-m-d', strtotime($_POST['to_date']));
            $roster_list = $this->roster_model->dateRoster($from_date, $to_date);
        }

        $int = 0;
        foreach ($roster_list as $item) {
            $link = $this->encrypt->encode($item->roster_id);
            $roster_list[$int]->link = $link;
            $int++;
        }

        $data['title'] = 'Roster List';
        $data['from_date'] = $from_date_;
        $data['to_date'] = $to_date_;
        $data['list'] = $roster_list;
        $data['user_type'] = $this->user_type;
        return view('roster.list', $data);
    }

    public function notificationdetail() {
        $this->validateSession(array(1, 4, 5));
        $vehicle_id = 0;
        $from_date_ = date('d M Y');
        $roster_list = array();
        if (empty($_POST)) {
            $date = date('Y-m-d');
        } else {
            $from_date_ = $_POST['from_date'];
            $date = date('Y-m-d', strtotime($_POST['from_date']));
            $roster_list2 = $this->roster_model->rosteremployees($date);
			$int = 0;
		$roster_list=array();
        foreach ($roster_list2 as $item) {
            $sms_status = 'Not found';
            if ($item->job_id != '') {
                $sms_status = $this->sms_status($item->job_id);
				$item->sms_status=$sms_status;
				$roster_list[]=$item;
            }else{
			}
            $int++;
        }
        }
        $data['title'] = 'Roster List';
        $data['from_date'] = $from_date_;
        $data['list'] = $roster_list;
        $data['user_type'] = $this->user_type;
        return view('roster.emplist', $data);
    }

    public function rosterdetail() {
        $this->validateSession(array(1, 4, 5));
		$date = strtotime(date('Y-m-d') . ' 1 days');
        $roster_list = $this->roster_model->dateRoster(date('Y-m-d', $date), date('Y-m-d', $date));
		
        $int = 1;
		$string='';
        $string.= 'Morning pickup detail\n';
        foreach ($roster_list as $item) {
			if($item->pickupdrop=='Pickup')
			{
            $link = $this->encrypt->encode($item->roster_id);
            $string.= 'Route No ' . $item->route_id . '\n';
            $v_det = $this->master_model->getMasterDetail('vehicle', 'vehicle_id', $item->vehicle_id);
            $string.= 'Cab No - ' . $v_det->number . '\n';
            $d_det = $this->master_model->getMasterDetail('driver', 'driver_id', $item->driver_id);
            $string.= 'Driver - ' . $d_det->name . '\n';
            $string.= 'Contact - ' . $d_det->mobile . '\n';
            $long_url = 'http://gurgaon.svktrv.in/roster/trip/admin/' . $link;
            $short_url = $this->master_model->getShortUrl($long_url);
            $string.= 'Trip detail - ' . $short_url . '\n\n';
            $int++;
			}
        }
		//echo $string;
		echo '<a onclick="clicka();" data-link="" class="whatsapp" style="cursor: pointer;"><img src="http://gurgaon.svktrv.in/uploads/logo/whatsapp.jpg"></a>';
		echo '<script>var isMobile = {
                Android: function() {
                    return navigator.userAgent.match(/Android/i);
                },
                BlackBerry: function() {
                    return navigator.userAgent.match(/BlackBerry/i);
                },
                iOS: function() {
                    return navigator.userAgent.match(/iPhone|iPad|iPod/i);
                },
                Opera: function() {
                    return navigator.userAgent.match(/Opera Mini/i);
                },
                Windows: function() {
                    return navigator.userAgent.match(/IEMobile/i);
                },
                any: function() {
                    return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
                }
                };
                function clicka(){
                    if( isMobile.any() ) {

                        var text = "'.$string.'";
                        var message = encodeURIComponent(text);
                        var whatsapp_url = "whatsapp://send?text=" + message;
                        window.location.href = whatsapp_url;
                    } else {
                        alert("Please share this in mobile device");
                    }

                }</script>';
    }

    public function updateroster($link) {
        $this->validateSession(array(1, 4, 5));
        $roster_id = $this->encrypt->decode($link);
        $this->employee_model = new Employee();
        $mis_employee = $this->employee_model->getPassengers();
        $route = $this->master_model->getMaster('route', 0);

        $roster_employee = $this->master_model->getList('roster_employee', 'roster_id', $roster_id);
        foreach ($roster_employee as $key => $a) {
            $roster_employee[$key]->time = date('H:i A', strtotime($a->pickup_time));
        }
        $roster_det = $this->master_model->getMasterDetail('roster', 'roster_id', $roster_id);
        $roster_det->date = date('d-m-Y', strtotime($roster_det->date));
        $data['title'] = 'Roster';
        $data['current_date'] = date('d-m-Y');
        $data['roster_employee'] = $roster_employee;
        $data['mis_employee'] = $mis_employee;
        $data['roster_det'] = $roster_det;
        $data['route_list'] = $route;
        $data['user_type'] = $this->user_type;
        return view('roster.update', $data);
    }

    public function resendsms($id) {
        $roster_det = $this->master_model->getMasterDetail('roster_employee', 'id', $id);
        $emp_det = $this->master_model->getMasterDetail('passenger', 'id', $roster_det->employee_id);
        $job_id = $this->sms_send($emp_det->mobile, $roster_det->sms);
        $this->roster_model->updateRosterSMS($job_id, $roster_det->sms, $id);
        $this->setSuccess('Notification sent successfully');
        header('Location: /admin/roster/list');
        exit;
    }

    public function sendnotification($roster_link) {
        $this->validateSession(array(1, 4, 5));
        $roster_id = $this->encrypt->decode($roster_link);
        $roster_det = $this->master_model->getMasterDetail('roster', 'roster_id', $roster_id);
        $driver_det = $this->master_model->getMasterDetail('driver', 'driver_id', $roster_det->driver_id);
        $roster_employee = $this->roster_model->rosterEmployee($roster_id);
        foreach ($roster_employee as $key => $a) {
            $pickup_time = date('h:i A', strtotime($a->pickup_time));
            $link = $this->encrypt->encode($a->id);
            $long_url = 'http://gurgaon.svktrv.in/roster/trip/employee/' . $link;
            $short_url = $this->master_model->getShortUrl($long_url);
            if ($roster_det->pickupdrop == 'Pickup') {
                $sms = "Dear Employee Cab assigned for Pickup on " . date('d M Y', strtotime($roster_det->date)) . ' Please reach at your pickup point at ' . $pickup_time . ' Trip details ' . $short_url;
            } else {
                $sms = "Dear Employee Cab assigned for Drop on " . date('d M Y', strtotime($roster_det->date)) . ' Please reach at your pickup point at ' . $pickup_time . ' Trip details ' . $short_url;
            }
           // $job_id = $this->sms_send($a->mobile, $sms);
          //  $this->roster_model->updateRosterSMS($job_id, $sms, $a->id);
        }
        $long_url = 'http://gurgaon.svktrv.in/roster/trip/driver/' . $roster_link;
        $short_url = $this->master_model->getShortUrl($long_url);
        if ($roster_det->pickupdrop == 'Pickup') {
            $sms = "Dear Pilot Trip assign for Pickup on " . date('d M Y', strtotime($roster_det->date)) . ' Trip details ' . $short_url;
        } else {
            $sms = "Dear Pilot Trip assign for Drop on " . date('d M Y', strtotime($roster_det->date)) . ' Trip details ' . $short_url;
        }

        $this->sms_send('9730946150', $sms);
        $this->setSuccess('Notification sent successfully');
        header('Location: /admin/roster/list');
        exit;
    }

    public function asigncab() {
        $this->validateSession(array(1, 4, 5));
        $this->employee_model = new Employee();
        $driver_list = $this->master_model->getMaster('driver', 0);
        $vehicle_list = $this->master_model->getMaster('vehicle', 0);

        $vehicle_id = 0;
        $from_date_ = date('d M Y');
        $roster_list = array();
        if (empty($_POST)) {
            $date = date('Y-m-d');
            $date = strtotime($date . ' 1 days');
            $roster_list = $this->roster_model->getRoster(date('Y-m-d', $date));
        } else {
            $from_date_ = $_POST['from_date'];
            $date = $from_date_;
            $roster_list = $this->roster_model->getRoster(date('Y-m-d', $date));
        }

        $data['roster_list'] = $roster_list;

        $data['title'] = 'Assign Roster';
        $data['from_date'] = date('d-m-Y', $date);
        $data['driver_list'] = $driver_list;
        $data['vehicle_list'] = $vehicle_list;
        $data['user_type'] = $this->user_type;
        return view('roster.asigncab', $data);
    }

    public function createmis($link) {
        $this->validateSession(array(1, 4, 5));
        $roster_id = $this->encrypt->decode($link);
        $this->employee_model = new Employee();
        $mis_employee = $this->employee_model->getPassengers();
        $route = $this->master_model->getMaster('route', 0);
        $driver_list = $this->master_model->getMaster('driver', 0);
        $vehicle_list = $this->master_model->getMaster('vehicle', 0);
        $packageroute = $this->master_model->getMaster('packageroute', 0);

        $roster_employee = $this->roster_model->rosterEmployee($roster_id);
        foreach ($roster_employee as $key => $a) {
            $roster_employee[$key]->time = date('H:i A', strtotime($a->pickup_time));
        }
        $roster_det = $this->master_model->getMasterDetail('roster', 'roster_id', $roster_id);
        $routedet = $this->master_model->getMasterDetail('route', 'route_id', $roster_det->route_id);
        $roster_det->date = date('d-M-Y', strtotime($roster_det->date));
        $data['title'] = 'Roster';
        $data['route_name'] = $routedet->name;
        $data['current_date'] = date('d-m-Y');
        $data['roster_employee'] = $roster_employee;
        $data['mis_employee'] = $mis_employee;
        $data['roster_det'] = $roster_det;
        $data['route_list'] = $route;
        $data['driver_list'] = $driver_list;
        $data['vehicle_list'] = $vehicle_list;
        $data['packageroute'] = $packageroute;
        $data['route_list'] = $route;
        $data['user_type'] = $this->user_type;
        return view('roster.asigncab', $data);
    }

    public function deleteroster($link) {
        $id = $this->encrypt->decode($link);
        $this->master_model->deleteReccord('roster', 'roster_id', $id, $this->user_id);
        $this->setSuccess('Roster has been deleted successfully');
        header('Location: /admin/roster/list');
        exit;
    }
    public function closeroster($link) {
        $id = $this->encrypt->decode($link);
        $this->roster_model->closeReccord($id, $this->user_id);
        $this->setSuccess('Roster has been Closed successfully');
        header('Location: /admin/roster/list');
        exit;
    }

    public function exportExcel($column, $name) {
        try {
            Excel::create($name, function($excel) use($column) {
                $excel->sheet('Sheet 1', function($sheet) use($column) {
                    $sheet->fromArray($column);
                    if (!empty($column)) {
                        $sheet->row(1, function($row) {
                            // call cell manipulation methods
                            $row->setBackground('#2874A6');
                            $row->setFontColor('#ffffff');
                        });
                    }
                    $sheet->freezeFirstRow();
                    $sheet->setAutoSize(true);
                });
            })->export('xlsx');
        } catch (Exception $e) {
            
        }
    }

    function updatekm($from_date, $to_date) {
        $this->mis_model->refreshMISKM($from_date, $to_date);
        $mislist = $this->mis_model->getALLMISLIST($from_date, $to_date);
        foreach ($mislist as $mis) {
            $km_det = $this->mis_model->getMaxKm(explode(',', $mis->location));
            $month = $date = date('m', strtotime($mis->date));
            $year = $date = date('Y', strtotime($mis->date));
            $startkm_det = $this->mis_model->getStartKm($month, $year, $mis->vehicle_id);
            if ($startkm_det->km > 0) {
                $start_km = $startkm_det->km;
            } else {
                $start_km = 0;
            }
            $max_km = $km_det->km;
            $end_km = $max_km + $start_km;
            $start_km = ($start_km > 0) ? $start_km : 0;
            $this->mis_model->updateMISKM($mis->id, $start_km, $end_km, $max_km);
        }
    }

}
