<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class APIController extends Controller
{

    function APIResponse($error_code = '', $srvrsp = '', $errorlist = '', $total_rows = 0)
    {
        $response['reqtime'] = REQ_TIME;
        $response['resptime'] = date("Y-m-d H:i:s");
        if ($total_rows == 1) {
            $response['total_records'] = count($srvrsp);
        }
        $response['srvrsp'] = $srvrsp;
        $response['errcode'] = $error_code;
        $errorMessage = ($error_code != '') ? $this->fetchError($error_code) : '';
        $response['errmsg'] = $errorMessage;
        $response['errlist'] = $errorlist;

        return response()->json($response);
    }


    function fetchError($error_code)
    {
        $errors["ER01001"] = "Invalid json request format.";
        $errors["ER01002"] = "Invalid request URL.";
        $errors["ER01003"] = "Invalid API key.";
        $errors["ER01004"] = "Access denied to API services.";
        $errors["ER01005"] = "Invalid template id.";
        $errors["ER01006"] = "Invalid request method.";
        $errors["ER01007"] = "Invoice upload failed.";

        $errors["ER01008"] = "Invalid request id.";
        $errors["ER01009"] = "Invoice already paid online.";
        $errors["ER01010"] = "Invalid payment mode.";
        $errors["ER01011"] = "Json format not match.";

        $errors["ER02001"] = "Id field not recognized. Do not change id field.";
        $errors["ER02002"] = "Date should be formatted in YYYY-MM-DD format (ex. 2015-12-24).";
        $errors["ER02003"] = "Mobile number should be only 10 digits.";
        $errors["ER02004"] = "Incorrectly formatted email id.";
        $errors["ER02005"] = "Name can not be empty.";
        $errors["ER02006"] = "Amount should contain only numeric values.";
        $errors["ER02007"] = "Input should be digits.";
        $errors["ER02008"] = "Text limit exceeds.";
        $errors["ER02009"] = "From date should be greater than To date";
        $errors["ER02010"] = "Difference between from-date and to-date not more than 31 days";
        $errors["ER02011"] = "Customer code does not exist.";

        $errors["ER02012"] = "Due date should not be less than current date.";
        $errors["ER02013"] = "Expiry date should not be less than due date.";
        $errors["ER02014"] = "Field can not be empty.";
        $errors["ER02015"] = "Invalid content please enter valid value.";


        $errors["ER02016"] = "Invalid coupon ID.";
        $errors["ER02017"] = "Invalid coupon type.";
        $errors["ER02018"] = "Invalid coupon code or already exist.";
        $errors["ER02019"] = "Invalid coupon value.";

        $errors["ER02020"] = "Invalid transaction type.";
        $errors["ER02021"] = "transaction does not exist";

        $errors["ER02030"] = "Api upload failed.";
        $errors["ER02031"] = "Customer code not valid or already exist";

        $errors["ER02032"] = "Package invoice limit has been exceeded. Please upgrade your package.";
        $errors["ER02033"] = "Invalid franchise_id or does not exist.";
        $errors["ER02035"] = "Invalid vendor_id or does not exist.";
        $errors["ER02034"] = "Invalid customer ID.";
        $errors["ER02035"] = "Invoice number not valid or already exist";
        $errors["ER02036"] = "Invalid mobile number";
        $errors["ER02037"] = "Sms pack is expired";
        $errors["ER02038"] = "Refund failed. Please contact to swipez Support";
        $errors["ER02039"] = "Login has been disabled. It will be enabled once user have reset password.";
        $errors["ER02040"] = "User has not yet been verified. Please verify email before logging in.";
        $errors["ER02041"] = "Invalid Username/Password combination. Please try again.";
        $errors["ER02042"] = "Invalid partial amount";

        return $errors[$error_code];
    }
}
