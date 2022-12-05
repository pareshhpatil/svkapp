<?php

namespace App\Libraries;

use Illuminate\Support\Facades\DB;

/**
 * Version 1.0
 */
class AtomTransactionRequest
{

    private $login;
    private $password;
    private $transactionType;
    private $productId;
    private $amount;
    private $transactionCurrency;
    private $transactionAmount;
    private $clientCode;
    private $transactionId;
    private $transactionDate;
    private $customerAccount;
    private $customerName;
    private $customerEmailId;
    private $customerMobile;
    private $customerBillingAddress;
    private $returnUrl;
    private $mode = "test";
    private $transactionUrl;
    private $nbType = "NBFundTransfer";
    private $ccType = "CCFundTransfer";
    private $reqHashKey = "";

    /**
     * @return string
     */
    public function getReqHashKey()
    {
        return $this->reqHashKey;
    }

    /**
     * @param string $reqHashKey
     */
    public function setReqHashKey($reqHashKey)
    {
        $this->reqHashKey = $reqHashKey;
    }

    /**
     * @return string
     */
    public function getRespHashKey()
    {
        return $this->respHashKey;
    }

    /**
     * @param string $respHashKey
     */
    public function setRespHashKey($respHashKey)
    {
        $this->respHashKey = $respHashKey;
    }

    /**
     * @return the $login
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param string $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * @return the $password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return the $transactionType
     */
    public function getTransactionType()
    {
        return $this->transactionType;
    }

    /**
     * @param string $transactionType
     */
    public function setTransactionType($transactionType)
    {
        $this->transactionType = $transactionType;
    }

    /**
     * @return the $productId
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * @param string $productId
     */
    public function setProductId($productId)
    {
        $this->productId = $productId;
    }

    /**
     * @return the $amount
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param string $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return the $transactionCurrency
     */
    public function getTransactionCurrency()
    {
        return $this->transactionCurrency;
    }

    /**
     * @param string $transactionCurrency
     */
    public function setTransactionCurrency($transactionCurrency)
    {
        $this->transactionCurrency = $transactionCurrency;
    }

    /**
     * @return the $transactionAmount
     */
    public function getTransactionAmount()
    {
        return $this->transactionAmount;
    }

    /**
     * @param string $transactionAmount
     */
    public function setTransactionAmount($transactionAmount)
    {
        $this->transactionAmount = $transactionAmount;
    }

