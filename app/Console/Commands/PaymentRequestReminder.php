<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\ParentModel;
use Illuminate\Support\Facades\DB;
use App\Jobs\PatronNotification;
use App\Libraries\Encrypt;
use Illuminate\Support\Str;
use SwipezShortURLWrapper;
use Exception;

class PaymentRequestReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contactrequest:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder for contact payment request';
    private $merchant_detail = array();

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
        $days = array(3, 1, 0);
        foreach ($days as $day) {
            $retObj = DB::select("call `get_pending_bills`(" . $day . ",2);");
            foreach ($retObj as $request) {
                $this->paymentNotification($request, $day);
            }
        }
    }
    public function paymentNotification($request, $day)
    {
        $invoiceModel = new ParentModel();
        if (!isset($this->merchant_detail[$request->user_id])) {
            $this->merchant_detail[$request->user_id] = $invoiceModel->getTableRow('user', 'user_id', $request->user_id);
        }
        if ($day == 1) {
            $due_text = ' Due tomorrow';
        } else if ($day == 0) {
            $due_text = ' Due today';
        } else {
            $due_text = ' Due in ' . $day . ' days';
        }
        $request_id = $request->payment_request_id;
        $url = env('APP_URL') . '/patron/paymentlink/view/' . Encrypt::encode($request_id);
        $report_link = env('APP_URL') . '/patron/paymentlink/reportlink/' . Encrypt::encode($request_id);
        if ($request->email != '') {
            $data['type'] = 'email';
            $data['email_template_name'] = 'payment_request';
            $data['email_subject'] = 'Payment Request from ' . $request->company_name . $due_text;
            $data['email_id'] = $request->email;
            $data['data']['company_name'] =  $request->company_name;
            $data['data']['payment_request_id'] =  $request_id;
            $data['data']['merchant_id'] =  $request->merchant_id;
            $data['data']['due_date'] = $request->due_date;
            $data['data']['narrative'] = $request->narrative;
            $data['data']['absolute_cost'] = $request->absolute_cost;
            $data['data']['merchant_email'] = $this->merchant_detail[$request->user_id]->email_id;
            $data['data']['merchant_mobile'] = $this->merchant_detail[$request->user_id]->mobile_no;
            $data['data']['payment_link'] = $url;
            $data['data']['report_link'] = $report_link;
            PatronNotification::dispatch($data)->onQueue(env('SQS_PATRON_NOTIFICATION'));
        }
        if ($request->mobile != '' && $request->sms_available == true) {
            $data = array();
            $company_name = Str::limit($request->company_name, 28, '...');
            if ($request->short_url == '') {
                $request->short_url = $this->getShortURL($url);
                $invoiceModel->updateTable('payment_request', 'payment_request_id', $request_id, 'short_url', $request->short_url);
            }

            $data['type'] = 'sms';
            $data['sms'] = 'Your ' . $company_name . ' bill is' . $due_text . '. Pay now via ' . $request->short_url . ' to enjoy uninterrupted services.';
            $data['sms_gateway'] = $request->sms_gateway;
            $data['sms_gateway_detail'] = null;
            if ($request->sms_gateway != 1) {
                $data['sms_gateway_detail'] = $invoiceModel->getTableRow('sms_gateway', 'sg_id', $request->sms_gateway);
            }
            $data['data']['payment_request_id'] =  $request_id;
            $data['data']['merchant_id'] =  $request->merchant_id;
            PatronNotification::dispatch($data)->onQueue(env('SQS_PATRON_NOTIFICATION'));
        }
    }

    function getShortURL($long_url)
    {
        try {
            $long_urls[] = $long_url;
            define('SWIPEZ_UTIL_PATH', getenv('SWIPEZ_BASE') . 'swipezutil');
            require SWIPEZ_UTIL_PATH . '/src/shorturl/SwipezShortURLWrapper.php';
            $shortUrlWrap = new SwipezShortURLWrapper();
            $shortUrls = $shortUrlWrap->SaveUrl($long_urls);
            $shortUrl = $shortUrls[0];
            return $shortUrl;
        } catch (Exception $e) {
            app('sentry')->captureException($e);
        }
    }
}
