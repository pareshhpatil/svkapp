<?php

use Illuminate\Support\Carbon;

/**
 * @author Paresh
 * Class made for generic function use for all controller 
 */
class Generic
{

    protected $session = NULL;
    protected $encrypt = NULL;

    public function __construct()
    {
        $this->session = new SessionLegacy();
        $this->encrypt = new Encryption();
    }

    #

    function getEncryptedList($list, $encrypt_id, $key)
    {
        $int = 0;
        foreach ($list as $item) {
            $list[$int][$encrypt_id] = $this->encrypt->encode($item[$key]);
            $int++;
        }
        return $list;
    }

    function getMoneyFormatList($list, $key, $val)
    {
        $int = 0;
        foreach ($list as $item) {
            $list[$int][$key] = $this->moneyFormatIndia($item[$val]);
            $int++;
        }
        return $list;
    }

    function getDateFormatList($list, $key, $val)
    {
        $int = 0;
        foreach ($list as $item) {
            $list[$int][$key] = $this->formatTimeString($item[$val]);
            $int++;
        }
        return $list;
    }

    function setEmptyArray($array)
    {
        foreach ($array as $key) {
            if (!isset($_POST[$key])) {
                $_POST[$key] = array();
            }
        }
    }

    function setZeroValue($array)
    {
        foreach ($array as $key) {
            if (!isset($_POST[$key])) {
                $_POST[$key] = 0;
            }
            if ($_POST[$key] > 0) {
            } else {
                $_POST[$key] = 0;
            }
        }
    }

    function setArrayZeroValue($array)
    {
        foreach ($array as $key) {
            foreach ($_POST[$key] as $k => $row) {
                if (!isset($_POST[$key][$k])) {
                    $_POST[$key][$k] = 0;
                }
                if ($_POST[$key][$k] > 0) {
                } else {
                    $_POST[$key][$k] = 0;
                }
            }
        }
    }

    function setEmptyValue($array)
    {
        foreach ($array as $key) {
            if (!isset($_POST[$key])) {
                $_POST[$key] = '';
            }
        }
    }

    function convertSQLDate($array)
    {
        foreach ($array as $key) {
            $filterarray = array();
            foreach ($_POST[$key] as $postarray) {
                $date = new DateTime($postarray);
                $filterarray[] = $date->format('Y-m-d');
            }
            $_POST[$key] = $filterarray;
        }
    }

    function convertSQLDateTime($array)
    {
        foreach ($array as $key) {
            $filterarray = array();
            foreach ($_POST[$key] as $postarray) {
                $date = new DateTime($postarray);
                $filterarray[] = $date->format('Y-m-d H:i:s');
            }
            $_POST[$key] = $filterarray;
        }
    }


    function sqlDate($date)
    {
        if ($date != '') {
            $date = new DateTime($date);
            return $date->format('Y-m-d');
        } else {
            return '';
        }
    }

    function htmlDate($date)
    {
        if ($date != '') {
            $date = new DateTime($date);
            return $date->format('d M Y');
        } else {
            return '';
        }
    }

    function setDates()
    {
        if (isset($_POST['from_date'])) {
            $from_date = $_POST['from_date'];
            $to_date = $_POST['to_date'];
        } else {
            $from_date = date('01 M Y');
            $to_date = date('d M Y');
        }
        $array['from_date'] = $from_date;
        $array['to_date'] = $to_date;
        return $array;
    }

    function mobileCode($data)
    {
        if (strlen($data) == 10) {
            $mobile = $data;
            $code = '+91';
        } else {
            $mobile = substr($data, -10);
            $code = substr($data, 0, strlen($data) - 10);
            if (substr($code, 0, 1) != '+') {
                $code = '+' . $code;
            }
        }
        $array['mobile'] = $mobile;
        $array['code'] = $code;
        return $array;
    }

    function getLastName($name)
    {
        $name = trim($name);
        $space_position = strpos($name, ' ');
        if ($space_position > 0) {
            $data['first_name'] = substr($name, 0, $space_position);
            $data['last_name'] = substr($name, $space_position);
        } else {
            $data['first_name'] = $name;
            $data['last_name'] = '';
        }
        return $data;
    }

    function formatTimeString($timeStamp)
    {
        $default_timezone = $this->session->get('default_timezone');
        $default_time_format = $this->session->get('default_time_format');
        $default_date_format = $this->session->get('default_date_format');
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
        $default_timezone = $this->session->get('default_timezone');
        $default_date_format = $this->session->get('default_date_format');
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

    public static function APIrequest($url, $post_string, $method = "POST", $token = null)
    {
        if (isset($token)) {
            $api_url = env('SWIPEZ_API_URL') . $url;
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $api_url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => $method,
                CURLOPT_POSTFIELDS => $post_string,
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer " . $token
                ),
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            echo  $err;
            echo $response;
            curl_close($curl);
            return $response;
        } else {
            $response['status'] = 0;
            return $response;
        }
    }

    function uploadFile($upload_file, $path, $file_name, $folder = 'uploads', $bucket_env = 'FORM_BUILDER_BUCKET')
    {
        try {
            if ($upload_file['error'] == 0) {
                $bucket = getenv($bucket_env);
                require_once UTIL . 'SiteBuilderS3Bucket.php';
                $aws = new SiteBuilderS3Bucket('ap-south-1');
                $file = $upload_file['name'];
                $ext = substr($file, strrpos($file, '.') + 1);
                $keyname = $folder . '/' . $path . '/' . $file_name . '.' . $ext;
                $fileurl = $aws->putBucket($bucket, $keyname, $upload_file['tmp_name'], $ext);
                return $fileurl;
            }
        } catch (Exception $e) {
            Sentry\captureException($e);

            SwipezLogger::error(__CLASS__, 'Error while uploading file to S3 Path:' . $path . $e->getMessage());
        }
    }
}
