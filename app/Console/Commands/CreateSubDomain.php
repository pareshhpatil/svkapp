<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Jobs\SubDomainManagement;
use App\Libraries\Helpers;

class CreateSubDomain extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:subdomain';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command subdomain for existing merchants';

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
        $allmerchants = DB::table('merchant')->where('display_url', '!=', null)->where('display_url', '!=', '')->get();
        foreach ($allmerchants as $merchant) {
            if ($merchant->company_name != '') {
                $this->createSubDomain($merchant->display_url, $merchant->merchant_id);
            }
        }
    }

    function createSubDomain($display_url, $merchant_id)
    {
        $body = [
            "type" => "A",
            "name" => $display_url,
            "content" => env('SUBDOMAIN_IP_ADDRESS'),
            "ttl" => 120,
            "pripority" => 10,
            "proxied" => true
        ];
        $json = json_encode($body);

        $response = Helpers::APIrequest("https://api.cloudflare.com/client/v4/zones/" . env('CLOUDFLARE_ZONE_ID') . "/dns_records/", $json, "POST", env('CLOUDFLARE_TOKEN'));
        $result = json_decode($response);
        if (isset($result->result->id) && !empty($result->result->id)) {
            $cf_id = $result->result->id;
            DB::table('merchant_landing')->where('merchant_id', $merchant_id)->update(['cf_id' => $cf_id]);
        } else {
            DB::table('merchant_landing')->where('merchant_id', $merchant_id)->update(['cf_response' => $response]);
        }
    }
}
