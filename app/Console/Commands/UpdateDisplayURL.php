<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateDisplayURL extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:url';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update default display url for merchants';

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

    public function generateUrl($url)
    {                                                 //Generating unique display_url
        $random = rand(100, 999);
        $newurl = $url . $random;
        $existDefaultUrl = DB::table('merchant')->where('display_url', $newurl)->first();
        if ($existDefaultUrl != null) {
            return $this->generateUrl($url);
        } else {
            return $newurl;
        }
    }

    public function setUrl($url)
    {
        $url = str_replace('https://', '', $url);
        $url = str_replace('http://', '', $url);
        $url = str_replace('www.', '', $url);
        $url = str_replace('.com', '', $url);
        $url = str_replace('.co.in', '', $url);
        $url = str_replace('.in', '', $url);
        $url = strtolower($url);                                         //modifying url and removing special chars
        $url = str_replace(' ', '', str_replace('/', '-', $url));
        $url = preg_replace('/[^A-Za-z0-9\-]/', '', $url);
        return $url;
    }

    public function setMerchantDetail($merchant)
    {                                      //Setting Merchant landing details
        if ($merchant->industry_type > 28 || $merchant->industry_type == 0 ||  $merchant->industry_type == null || $merchant->industry_type == '') {
            $merchant_detail = DB::table('default_industry_merch_landing')->where('industry_id', 0)->first();
        } else {
            $industry = DB::table('config')->where('config_key', $merchant->industry_type)->where('config_type', 'industry_type')->first();

            if ($industry == null) {
                $merchant_detail = DB::table('default_industry_merch_landing')->where('industry_id', 0)->first();
            } else {
                $merchant_detail = DB::table('default_industry_merch_landing')->where('industry_id', $industry->config_key)->first();
            }
        }

        if ($merchant_detail->industry_id == 0 || $merchant_detail->industry_id == 28) {      // if industry is 0 or null or other
            $default_banner = $merchant_detail->default_image_path;
        } else {                                                                               // if insustry is valid industry means not zero or other or null.
            $random = rand(1, 10);
            $default_banner = 'landingpage/' . $industry->config_key . '_' . $random . '.jpg';
            if (!is_file(public_path($default_banner))) {
                $default_banner = $merchant_detail->default_image_path;
            }
        }

        $newMerchant = DB::table('merchant_landing')->insertGetId([
            'merchant_id' => $merchant->merchant_id,
            'overview' => $merchant_detail->overview,
            'terms_condition' => $merchant_detail->terms_condition,
            'cancellation_policy' => $merchant_detail->cancellation_policy,
            'about_us' => $merchant_detail->about_us,
            'office_location' => $merchant_detail->office_location,
            'contact_no' => $merchant_detail->contact_no,
            'email_id' => $merchant_detail->email_id,
            'logo' => 'landingpage/default-logo.png',
            'banner' => $default_banner,
            'booking_background' => $merchant_detail->booking_background,
            'booking_title' => $merchant_detail->booking_title,
            'booking_hide_menu' => $merchant_detail->booking_hide_menu,
            'banner_text' => $merchant_detail->banner_text,
            'banner_paragraph' => $merchant_detail->banner_paragraph,
            'pay_my_bill_text' => $merchant_detail->pay_my_bill_text,
            'pay_my_bill_paragraph' => $merchant_detail->pay_my_bill_paragraph,
            'created_by' => 'System',
            'last_update_by' => 'System',
            'created_date' => date('Y-m-d H:i:s'),
            'cf_id' => null,
            'cf_response' => 0
        ]);
    }

    public function handle()
    {
        $existingMerchants = DB::table('merchant')->where('display_url', '!=', null)->where('display_url', '!=', '')->get();
        foreach ($existingMerchants as $merchant) {
            $url = $this->setUrl($merchant->display_url);
            if (strtolower($merchant->display_url) != $url) {
                $existDefaultUrl = DB::table('merchant')->where('display_url', $url)->first();
                if ($existDefaultUrl != null) {
                    $url = $this->generateUrl($url);
                }
                DB::table('display_url_backup')->insertGetId(['merchant_id' => $merchant->merchant_id, 'old_url' => $merchant->display_url, 'new_url' => $url]);
                DB::table('merchant')->where('merchant_id', $merchant->merchant_id)->update(['display_url' => $url]);
            }
            $merchant_landing = DB::table('merchant_landing')->where('merchant_id', $merchant->merchant_id)->first();
            if ($merchant_landing == null) {
                $this->setMerchantDetail($merchant);
            }
        }

        $allMerchants = DB::table('merchant')->where('display_url', null)->orWhere('display_url', '')->get();

        $displayUrls = [];
        foreach ($allMerchants as $merchant) {
            if ($merchant->company_name != '') {
                $url = $this->setUrl($merchant->company_name);
                if (strlen($url) > 10) {
                    $url = substr($url, 0, 10);
                }
                $existDefaultUrl = DB::table('merchant')->where('display_url', $url)->first();
                if ($existDefaultUrl != null) {
                    $url = $this->generateUrl($url);
                }
                array_push($displayUrls, $url);
                DB::table('merchant')->where('merchant_id', $merchant->merchant_id)->update(['display_url' => $url]);
                $this->setMerchantDetail($merchant);
            }
        }
    }
}
