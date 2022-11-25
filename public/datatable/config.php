<?php

use Illuminate\Support\Carbon;

ini_set('error_reporting', 0);
@session_start();
date_default_timezone_set("Asia/Kolkata");
header('Content-Type: application/json');
$env = getenv('ENV');
if ($env != 'LOCAL') {
    if (isset($_SERVER['HTTP_CDN_LOOP'])) {
        if ($_SERVER['HTTP_CDN_LOOP'] != 'cloudflare') {
            exit(json_encode(['error' => 'Invalid request']));
        }
    } else {
        exit(json_encode(['error' => 'Invalid request']));
    }
    if (isset($_SERVER['HTTP_REFERER'])) {
        $url_array = parse_url($_SERVER['HTTP_REFERER']);
        $domain = $url_array['host'];
        $address = $_SERVER['SERVER_NAME'];
        if (strpos($domain, $address) == false && $domain != $address) {
            exit(json_encode([
                'error' => 'Invalid request'
            ]));
        }
    } else {
        exit(json_encode(['error' => 'Invalid request']));
    }

    if (isset($_POST['csrf_token'])) {
        if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            exit(json_encode(['error' => 'Invalid request']));
        }
    } else {
        exit(json_encode(['error' => 'Invalid request']));
    }
}

if ($_SESSION['user_status'] < 12) {
    exit(json_encode(['error' => 'Invalid request']));
}

function formatTimeString($timeStamp)
{

    // $str_time = date("Y-m-d H:i:s", $timeStamp);
    //return $str_time;
    $time = strtotime($timeStamp);
    $d = new DateTime($timeStamp);

    $weekDays = ['Mon', 'Tue', 'Wed', 'Thur', 'Fri', 'Sat', 'Sun'];
    $months = ['Jan', 'Feb', 'Mar', 'Apr', ' May', 'Jun', 'Jul', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'];

    if ($time > strtotime('-2 minutes')) {
        return 'Just now';
    } elseif ($time > strtotime('-59 minutes')) {
        $min_diff = floor((strtotime('now') - $time) / 60);
        return $min_diff . ' min' . (($min_diff != 1) ? "s" : "") . ' ago';
    } elseif ($time > strtotime('-23 hours')) {
        $hour_diff = floor((strtotime('now') - $time) / (60 * 60));
        return $hour_diff . ' hour' . (($hour_diff != 1) ? "s" : "") . ' ago';
    } elseif ($time > strtotime('today')) {
        return $d->format('G:i');
    } elseif ($time > strtotime('yesterday')) {
        return 'Yesterday at ' . $d->format('G:i');
    } elseif ($time > strtotime('this week')) {
        return $weekDays[$d->format('N') - 1] . ' at ' . $d->format('G:i');
    } else {
        return $d->format('j') . ' ' . $months[$d->format('n') - 1] .
            (($d->format('Y') != date("Y")) ? $d->format(' Y') : "") .
            ' at ' . $d->format('G:i');
    }
}

function formatTimeString2($timeStamp)
{

    $default_timezone = $_SESSION['default_timezone'];
    $default_time_format = $_SESSION['default_time_format'];
    $default_date_format = $_SESSION['default_date_format'];
    $default_date_format =  str_replace('yyyy', 'Y', $default_date_format);

    if ($default_time_format == '24') {
        $time_format = 'G:i';
    } else {
        $time_format = 'g:i A';
    }

    $time = strtotime($timeStamp);
    $timeStamp = Carbon::parse($timeStamp)->format('Y-m-d H:i:s');
    $timeStamp = Carbon::createFromFormat('Y-m-d H:i:s',  $timeStamp, 'UTC');
    $time_formatted = $timeStamp->setTimezone($default_timezone)->format($time_format);
    $timeStamp = $timeStamp->setTimezone($default_timezone);

    $weekDays = ['Mon', 'Tue', 'Wed', 'Thur', 'Fri', 'Sat', 'Sun'];
    $months = ['Jan', 'Feb', 'Mar', 'Apr', ' May', 'Jun', 'Jul', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'];

    if ($time > strtotime('-2 minutes')) {
        return 'Just now';
    } elseif ($time > strtotime('-59 minutes')) {
        $min_diff = floor((strtotime('now') - $time) / 60);
        return $min_diff . ' min' . (($min_diff != 1) ? "s" : "") . ' ago';
    } elseif ($time > strtotime('-23 hours')) {
        $hour_diff = floor((strtotime('now') - $time) / (60 * 60));
        return  $hour_diff . ' hour' . (($hour_diff != 1) ? "s" : "") . ' ago';
    } elseif ($time > strtotime('today')) {
        return $time_formatted;
    } elseif ($time > strtotime('yesterday')) {
        return  'Yesterday at ' . $time_formatted;
    } elseif ($time > strtotime('this week')) {
        return $weekDays[$timeStamp->format('N') - 1] . ' at ' . $time_formatted;
    } else {
        if ($default_date_format == 'M d Y') {
            return $months[$timeStamp->format('n') - 1]  . ' ' . $timeStamp->format('j')  .
                (($timeStamp->format('Y') != date("Y")) ? $timeStamp->format(' Y') : "") .
                ' at ' . $time_formatted;
        } else {
            return $timeStamp->format('j') . ' ' . $months[$timeStamp->format('n') - 1] .
                (($timeStamp->format('Y') != date("Y")) ? $timeStamp->format(' Y') : "") .
                ' at ' . $time_formatted;
        }
    }
}

function formatDateString($date)
{
    $default_timezone = $_SESSION['default_timezone'];
    $default_date_format = $_SESSION['default_date_format'];
    $default_date_format =  str_replace('yyyy', 'Y', $default_date_format);

    $date = Carbon::parse($date)->format($default_date_format);
    $date = Carbon::createFromFormat($default_date_format, $date, 'UTC');
    $date = $date->setTimezone($default_timezone)->format($default_date_format);

    return  $date;
}

function moneyFormatIndia($num)
{
    $num = str_replace(',', '', $num);
    $explrestunits = "";
    $numdecimal = "";
    $num = $num + 0;
    if (substr($num, -2, 1) == '.') {
        $num = $num . '0';
    }
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

if ($_SESSION['has_error'] == true) {
    exit('{"draw":1,"totalSum":0,"recordsTotal":0,"recordsFiltered":0,"data":[]}');
}
define('LIB', '../../legacy_app/lib/');
require_once "../../legacy_app/util/ConfigReader.php";
require '../../vendor/autoload.php';
require '../../legacy_app/util/Secretkey.php';
require_once "../../legacy_app/util/Encrypt.php";

$enc = new Encryption();
$merchant_id = $enc->decode($_SESSION['merchant_id']);
$user_id = $enc->decode($_SESSION['userid']);
