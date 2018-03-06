<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Session;
use App\Lib\Encryption;
use Numbers_Words;
use View;

class Controller extends BaseController {

    public $display_name = '';
    public $user_id = '';
    public $admin_id = '';
    public $user_type = '';
    public $login_type = '';
    public $user_role = '';
    public $session = '';
    public $encrypt = '';

    use AuthorizesRequests,
        DispatchesJobs,
        ValidatesRequests;

    function __construct() {
        $this->encrypt = new Encryption();
    }

    public function validateSession($type) {
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

            if (Session::get('user_type') == $type) {
                $this->display_name = Session::get('user_name');
                $this->admin_id = Session::get('admin_id');
                $this->company_name = Session::get('company_name');
                View::share('company_name', Session::get('company_name'));
                View::share('company_logo', Session::get('logo'));
                $this->user_id = Session::get('user_id');
                View::share('current_date', date('d-m-Y'));
            } else {
                header('location: /login/accessdenied');
                exit;
            }
        }
    }

    public function setSuccess($message) {
        @session_start();
        $_SESSION['success_message'] = $message;
        CustomSession::set('success_message', $message);
    }

    public function setError($title, $message) {
        $_SESSION['error_title'] = $title;
        $_SESSION['error_message'] = $message;
        return redirect('/error');
        exit();
    }

    public function validateID($id) {
        if (is_numeric($id)) {
            return TRUE;
        } else {
            $_SESSION['error_title'] = 'Error';
            $_SESSION['error_message'] = 'Invalid Link';
            header("Location: /error");
        }
        exit();
    }

    public function encryptedLink($list) {
        foreach ($list as $item) {
            
        }
    }

    public function setGenericError() {
        $_SESSION['error_title'] = 'Error';
        $_SESSION['error_message'] = 'Error in Global system for this process please try again later.';
        header("Location: /error");
        exit();
    }

    function moneyFormatIndia($num) {
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

    public function session_expire() {
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

    public function wordMoney($amount) {
        $numb = new Numbers_Words();
        $num_words = $numb->toCurrency($amount, "en_IN");
        $num_words1 = str_replace("Indian Rupeess", "Rupees", $num_words);
        $money_words = ucwords($num_words1);
        return str_replace('Zero Paises', '', $money_words);
    }

}
