<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Bulkupload;
use App\Model\Template;
use App\Model\Paymentrequest;
use App\Http\Controllers\GenericController;
use Log;
use Exception;

class Apirequestsend extends Command {

    private $template_model;
    private $bulk_model;
    private $payment_request_model;
    private $user_id = 0;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apiinvoice:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Api invoices';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        $this->bulk_model = new Bulkupload();
        $this->template_model = new Template();
        $this->payment_request_model = new Paymentrequest();
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        try {
            Log::info('Api request send cron initiated');
            $gene = new GenericController();
            $list = $this->bulk_model->getAPIist(4);
            foreach ($list as $item) {
                Log::info('Api request send api id :' . $item->api_id);
                $this->user_id = $item->updated_by;
                $this->bulk_model->updateAPIUploadStatus($item->api_id, 8, $this->user_id);
                $success = 1;
                $reqs = $this->payment_request_model->getStagingrequest($item->api_id, 2);
                foreach ($reqs as $req) {
                    $result = $this->payment_request_model->sendPaymentRequest($item->api_id, 2, $req->payment_request_id, $this->user_id);
                    if (!isset($result->message)) {
                        $success = 0;
                        Log::error('Api request sent failed staging req id: ' . $req->payment_request_id . ' Error :' . $result->Message);
                    } else {
                        $gene->sendRequestNotification($result->payment_request_id, '');
                    }
                }
                if ($success == 1) {
                    Log::info('Api request sent successfully Api id :' . $item->api_id);
                    $this->bulk_model->updateAPIUploadStatus($item->api_id, 5, $this->user_id);
                }
            }
        } catch (Exception $e) {
            Log::error('EB009 Error while sending api request Error: ' . $e->getMessage());
        }
    }

}
