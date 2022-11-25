<?php

namespace App\Libraries;

use Illuminate\Support\Facades\DB;

class CloudFlareApi
{

    public function createSubDomain($display_url, $merchant_id) {

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

    public function deleteSubdomain($merchant_id) {
        $merchant_landing = DB::table('merchant_landing')->where('merchant_id', $merchant_id)->get();
        $emptyjson = json_encode('');
        $deleteResponse=Helpers::APIrequest("https://api.cloudflare.com/client/v4/zones/".env('CLOUDFLARE_ZONE_ID')."/dns_records/".$merchant_landing->cf_id."/", $emptyjson, "DELETE", env('CLOUDFLARE_TOKEN'));
        $deleteResult = json_decode($deleteResponse);
        if(isset($deleteResult->errors[0]->code) && !empty($deleteResult->errors[0]->code)){
            $cf_response = $deleteResult->errors[0]->code;
            DB::table('merchant_landing')->where('merchant_id', $merchant_id)->update(['cf_response'=>$cf_response]);
        }
        else{
            $cf_response = '0';
            DB::table('merchant_landing')->where('merchant_id', $merchant_id)->update(['cf_id'=>null,'cf_response'=>'0']);
            $entry = DB::table('deleted_merchant_pages')->where('merchant_id', $merchant_id)->first();
            if($entry == null) {
                DB::table('deleted_merchant_pages')->insertGetId(['merchant_id'=> $merchant_id ,'sub_domain'=> 'https://'.$this->old_url.env('SUB_DOMAIN_URL')]);
            }
            else{
                DB::table('deleted_merchant_pages')->where('merchant_id', $merchant_id)->update(['sub_domain'=> 'https://'.$this->old_url.env('SUB_DOMAIN_URL')]);
            }
        }
    }

}