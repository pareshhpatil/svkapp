<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Paymentrequest;
use App\Http\Controllers\GenericController;
use Log;
use Exception;

class remindersend extends Command {

    private $payment_request_model;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Payment request reminder';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
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
            Log::info('Payment request reminder send cron initiated');
            $gene = new GenericController();
            $reminders = array(0, 1, 3);
            foreach ($reminders as $day) {
                Log::info('Payment request reminder for day ' . $day . ' cron initiated');
                switch ($day) {
                    case 0:
                        $title = ' Due today';
                        break;
                    case 1 :
                        $title = ' Due tomorrow';
                        break;
                    default :
                        $title = ' Due in ' . $day . ' days';
                        break;
                }
                $list = $this->payment_request_model->getPendingRequest($day);
                foreach ($list as $item) {
                    $gene->sendRequestNotification($item->payment_request_id, '', $title);
                }
            }
        } catch (Exception $e) {
            Log::error(__CLASS__ . 'EB009 Error while sending reminder request Error: ' . $e->getMessage());
        }
    }

}