    /**
     * @return the $transactionId
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * @param string $transactionId
     */
    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;
    }

    /**
     * @return the $transactionDate
     */
    public function getTransactionDate()
    {
        return $this->transactionDate;
    }

    /**
     * @param string $transactionDate
     */
    public function setTransactionDate($transactionDate)
    {
        $this->transactionDate = $transactionDate;
    }

    /**
     * @return the $customerAccount
     */
    public function getCustomerAccount()
    {
        return $this->customerAccount;
    }

    /**
     * @param string $customerAccount
     */
    public function setCustomerAccount($customerAccount)
    {
        $this->customerAccount = $customerAccount;
    }

    /**
     * @return the $customerName
     */
    public function getCustomerName()
    {
        return $this->customerName;
    }

    /**
     * @param string $customerName
     */
    public function setCustomerName($customerName)
    {
        $this->customerName = $customerName;
    }

    /**
     * @return the $customerEmailId
     */
    public function getCustomerEmailId()
    {
        return $this->customerEmailId;
    }

    /**
     * @param string $customerEmailId
     */
    public function setCustomerEmailId($customerEmailId)
    {
        $this->customerEmailId = $customerEmailId;
    }

    /**
     * @return the $customerMobile
     */
    public function getCustomerMobile()
    {
        return $this->customerMobile;
    }

    /**
     * @param string $customerMobile
     */
    public function setCustomerMobile($customerMobile)
    {
        $this->customerMobile = $customerMobile;
    }

    /**
     * @return the $customerBillingAddress
     */
    public function getCustomerBillingAddress()
    {
        return $this->customerBillingAddress;
    }

    /**
     * @param string $customerBillingAddress
     */
    public function setCustomerBillingAddress($customerBillingAddress)
    {
        $this->customerBillingAddress = $customerBillingAddress;
    }

    /**
     * @return the $returnUrl
     */
    public function getReturnUrl()
    {
        return $this->returnUrl;
    }

    /**
     * @param string $returnUrl
     */
    public function setReturnUrl($returnUrl)
    {
        $this->returnUrl = $returnUrl;
    }

    /**
     * @return the $mode
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @param string $mode
     */
    public function setMode($mode)
    {
        $this->mode = $mode;
    }

    /**
     * @return the $transactionUrl
     */
    public function getTransactionUrl()
    {
        return $this->transactionUrl;
    }

    /**
     * @param string $transactionUrl
     */
    public function setTransactionUrl($transactionUrl)
    {
        $this->transactionUrl = $transactionUrl;
    }

    public function getnbType()
    {
        return $this->nbType;
    }

    public function getccType()
    {
        return $this->ccType;
    }

    private function setUrl()
    {
        $port = 443;
        if ($this->getMode() == "live") {
            $url = "https://payment.atomtech.in/paynetz/epi/fts";
        } else {
            $url = "https://paynetzuat.atomtech.in/paynetz/epi/fts";
        }
        $this->setTransactionUrl($url);
        $this->setPort($port);
    }

    public function setClientCode($clientCode)
    {
        if ($clientCode == NULL || $clientCode == "") {
            $this->clientCode = urlencode(base64_encode(123));
        } else {
            $this->clientCode = urlencode(base64_encode($clientCode));
        }
    }

    private function getClientCode()
    {
        return $this->clientCode;
    }

    private function setPort($port)
    {
        $this->port = $port;
    }

    private function getPort()
    {
        return $this->port;
    }

    public function getChecksum()
    {
        $str = $this->login . $this->password . "NBFundTransfer" . $this->productId . $this->transactionId . $this->amount . "INR";
        // echo $str;exit;
        $signature = hash_hmac("sha512", $str, $this->reqHashKey, false);
        return $signature;
    }

    private function getData()
    {
        $strReqst = "";
        $strReqst .= "login=" . $this->getLogin();
        $strReqst .= "&pass=" . $this->getPassword();
        //$txnType = $this->getTransactionType();
        //if($txnType== 'NB'){
        $strReqst .= "&ttype=NBFundTransfer";
        //}else{
        //$strReqst .= "&ttype=".$this->getccType();
        //}
        $strReqst .= "&prodid=" . $this->getProductId();
        $strReqst .= "&amt=" . $this->getAmount();
        $strReqst .= "&txncurr=" . $this->getTransactionCurrency();
        $strReqst .= "&txnscamt=" . $this->getTransactionAmount();
        $strReqst .= "&ru=" . $this->getReturnUrl();
        $strReqst .= "&clientcode=" . $this->getClientCode();
        $strReqst .= "&txnid=" . $this->getTransactionId();
        $strReqst .= "&date=" . $this->getTransactionDate();
        $strReqst .= "&udf1=" . $this->getCustomerName();
        $strReqst .= "&udf2=" . $this->getCustomerEmailId();
        $strReqst .= "&udf3=" . $this->getCustomerMobile();
        $strReqst .= "&udf4=" . $this->getCustomerBillingAddress();
        $strReqst .= "&custacc=" . $this->getCustomerAccount();
        if ($this->reqHashKey != '') {
            $strReqst .= "&signature=" . $this->getChecksum();
        }

        return $strReqst;
    }

    private function getOldData()
    {
        //$payment->url = $this->paymentConfig->Url;
        $ttType = "NBFundTransfer";
        $postFields = "";
        $postFields .= "&login=" . $this->getLogin();
        $postFields .= "&pass=" . $this->getPassword();
        $postFields .= "&ttype=" . $ttType;
        $postFields .= "&prodid=" . $this->getProductId();
        $postFields .= "&amt=" . $this->getAmount();
        $postFields .= "&txncurr=" . $this->getTransactionCurrency();
        $postFields .= "&txnscamt=" . $this->getTransactionAmount();
        $postFields .= "&clientcode=" . $this->getClientCode();
        $postFields .= "&txnid=" . $this->getTransactionId();
        $postFields .= "&date=" . $this->getTransactionDate();
        $postFields .= "&custacc=" . $this->getCustomerAccount();
        $postFields .= "&udf1=" . $this->getCustomerName();
        $postFields .= "&udf2=" . $this->getCustomerEmailId();
        $postFields .= "&udf3=" . $this->getCustomerMobile();
        $postFields .= "&udf4=" . $this->getCustomerBillingAddress();
        $postFields .= "&ru=" . $this->getReturnUrl();

        // Not required for merchant
        //$postFields .= "&bankid=".$_POST['bankid'];
        // die($postFields);
        $sendUrl = $this->transactionUrl . "?" . substr($postFields, 1) . "\n";
        // $this->writeLog($sendUrl);
        $returnData = $this->sendInfo($postFields, $this->transactionUrl);
        //  $this->writeLog($returnData . "\n");
        $xmlObjArray = $this->xmltoarray($returnData);

        #Validate tempTxnID
        if ($xmlObjArray['tempTxnId'] == '') {
            $response['status'] = 0;
            $response['return_data'] = $returnData;
            $response['send_url'] = $sendUrl;
            return $response;
        }
        $url = $xmlObjArray['url'];
        $postFields = "";
        $postFields .= "&ttype=" . $ttType;
        $postFields .= "&tempTxnId=" . $xmlObjArray['tempTxnId'];
        $postFields .= "&token=" . $xmlObjArray['token'];
        $postFields .= "&txnStage=1";
        $url = $this->transactionUrl . "?" . $postFields;
        $response['status'] = 1;
        $response['url'] = $url;
        return $response;
    }

    function sendInfo($data, $url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_PORT, 443);
        //curl_setopt($ch, CURLOPT_SSLVERSION,3);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $returnData = curl_exec($ch);

        curl_close($ch);
        return $returnData;
    }

    function xmltoarray($data)
    {
        $parser = xml_parser_create('');
        xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8");
        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
        xml_parse_into_struct($parser, trim($data), $xml_values);
        xml_parser_free($parser);

        $returnArray = array();
        $returnArray['url'] = $xml_values[3]['value'];
        $returnArray['tempTxnId'] = $xml_values[5]['value'];
        $returnArray['token'] = $xml_values[6]['value'];

        return $returnArray;
    }

    /**
     * This function returns transaction token url
     * @return string
     */
    public function getPGUrl()
    {
        if ($this->reqHashKey == '') {
            $response = $this->getOldData();
            return $response;
        } else {
            $data = $this->getData();
            //$this->writeLog($data);
            $response['status'] = 1;
            $response['url'] = $this->transactionUrl . "?" . $data;
            return $response;
        }
    }

    private function writeLog($data)
    {
        $fileName = "date" . date("Y-m-d") . ".txt";
        $fp = fopen("log/" . $fileName, 'a+');
        $data = date("Y-m-d H:i:s") . " - " . $data;
        fwrite($fp, $data);
        fclose($fp);
    }
}
