<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Session;
use App\Lib\Encryption;
use View;

class Controller extends BaseController
{

    public $display_name = '';
    public $user_id = '';
    public $employee_id = '';
    public $admin_id = '';
    public $user_type = '';
    public $login_type = '';
    public $user_role = '';
    public $session = '';
    public $encrypt = '';
    public $from_date = '';
    public $to_date = '';

    use AuthorizesRequests,
        DispatchesJobs,
        ValidatesRequests;

    function __construct()
    {
        $this->encrypt = new Encryption();
    }

    public function validateSession($type)
    {
        @session_start();
        foreach ($_SESSION as $key => $value) {
            Session::put($key, $value);
        }
        Session::save();
        if (Session::get('logged_in') != 1) {
            header('location: /login');
            exit;
        } else {

            if (Session::get('success_message')) {
                View::share('success_message', Session::get('success_message'));
                unset($_SESSION['success_message']);
                CustomSession::remove('success_message');
            }

            if (in_array(Session::get('user_type'), $type)) {
                $this->display_name = Session::get('user_name');
                $this->admin_id = Session::get('admin_id');
                $this->company_name = Session::get('company_name');
                View::share('company_name', Session::get('company_name'));
                View::share('company_logo', Session::get('logo'));
                $this->user_id = Session::get('user_id');
                $this->user_type = Session::get('user_type');
                $this->employee_id = Session::get('employee_id');
                if ($this->user_type == 1) {
                    $type = 'admin';
                } elseif ($this->user_type == 2) {
                    $type = 'employee';
                } elseif ($this->user_type == 3) {
                    $type = 'client';
                } elseif ($this->user_type == 4) {
                    $type = 'vendor';
                } elseif ($this->user_type == 7) {
                    $type = 'company';
                }
                View::share('login_type', $type);
                View::share('current_date', date('d-m-Y'));
            } else {
                header('location: /login/accessdenied');
                exit;
            }
        }
    }

    public function setSuccess($message)
    {
        @session_start();
        $_SESSION['success_message'] = $message;
        CustomSession::set('success_message', $message);
    }

    public function setError($title, $message)
    {
        $_SESSION['error_title'] = $title;
        $_SESSION['error_message'] = $message;
        return redirect('/error');
        exit();
    }

    public function validateID($id)
    {
        if (is_numeric($id)) {
            return TRUE;
        } else {
            $_SESSION['error_title'] = 'Error';
            $_SESSION['error_message'] = 'Invalid Link';
            header("Location: /error");
        }
        exit();
    }

    public function encryptedLink($list)
    {
        foreach ($list as $item) {
        }
    }

    public function setGenericError()
    {
        $_SESSION['error_title'] = 'Error';
        $_SESSION['error_message'] = 'Error in Global system for this process please try again later.';
        header("Location: /error");
        exit();
    }

    function moneyFormatIndia($num)
    {
        $num = str_replace(',', '', $num);
        $explrestunits = "";
        $numdecimal = "";
        if (substr($num, -3, 1) == '.') {
            $numdecimal = substr($num, -3);
            $num = str_replace($numdecimal, '', $num);
        }
        if (strlen($num) > 3) {
            $lastthree = substr($num, strlen($num) - 3, strlen($num));
            $restunits = substr($num, 0, strlen($num) - 3); // extracts the last three digits
            $restunits = (strlen($restunits) % 2 == 1) ? "0" . $restunits : $restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
            $expunit = str_split($restunits, 2);
            for ($i = 0; $i < sizeof($expunit); $i++) {
                // creates each of the 2's group and adds a comma to the end
                if ($i == 0) {
                    $explrestunits .= (int) $expunit[$i] . ","; // if is first value , convert into integer
                } else {
                    $explrestunits .= $expunit[$i] . ",";
                }
            }
            $thecash = $explrestunits . $lastthree;
        } else {
            $thecash = $num;
        }
        return $thecash . $numdecimal; // writes the final format where $currency is the currency symbol.
    }

    public function session_expire()
    {
        @session_start();
        if (isset($_SESSION['logged_in'])) {
            $expireAfter = 15;
            if (isset($_SESSION['last_action'])) {
                $secondsInactive = time() - $_SESSION['last_action'];
                $expireAfterSeconds = $expireAfter * 60;
                if ($secondsInactive >= $expireAfterSeconds) {
                    header('location: /logout');
                    exit;
                }
            }
            $_SESSION['last_action'] = time();
        }
    }

    function sms_send($phone, $message)
    {
        $message = str_replace(" ", "%20", $message);
        $message = str_replace("&", "%26", $message);
        $message = preg_replace("/\r|\n/", "%0a", $message);
        $invokeURL = 'http://sms.indiatext.in/api/mt/SendSMS?user=appsofty40&password=app@123&senderid=SVKTRV&channel=Trans&DCS=0&flashsms=0&number=' . $phone . '&text=' . $message . '&route=01';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $invokeURL); // here the request is sent to payment gateway 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        return true;
    }

    function displaywords($number)
    {
        $no = (int) floor($number);
        $point = (int) round(($number - $no) * 100);
        $hundred = null;
        $digits_1 = strlen($no);
        $i = 0;
        $str = array();
        $words = array(
            '0' => '', '1' => 'one', '2' => 'two',
            '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
            '7' => 'seven', '8' => 'eight', '9' => 'nine',
            '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
            '13' => 'thirteen', '14' => 'fourteen',
            '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
            '18' => 'eighteen', '19' => 'nineteen', '20' => 'twenty',
            '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
            '60' => 'sixty', '70' => 'seventy',
            '80' => 'eighty', '90' => 'ninety'
        );
        $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
        while ($i < $digits_1) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += ($divider == 10) ? 1 : 2;


            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str[] = ($number < 21) ? $words[$number] .
                    " " . $digits[$counter] . $plural . " " . $hundred :
                    $words[floor($number / 10) * 10]
                    . " " . $words[$number % 10] . " "
                    . $digits[$counter] . $plural . " " . $hundred;
            } else
                $str[] = null;
        }
        $str = array_reverse($str);
        $result = implode('', $str);


        if ($point > 20) {
            $points = ($point) ?
                "" . $words[floor($point / 10) * 10] . " " .
                $words[$point = $point % 10] : '';
        } else {
            $points = $words[$point];
        }
        if ($points != '') {
            return ucfirst($result) . "Rupees  " . $points . " Paise Only";
        } else {

            return ucfirst($result) . "Rupees Only";
        }
    }

    public function setFilterDates()
    {
        $from_date_ = date('1 M Y');
        $to_date_ = date('d M Y');
        if (isset($_POST['from_date'])) {
            $this->from_date = date('Y-m-d', strtotime($_POST['from_date']));
            $this->to_date = date('Y-m-d', strtotime($_POST['to_date']));
            View::share('from_date', $_POST['from_date']);
            View::share('to_date', $_POST['to_date']);
        } else {
            $this->from_date = date('Y-m-d', strtotime($from_date_));
            $this->to_date = date('Y-m-d', strtotime($to_date_));
            View::share('from_date', $from_date_);
            View::share('to_date', $to_date_);
        }
    }
}
