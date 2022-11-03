<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Libraries\MailWrapper;
use Carbon\Carbon;
use Exception;
use Log;

class HighValuePayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getPayments:email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'high value payments';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $mail = new MailWrapper();

            $get_high_value_payments = DB::table('payment_transaction as p')
                                    ->join('merchant', 'merchant.merchant_id', '=', 'p.merchant_id')
                                    ->join('customer', 'customer.customer_id', '=', 'p.customer_id')
                                    ->select('p.merchant_id','p.amount','p.payment_mode','p.payment_transaction_status','p.created_date','p.customer_id','merchant.company_name','merchant.created_date as registered_on','customer.mobile','customer.email')
                                    ->where('pg_id', env('PG_ID'))
                                    ->where('amount','>=', env('AMOUNT'))
                                    ->where('payment_transaction_status',1)
                                    ->whereDate('p.created_date',Carbon::yesterday())
                                    ->get()->toArray();
            
            $email_template = "high-value-payments";
            $subject = "High value payments feed";

            if (!empty($get_high_value_payments)) {
                $data['transaction_details'] = $get_high_value_payments;
                $mkp_users = explode(',',env('MKP_USERS'));

                if(!empty($mkp_users)) {
                    foreach($mkp_users as $uk=>$user_email) {
                        $mail->sendmail($user_email,$email_template, $data, $subject);
                    }
                }
            }
        } catch (Exception $e) {
            app('sentry')->captureException($e);
            Log::error(__METHOD__ . 'Error: ' . $e->getMessage());
        }
    }
}
