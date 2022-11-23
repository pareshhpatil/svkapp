<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Autocollect;

class AutocollectPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'autocollect:payments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To fetch auto collect payments';

    public $client_id = null;
    public $client_secret = null;
    public $api_url = null;

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
        $model = new Autocollect();
        $subscriptions = $model->getActiveSubscription();
        foreach ($subscriptions as $row) {
            $key_details = $model->getMerchantData($row->merchant_id, 'AUTOCOLLECT_KEY_DETAILS');
            if ($key_details != false) {
                $keys = json_decode($key_details);
                $this->client_id = $keys->key;
                $this->client_secret = $keys->secret;
                //$cashfree_api = new CashfreeAutocollectAPI($keys->mode, $keys->key, $keys->secret);
                if ($row->status == 0) {
                    $response = $this->APIInvoike("subscriptions/" . $row->pg_ref, 'GET', '', $keys->mode);
                    if (isset($response['response']['subscription']['status'])) {
                        if ($response['response']['subscription']['status'] == 'ACTIVE') {
                            $model->updateSubscriptionStatus($row->subscription_id, 1);
                        }
                    }
                } elseif ($row->status == 1) {
                    $response = $this->APIInvoike("subscriptions/" . $row->pg_ref . "/payments?count=100", 'GET', '', $keys->mode);
                    if (!empty($response['response']['payments'])) {
                        foreach ($response['response']['payments'] as $prow) {
                            $model->handleSubscriptionPayment($row->subscription_id, $prow['paymentId'], $prow['referenceId'], $prow['amount'], $prow['addedOn'], $prow['status']);
                        }
                    }
                }
            }
        }
    }


    function APIInvoike($url, $method, $post_data = '', $mode)
    {
        if ($mode == 'PROD') {
            $this->api_url = "https://api.cashfree.com/api/v2/";
        } else {
            $this->api_url = "https://test.cashfree.com/api/v2/";
        }
        $header_array = array(
            "X-Client-Id: " . $this->client_id,
            "X-Client-Secret: " . $this->client_secret,
            "Content-Type: application/json"
        );
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->api_url . $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => $post_data,
            CURLOPT_HTTPHEADER => $header_array,
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            $res['status'] = 0;
            $res['error'] = $err;
        } else {
            $res['status'] = 1;
            $res['response'] = json_decode($response, true);
        }
        return $res;
    }
}
