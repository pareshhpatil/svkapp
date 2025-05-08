<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\WebhookController;
use Illuminate\Http\Request;
use App\Http\Lib\Encryption;
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $user_id = null;

    public function sqlDate($date, $format = 'Y-m-d')
    {
        return date($format, strtotime($date));
    }
    public function sqlTime($time)
    {
        return date('H:i:s', strtotime($time));
    }
    public function htmlTime($time)
    {
        if ($time != '') {
            return date('h:i:A', strtotime($time));
        }
    }
    public function htmlDate($time)
    {
        $w = substr(date("l", strtotime($time)), 0, 3);
        if ($time != '') {
            return $w . date(' d M Y', strtotime($time));
        }
    }

    public function htmlDateTime($time)
    {
        $w = substr(date("l", strtotime($time)), 0, 3);
        if ($time != '') {
            return $w . date(' d M y h:i:A', strtotime($time));
        }
    }

    public function htmlShortDateTime($time)
    {
        if ($time != '') {
            return  date('d M h:i:A', strtotime($time));
        }
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

    function notifyAdmin($body)
    {
        $WebhookController = new WebhookController();
        $json = '{"object":"whatsapp_business_account","entry":[{"id":"351936407998643","changes":[{"value":{"messaging_product":"whatsapp","metadata":{"display_phone_number":"918879643150","phone_number_id":"350618571465341"},"contacts":[{"profile":{"name":"Contact us"},"wa_id":"919423300297"}],"messages":[{"from":"919423300297","id":"' . rand(10000, 99999) . '","timestamp":"1718544192","text":{"body":"' . $body . '"},"type":"text"}]},"field":"messages"}]}]}';
        $request = new Request(json_decode($json, 1));
        $WebhookController->facebookWebhook($request);
    }


    public function EncryptList($array, $single = 0, $link = '/passenger/ride/', $key = 'pid')
    {
        if (!empty($array)) {
            if ($single == 0) {
                foreach ($array as $k => $v) {
                    $array[$k]['link'] = $link . Encryption::encode($v[$key]);
                }
            } else {

                $array['link'] = $link . Encryption::encode($array[$key]);
            }
        }
        return $array;
    }
}
