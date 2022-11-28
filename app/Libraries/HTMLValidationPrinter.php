<?php

namespace App\Libraries;

/*
 * Stores list of HTML pattern validaton of all forms. this validations are called into the files from here.
 */

/**
 * Description of HTMLValidationPrinter
 *
 * @author Shuhaid
 */
class HTMLValidationPrinter {

    public static $_messageList = array();

    public static function fetch($key_ = null) {
        /* --- login username ---  */
        $str = <<<EOD
required aria-required="true" pattern="[A-Za-z0-9](([_\.\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([\.\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,})" title="This field is required. Please enter a valid email address " 
EOD;
        self::$_messageList["lusername"] = $str;

        /* --- login password --- */
        $str = <<<EOD
required aria-required="true" title="Enter correct password OR Select Forgot Password to reset password" required pattern="(?=.*[0-9])(?=.*[a-zA-Z]).{8,20}" maxlength="20" 
EOD;
        self::$_messageList["lpassword"] = $str;

        /* --- email addresses : registration process both merchant and patron --- */
        $str = <<<EOD
  pattern="[A-Za-z0-9](([_\.\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([\.\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,})" title="This field is required. Please enter a valid email address " maxlength="254" 
EOD;
        self::$_messageList["email"] = $str;

        /* ---    --- */
        $str = <<<EOD
required aria-required="true" title="Make a strong password of 8 characters. Enter a combination containing letters, numerics & special characters" pattern="(?=.*[0-9])(?=.*[a-zA-Z]).{8,20}" maxlength="20" 
EOD;
        self::$_messageList["password"] = $str;

        /* ---    --- */
        $str = <<<EOD
id="verifypassword" oninput="validatePass(document.getElementById('password'), this);" onfocus="validatePass(document.getElementById('password'), this);"  required aria-required="true" maxlength="20" 
EOD;
        self::$_messageList["verifypassword"] = $str;

        /* ---  fname , lname  --- */
        $str = <<<EOD
 pattern=^[a-zA-Z0-9\s]*$ maxlength=100
EOD;
        self::$_messageList["name"] = $str;

        /* ---  fname , lname  --- */
        $str = <<<EOD
pattern="^[a-zA-Z0-9\s]*$" title="Use only letters and Numbers Special characters are not allowed"  maxlength="100"
EOD;
        self::$_messageList["name_text"] = $str;

        /* ---  dropdown lists: day , month , year , template , industry type , entity type , bill date , due date , --- */
        $str = <<<EOD
required aria-required="true" 
EOD;
        self::$_messageList["dropdown"] = $str;

        /* ---  mobile code  --- */
        $str = <<<EOD
required aria-required=”true” title="This field is required. Enter  your  Country code" maxlength="3"    
pattern="([+|\0[0-9]{1,6})?([0-9]{2,3})" 
EOD;
        self::$_messageList["mobilecode"] = $str;


        /* --- mobile   --- */
        $str = <<<EOD
 pattern=^(\+[\d]{1,5}|0)?[1-9]\d{9}$
EOD;
        self::$_messageList["mobile"] = $str;
        /* --- mobile   --- */
        $str = <<<EOD
 pattern="^(0[1-9]|[1-2][0-9]|3[0-1]) [A-Za-z]{3} [0-9]{4}$" title="Select date from calendar"
EOD;
        self::$_messageList["date"] = $str;

        /* --- mobile   --- */
        $str = <<<EOD
title="Enter your valid mobile number" maxlength="12" pattern="^(\+[\d]{1,5}|0)?[1-9]\d{9}$"
EOD;
        self::$_messageList["mobile_number"] = $str;

        /* ---  std code  --- */
        $str = <<<EOD
title="Enter  your  STD  code" maxlength="6" pattern="([+|\0[0-9]{1,6})?([0-9]{2,6})"
EOD;
        self::$_messageList["landlinecode"] = $str;

        /* ---  landline  --- */
        $str = <<<EOD
title="Enter your Landline number" maxlength="12" pattern="([0-9]{0,12})"
EOD;
        self::$_messageList["landline"] = $str;

        /* ---  address line : patron  --- */
        $str = <<<EOD
required aria-required="true" title="Address too short, please put in your complete and accurate billing address. This is required for successfully processing your payments."  maxlength="500" minlength="25"
EOD;
        self::$_messageList["paddr"] = $str;

        /* ---  address line 1 :   --- */
        $str = <<<EOD
required aria-required="true" title="This field is required. Please Enter your valid address" maxlength="500" minlength="25"
EOD;
        self::$_messageList["addr1"] = $str;

        $str = <<<EOD
 maxlength=500 
EOD;
        self::$_messageList["address"] = $str;

        /* ---  address line 2 :   --- */
        $str = <<<EOD
 title="Please Enter your valid mailing address" maxlength="100"
EOD;
        self::$_messageList["addr2"] = $str;

        /* ---  city  --- */
        $str = <<<EOD
 pattern=[\sa-zA-Z]* maxlength=40
EOD;
        self::$_messageList["city"] = $str;

        /* ---  state  --- */
        $str = <<<EOD
pattern="^[a-zA-Z0-9\s]*$" title="This field is required. State name can't have numerics or special characters" maxlength="40"
EOD;
        self::$_messageList["state"] = $str;

        /* ---  country  --- */
        $str = <<<EOD
required aria-required="true" pattern="[\sa-zA-Z]*" title="This field is required. Country name can't have numerics or special characters" maxlength="25"
EOD;
        self::$_messageList["country"] = $str;

        /* --- patron zip (alphnumeric)   --- */
        $str = <<<EOD
required aria-required="true" title="This field is required. Zip code is combination of alphabets and numbers & at least 5 numbers." pattern="[\sa-zA-Z0-9]{5,10}" maxlength="10" 
EOD;
        self::$_messageList["pzipcode"] = $str;

        /* ---  capcha  --- */
        $str = <<<EOD
required aria-required="true" pattern="[\sa-z\d]{6,6}" title="This field is required. Please enter the values from the image" maxlength="6" 
EOD;
        self::$_messageList["capcha"] = $str;

        /* ---  checkbox : terms and condition  --- */
        $str = <<<EOD
required aria-required="true"
EOD;
        self::$_messageList["checkbox"] = $str;

        /* ---  form_invoice: customer-id  --- */
        $str = <<<EOD
title="Maximum Combination of 12 characters containing letters, numbers and special characters" pattern="[\s([A-Z][a-z][0-9]*)([-][A-Z][a-z][0-9]*)*\s]" maxlength="12"  

EOD;
        self::$_messageList["custid"] = $str;

        /* --- form_invoice: invoice number  --- */
        $str = <<<EOD
title="Maximum Combination of 12 characters containing letters, numbers and special characters" maxlength="12"
EOD;
        self::$_messageList["invoice_no"] = $str;

        /* ---  form_invoice: bill_period  --- */
        $str = <<<EOD
pattern="[\s([A-Z][a-z][0-9]*)([-][A-Z][a-z][0-9]*)*\s]" maxlength="20"
EOD;
        self::$_messageList["bill_period"] = $str;

        /* -- money/amount: previous_dues , previous_payment , amount fields , absolute cost , tax value , Unit price , quantity , applicable on rs. , 
          offline payment->amount field -- */
        $str = <<<EOD
title="Accepts only numeric characters. Value not exceeding ₹ 1,00,000.00" 
pattern="^-?[0-9]\d*(\.\d+)?$" maxlength="9" 
EOD;
        self::$_messageList["amount"] = $str;

        $str = <<<EOD
title="Accepts only numeric characters. Value not exceeding ₹ 1,00,000.00" 
pattern="^-?[0-9]\d*(\.\d+)?$" maxlength="9" 
EOD;
        self::$_messageList["money"] = $str;

        /* --- previous dues with minus sign --- */
        $str = <<<EOD
title="Accepts only numeric characters. Value not exceeding ₹ 1,00,000.00" 
pattern="^-?[0-9]\d*(\.\d+)?$" maxlength="9" 
EOD;
        self::$_messageList["previousamt"] = $str;


        /* --- offline respond amount --- */
        $str = <<<EOD
required aria-required="true" title="Accepts only numeric characters. Value not exceeding ₹ 1,00,000.00" pattern="^-?[0-9]\d*(\.\d+)?$" maxlength="9" 
EOD;
        self::$_messageList["offamount"] = $str;

        /* --- Quantity --- */
        $str = <<<EOD
title="Accepts only numeric characters" 
pattern="^-?[0-9]\d*(\.\d+)?$" maxlength="10" 
EOD;
        self::$_messageList["quantity"] = $str;

        /* ---  form invoice: bill cycle name  --- */
        $str = <<<EOD
required aria-required="true" title="This field is required.Enter your Bill Cycle name" pattern="[\s([A-Z][a-z][0-9]*)([-][A-Z][a-z][0-9]*)*\s]"  maxlength="40"
EOD;
        self::$_messageList["bill_cycle"] = $str;

        /* ---  Bill value   --- */
        $str = <<<EOD
title="Bill value must not exceed ₹ 1,00,000.00"   pattern="^-?[0-9]\d*(\.\d+)?$" maxlength="9" 
EOD;
        self::$_messageList["bill_value"] = $str;

        /* ---  grand total  --- */
        $str = <<<EOD
pattern="^-?[0-9]\d*(\.\d+)?$" title="Zero value invoices are not allowed.   Minimun invoice value  ₹ 20 and value not exceeding ₹ 1,00,000.00"  maxlength="9" 
EOD;
        self::$_messageList["grand_total"] = $str;


        /* ---  Particular label and tax    --- */
        $str = <<<EOD
required aria-required="true" maxlength="40" pattern="[a-zA-Z0-9]+(([\!\'\,\.\s\-\@\#\$\%\^\&\*\(\)\_\+\|\\\/\=\{\}\[\]\][a-zA-Z0-9])?[a-zA-Z0-9]*)*" title="Does not accepts ` and ~ characters"
EOD;
        self::$_messageList["particular"] = $str;

        /* ---  header label    --- */
        $str = <<<EOD
required aria-required="true" maxlength="20" pattern="[a-zA-Z0-9]+(([\!\'\,\.\s\-\@\#\$\%\^\&\*\(\)\_\+\|\\\/\=\{\}\[\]\][a-zA-Z0-9])?[a-zA-Z0-9]*)*" title="Does not accepts ` and ~ characters"
EOD;
        self::$_messageList["header"] = $str;

        /* ---  duration label    --- */
        $str = <<<EOD
 maxlength="15" pattern="[a-zA-Z0-9]+(([\!\'\,\.\s\-\@\#\$\%\^\&\*\(\)\_\+\|\\\/\=\{\}\[\]\][a-zA-Z0-9])?[a-zA-Z0-9]*)*" title="Does not accepts ` and ~ characters"
EOD;
        self::$_messageList["duration"] = $str;


        /* ---  template create and form_invoice: tax in percentage  --- */
        $str = <<<EOD
maxlength="5" pattern="^([1-9]([0-9])?|0)(\.[0-9]{1,2})?$" title="Accepts only numeric characters. Value less than 100"  
EOD;
        self::$_messageList["ptax"] = $str;

        /* ---  template create and form_invoice: tax in percentage  --- */
        $str = <<<EOD
maxlength="5" pattern="^([1-9]([0-9])?|0)(\.[0-9]{1,2})?$" title="Accepts only numeric characters. Value less than 100"  
EOD;
        self::$_messageList["percent"] = $str;

        /* --- password reset : old password  --- */
        $str = <<<EOD
required aria-required="true" title="You cannot leave this field empty. Please enter your password" pattern="(?=.*[0-9])(?=.*[a-zA-Z]).{8,20}" maxlength="20" 
EOD;
        self::$_messageList["oldpassword"] = $str;

        /* ---  entity : company name  --- */
        $str = <<<EOD
required aria-required="true" title="Maximum Combination of 50 characters containing letters, numbers and special characters" maxlength="50"
EOD;
        self::$_messageList["company_name"] = $str;

        /* ---  entity : pan number  --- */
        $str = <<<EOD
title="Enter Valid PAN Number" pattern="[A-Za-z]{5}\d{4}[A-Za-z]{1}" maxlength="10"  

EOD;
        self::$_messageList["pan"] = $str;

        /* ---  entity : resgistration number  --- */
        $str = <<<EOD
maxlength="40"  title="Maximum Combination of 40 characters containing letters, numbers and special characters"

EOD;
        self::$_messageList["resg_no"] = $str;

        /* ---  entity : resgistration number  --- */
        $str = <<<EOD
maxlength="15" pattern="\d{2}[A-Za-z]{5}\d{4}[A-Za-z]{1}\d{1}[A-Za-z]{1}[a-zA-Z0-9]{1}" title="Enter Valid GST number"

EOD;
        self::$_messageList["gst_number"] = $str;

        /* --- entity : zipcode --- */
        $str = <<<EOD
maxlength=6 pattern=[0-9]{5,6} 
EOD;
        self::$_messageList["zipcode"] = $str;

        /* ---  entity : business-contact-code  --- */
        $str = <<<EOD
required aria-required=”true” title="This field is required. Enter  your  Country code or STD code as applicable" maxlength="6" pattern="([+|\0[0-9]{1,6})?([0-9]{2,6})" 

EOD;
        self::$_messageList["busi_code"] = $str;

        /* --- entity : business contact --- */
        $str = <<<EOD
required aria-required=”true” title="Enter  your  business contact number" maxlength="12" pattern="([0-9]{10,12})"
EOD;
        self::$_messageList["busi_contact"] = $str;

        /* ---  entity : transaction money range  --- */
        $str = <<<EOD
required aria-required="true" pattern="^-?[0-9]\d*(\.\d+)?$" title="Accepts only numeric characters. Minimum value ₹ 20 and Value not exceeding ₹ 1,00,000.00" maxlength="9" 
EOD;
        self::$_messageList["amt_range"] = $str;

        /* ---  template create : template name  --- */
        $str = <<<EOD
required aria-required="true" required pattern="[\sa-zA-Z0-9]+(([\.\&\+\_\\\/\s\-\][\sa-zA-Z0-9])?[\sa-zA-Z0-9]*)*" maxlength="40" title="Maximum Combination of 40 characters containing letters, numbers and accepts special characters /-+.\&_ only"
EOD;
        self::$_messageList["temp_name"] = $str;

        /* --- respond offline : cheque   --- */
        $str = <<<EOD
 title="Accepts only numeric characters." maxlength="8" pattern="([0-9]{0,8})"
EOD;
        self::$_messageList["cheque"] = $str;

        /* --- cashpaidto , narrative ,   --- */
        $str = <<<EOD
 pattern="[a-zA-Z0-9]+(([\!\'\,\.\s\-\@\#\$\%\^\&\*\(\)\_\+\|\\\/\=\{\}\[\]\][a-zA-Z0-9])?[a-zA-Z0-9]*)*" title="Please fill correct data does not accept special charactor"
EOD;
        self::$_messageList["narrative"] = $str;

        $str = <<<EOD
 pattern="[a-zA-Z0-9]+(([\!\'\,\.\s\-\@\#\$\%\^\&\*\(\)\_\+\|\\\/\=\{\}\[\]\][a-zA-Z0-9])?[a-zA-Z0-9]*)*" title="Please fill correct data does not accept special charactor"
EOD;
        self::$_messageList["payment_address"] = $str;

        /* --- header text --- */
        $str = <<<EOD
maxlength="150" title="Does not accepts ` and ~ characters"
EOD;
        self::$_messageList["text"] = $str;

        /* --- header textarea --- */
        $str = <<<EOD
 pattern="[a-zA-Z0-9]+(([\!\'\,\.\s\-\@\#\$\%\^\&\*\(\)\_\+\|\\\/\=\{\}\[\]\][a-zA-Z0-9])?[a-zA-Z0-9]*)*" title="Does not accepts ` and ~ characters"
EOD;
        self::$_messageList["textarea"] = $str;


        /* ---  bank refernce  --- */
        $str = <<<EOD
maxlength="20" title="Accepts atmax 20 characters."
EOD;
        self::$_messageList["bankref"] = $str;

        /* ---   help desk : name  --- */
        $str = <<<EOD
required pattern="[a-zA-Z]+(([\'\,\.\s\-\][a-zA-Z])?[a-zA-Z]*)*" aria-required="true" title="This field is required. Use letters.  Numbers or special characters are not allowed"  maxlength="100"
EOD;
        self::$_messageList["contact_name"] = $str;

        /* ---   help desk : message field  --- */
        $str = <<<EOD
required aria-required="true" title="This field is required." maxlength="500" 
EOD;
        self::$_messageList["contact_msg"] = $str;

        /* ---   help desk : subject field  --- */
        $str = <<<EOD
required  aria-required="true" title="Please enter the subject" maxlength="40"
EOD;
        self::$_messageList["contact_sub"] = $str;

        /* ---  merchant suggest : email , name , nature , number --- */
        $str = <<<EOD
required  aria-required="true" title="This field is required." maxlength="255"
EOD;
        self::$_messageList["sugg_extra"] = $str;

        $str = <<<EOD
 pattern="[A-Za-z0-9](([_\.\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([\.\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,})" title="Please enter a valid email address " maxlength="255"
EOD;
        self::$_messageList["sugg_extraemail"] = $str;

        $str = <<<EOD
 title="Please enter valid contact details" maxlength="20" pattern="([0-9]{10,20})"
EOD;
        self::$_messageList["sugg_extrano"] = $str;

        $str = <<<EOD
required pattern="[a-zA-Z]+(([\'\,\.\s\-\][a-zA-Z])?[a-zA-Z]*)*" aria-required="true" title="This field is required. Use letters.  Numbers or special characters are not allowed"  maxlength="70"
EOD;
        self::$_messageList["supplier_name"] = $str;

        /* number in events */

        $str = <<<EOD
title="Accepts only numeric characters" pattern="^[0-9]+$" 
EOD;
        self::$_messageList["number"] = $str;

        $str = <<<EOD
aria-required=”true” title="This field is required. Enter your number of occurences" maxlength="4" pattern="([0-9]{0,4})"
EOD;
        self::$_messageList["subscription"] = $str;

        $str = <<<EOD
 title="Please enter valid IFSC code" maxlength="11" pattern="^[A-Za-z]{4}\d{7}$"
EOD;
        self::$_messageList["ifsc_code"] = $str;
        if ($key_ != null) {
            $value = isset(self::$_messageList[$key_]) ? self::$_messageList[$key_] : NULL;
            return $value;
        } else {
            return (object) self::$_messageList;
        }
    }

}

?>