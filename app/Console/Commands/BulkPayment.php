<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Cell_DataType;
use Illuminate\Support\Facades\Storage;
use App\Models\MasterModel;
use App\Models\StaffModel;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use App\Http\Controllers\ApiController;

class BulkPayment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bulk:payment';
    protected $messaging;


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return int
     */
    public function handle()
    {
        $ApiController = new ApiController();

        $array = ['7021699544','6389441312','8898249326','8175867678','6387231264','9867599573','9137801064','9918967820','8928287733'];
        foreach ($array as $mobile) {
            //$ApiController->sendWhatsappMessage($mobile, 'mobile', 'mobile_app_installation', [], null, 'en', 1);
            $ApiController->sendWhatsappMessage($mobile, 'mobile', 'photo_upload', [], null, 'hi', 1);
            echo $mobile . PHP_EOL;
        }

        // $deviceToken = 'd_Z0Q7fS20wUo_5bOSBHiG:APA91bHyzjDuWg_eKkvT0lYjehPVQgSpUDes8ykQ6b64qjdhI4bbWJfR2Jtlu5n71oEW8OJdlLd-mw8mIRmieJ8NakqH8Lxd6Q-Fv4T-rrldtRKWiS17lSE'; // Replace with actual device token

        // $this->sendNotificationToDevice($deviceToken, 'hello', 'Hii');
    }

    public function sendNotificationToDevice(string $deviceToken, string $title, string $body, $url = '', $image = '')
    {
        $factory = (new Factory)->withServiceAccount(storage_path(env('FIREBASE_CREDENTIALS')));
        $messaging = $factory->createMessaging();
        $data = [];
        if ($url != '') {
            $data = [
                'deepLink' => $url
            ];
        }
        $notification = Notification::create($title, $body, $image);
        $message = CloudMessage::withTarget('token', $deviceToken)
            ->withNotification($notification)
            ->withData($data);

        return $messaging->send($message);
    }
}
