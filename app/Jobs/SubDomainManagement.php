<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;
use Spatie\Sitemap\SitemapIndex;
use Illuminate\Support\Facades\DB;
use App\Libraries\Helpers;
use App\Model\User;

class SubDomainManagement implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $merchant_id;
    protected $display_url;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($merchant_id, $display_url)
    {
        $this->merchant_id = $merchant_id;
        $this->display_url = $display_url;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {  
        $merchant = DB::table('merchant')->where('merchant_id', $this->merchant_id)->first();
        if($merchant->company_name != ''){
            $xmlString = file_get_contents(public_path('merchant-sites.xml'));
            $xmlObject = simplexml_load_string($xmlString);
            $json = json_encode($xmlObject);
            $phpArray = json_decode($json, true);
            $sitemapindex = SitemapIndex::create(env('APP_URL'));
            $counter = 0;
            foreach($phpArray['sitemap'] as $item) {
                if($item['loc'] == env('APP_URL').'/merchant-sites/'.$this->display_url.'.xml') {
                    $counter++; 
                }
                $sitemapindex->add($item['loc']);
            }
            if($counter == 0){
                $sitemapindex->add(env('APP_URL').'/merchant-sites/'.$this->display_url.'.xml');
            }
            $sitemapindex->writeToFile(public_path('merchant-sites.xml'));
            SitemapGenerator::create('https://'.$this->display_url.env('SUB_DOMAIN_URL'))->getSitemap()->add(Url::create(env('SWIPEZ_BASE_URL').'m/'.$this->display_url.'/')
            ->setLastModificationDate(Carbon::yesterday())
            ->setPriority(1))
            ->add(Url::create(env('SWIPEZ_BASE_URL').'m/'.$this->display_url.'/policies')
            ->setLastModificationDate(Carbon::yesterday())
            ->setPriority(1))
            ->add(Url::create(env('SWIPEZ_BASE_URL').'m/'.$this->display_url.'/paymybill')
            ->setLastModificationDate(Carbon::yesterday())
            ->setPriority(1))
            ->add(Url::create(env('SWIPEZ_BASE_URL').'m/'.$this->display_url.'/payment-link')
            ->setLastModificationDate(Carbon::yesterday())
            ->setPriority(1))
            ->add(Url::create(env('SWIPEZ_BASE_URL').'m/'.$this->display_url.'/aboutus')
            ->setLastModificationDate(Carbon::yesterday())
            ->setPriority(1))
            ->add(Url::create(env('SWIPEZ_BASE_URL').'m/'.$this->display_url.'/contactus')
            ->setLastModificationDate(Carbon::yesterday())
            ->setPriority(1))
            ->writeToFile(public_path('merchant-sites/'.$this->display_url.'.xml'));
        }
    }
}
