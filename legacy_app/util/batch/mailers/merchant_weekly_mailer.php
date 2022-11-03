<?php

include('../config.php');

class MerchantWeeklyMailer extends Batch {

    public $logger = NULL;
    public $encrypt = NULL;
    public $message = NULL;
    private $toDate = NULL;
    private $fromDate = NULL;
    private $sendToZero = NULL;

    function __construct() {
        parent::__construct();
        $this->logger = new SwipezLogger('log4php_config.xml');
        $this->MerchantWeeklyMailer();
    }

    public function MerchantWeeklyMailer() {
        try {
            #process input params
            $this->processInputParams();

            #fetch data from DB
            $data = $this->fetchDataFromDB();

            #loop records and send indi email
            $this->sendMailer($data);

            #report status
        #
        } catch (Exception $e) {
Sentry\captureException($e);
            
$this->logger->error(__CLASS__, '[E31]Exception occured while running merchant weekly mailer with input params ' . ' ' . $ex->getMessage());
        }
    }

    /**
     * Process input param given to the script
     *
     */
    public function processInputParams() {
        if (count($GLOBALS['argv']) == 4) {
            $fromDate = $GLOBALS['argv'][1];
            $toDate = $GLOBALS['argv'][2];
            $sendToZero = $GLOBALS['argv'][3];

            if (preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $fromDate)) {
                $this->fromDate = $fromDate;
            } else {
                print "Error: Invalid from date format $fromDate should be YYYY-MM-DD";
                throw new Exception("Invalid from date format " . $fromDate);
            }
            if (preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $toDate)) {
                $this->toDate = $toDate;
            } else {
                print "Error: Invalid to date format $toDate should be YYYY-MM-DD";
                throw new Exception("Invalid to date format " . $toDate);
            }
        } else {
            print "Invalid # of command line arguments. <from date> <to date> <1 or 0>";
            throw new Exception("Invalid # of command line arguments");
        }
    }

    /**
     * Fetch invoice summary data from database
     * 
     * @return type
     */
    public function fetchDataFromDB() {
        try {
            $sql = "call mailer_rpt_payment_summary(:from_date, :to_date)";
            $params = array(':from_date' => $this->fromDate,
                ':to_date' => $this->toDate);
            $this->db->exec($sql, $params);
            $resultSet = $this->db->resultset();

            return $resultSet;
        } catch (Exception $e) {
Sentry\captureException($e);
            print "Stored procedure execution failed";
            throw new Exception("Stored procedure execution failed " . $ex->getMessage());
        }
    }

    /**
     * Send mailer to merchants
     * 
     * @param type $data
     */
    public function sendMailer($data) {
        $emailWrapper = new EmailWrapper();
        $subject = 'Weekly collections summary';
        $inputEmail = file_get_contents("content/weekly_summary.html");
        #setlocale(LC_MONETARY, 'en_IN');

	setlocale(LC_MONETARY, 'en_IN');
        foreach ($data as $record) {
            $message = $inputEmail;
            $fname = $record['merchant_fname'];
            $email = $record['merchant_email'];
            #$email = "shuhaid@gmail.com";

            $invoices_sent = $record['invoices_sent'];
            $invoices_sent_amount = $record['invoices_sent_amount'];
            $invoices_sent_amount = floatval($invoices_sent_amount);


            $pymt_online_number = $record['pymt_online_number'];
            $pymt_online_amount = $record['pymt_online_amount'];


            $pymt_offline_number = $record['pymt_offline_number'];
            $pymt_offline_amount = $record['pymt_offline_amount'];

            $invoices_pending_amount = $record['invoices_pending_amount'];

            $invoices_pending = $invoices_sent - ($pymt_online_number + $pymt_offline_number);

            if ($invoices_sent > 0 && $invoices_sent_amount > 0) {
                $pymt_online_number_per = round(($pymt_online_number / $invoices_sent) * 100);
                $pymt_online_amount_per = round(($pymt_online_amount / $invoices_sent_amount) * 100);
                $pymt_offline_number_per = round(($pymt_offline_number / $invoices_sent) * 100);
                $pymt_offline_amount_per = round(($pymt_offline_amount / $invoices_sent_amount) * 100);
                $balance_pnd_per = round(($invoices_pending_amount / $invoices_sent_amount) * 100);
            } else {
                $pymt_online_number_per = 0;
                $pymt_online_amount_per = 0;
                $pymt_offline_number_per = 0;
                $pymt_offline_amount_per = 0;
                $balance_pnd_per = 0;
            }
/*
            $invoices_sent_amount = $this->moneyFormatIndia($invoices_sent_amount);
            $pymt_online_amount = $this->moneyFormatIndia(floatval($record['pymt_online_amount']));
            $pymt_offline_amount = $this->moneyFormatIndia(floatval($record['pymt_offline_amount']));
            $invoices_pending_amount = $this->moneyFormatIndia(floatval($record['invoices_pending_amount']));
*/

            $invoices_sent_amount = money_format('%!i',$invoices_sent_amount);
            $pymt_online_amount = money_format('%!i',floatval($record['pymt_online_amount']));
            $pymt_offline_amount = money_format('%!i',floatval($record['pymt_offline_amount']));
            $invoices_pending_amount = money_format('%!i',floatval($record['invoices_pending_amount']));

            $curr_month = date('F Y');
            $message = str_replace("__MM__ __YYYY__", $curr_month, $message);
            $message = str_replace("__INV_SENT__", $invoices_sent, $message);
            $message = str_replace("__INV_SENT_AMT__", $invoices_sent_amount, $message);
            $message = str_replace("__PD_ONL__", $pymt_online_number, $message);
            $message = str_replace("__PD_ONL_AMT__", $pymt_online_amount, $message);
            $message = str_replace("__PD_OFF__", $pymt_offline_number, $message);
            $message = str_replace("__PD_OFF_AMT__", $pymt_offline_amount, $message);
            $message = str_replace("__INV_PND__", $invoices_pending, $message);
            $message = str_replace("__BAL_PND__", $invoices_pending_amount, $message);
            $message = str_replace("__PD_ONL_PER__", $pymt_online_number_per, $message);
            $message = str_replace("__PD_ONL_AMT_PER__", $pymt_online_amount_per, $message);
            $message = str_replace("__PD_OFF_PER__", $pymt_offline_number_per, $message);
            $message = str_replace("__PD_OFF_AMT_PER__", $pymt_offline_amount_per, $message);
            $message = str_replace("__BAL_PND_PER__", $balance_pnd_per, $message);

            $emailWrapper->sendMail($email, $fname, $subject, $message);
        }
    }

    /**
     * 
     * @param type $num
     * @return type
     */
    function moneyFormatIndia($num) {
        $explrestunits = "";
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
        return $thecash; // writes the final format where $currency is the currency symbol.
    }

}
new MerchantWeeklyMailer();
