<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Bulkupload;
use App\Model\Template;
use App\Model\Paymentrequest;
use App\Http\Controllers\GenericController;
use Log;
use Exception;

class bulkuploadsend extends Command {

    private $template_model;
    private $bulk_model;
    private $payment_request_model;
    private $user_id = 0;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bulkinvoice:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send bulk upload invoices';

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
            Log::info('Bulk request send cron initiated');
            $gene = new GenericController();
            
            $list = $this->bulk_model->getBulklist(4);
            foreach ($list as $item) {
                Log::info('Bulk request send bulk id :' . $item->bulk_upload_id);
                $this->user_id = $item->updated_by;
                $this->bulk_model->updateBulkuploadStatus($item->bulk_upload_id, 8, $this->user_id);
                $success = 1;
                $reqs = $this->payment_request_model->getStagingrequest($item->bulk_upload_id);
                foreach ($reqs as $req) {
                    $result = $this->payment_request_model->sendPaymentRequest($item->bulk_upload_id, 1, $req->payment_request_id, $this->user_id);
                    if (!isset($result->message)) {
                        $success = 0;
                        Log::error(__CLASS__ . 'Bulk request sent failed staging req id: ' . $req->payment_request_id . ' Error :' . $result->Message);
                    } else {
                        $gene->sendRequestNotification($result->payment_request_id, '');
                    }
                }
                if ($success == 1) {
                    Log::info('Bulk request sent successfully bulk id :' . $item->bulk_upload_id);
                    $this->bulk_model->updateBulkuploadStatus($item->bulk_upload_id, 5, $this->user_id);
                }
            }
        } catch (Exception $e) {
            Log::error(__CLASS__ . 'EB009 Error while sending bulk request Error: ' . $e->getMessage());
        }
    }

}
