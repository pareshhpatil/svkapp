<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Tags\Url;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\SitemapIndex;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Model\MerchantPage;

class CreateSiteMap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:sitemap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create sitemap for merchants';

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
        $MerchantPage = new MerchantPage();
        $allmerchants = $MerchantPage->getMerchantSitemap();
        $min_date = 2020;
        $max_count = 8000;

        $MerchantArray = [];
        foreach ($allmerchants as $m) {
            $date = (int)substr($m->last_update_date, 0, 4);
            if ($date <= $min_date) {
                $MerchantArray[$min_date][] = $m;
            } else {
                $MerchantArray[$date][] = $m;
            }
        }
        foreach ($MerchantArray as $year => $marray) {
            $split_array = array_chunk($marray, $max_count);
            #Create new sitemap file for merchant-sites
            foreach ($split_array as $key => $spmarray) {
                $sitemapindex = SitemapIndex::create(env('APP_URL'));
                #Add new sitemap entry in merchant-sites.xml
                foreach ($spmarray as $merchant) {
                    $modified_date=Carbon::parse($merchant->last_update_date); 
                    $sitemapindex->add(env('APP_URL') . '/merchant-sites/' . $merchant->display_url . '.xml');
                    if (!file_exists(public_path() . '/merchant-sites/' . $merchant->display_url . '.xml')) {
                        SitemapGenerator::create('https://' . $merchant->display_url . env('SUB_DOMAIN_URL'))->getSitemap()->add(Url::create(env('SWIPEZ_BASE_URL') . 'm/' . $merchant->display_url . '/')
                            ->setLastModificationDate($modified_date)
                            ->setPriority(1))
                            ->add(Url::create(env('SWIPEZ_BASE_URL') . 'm/' . $merchant->display_url . '/policies')
                                ->setLastModificationDate($modified_date)
                                ->setPriority(1))
                            ->add(Url::create(env('SWIPEZ_BASE_URL') . 'm/' . $merchant->display_url . '/paymybill')
                                ->setLastModificationDate($modified_date)
                                ->setPriority(1))
                            ->add(Url::create(env('SWIPEZ_BASE_URL') . 'm/' . $merchant->display_url . '/payment-link')
                                ->setLastModificationDate($modified_date)
                                ->setPriority(1))
                            ->add(Url::create(env('SWIPEZ_BASE_URL') . 'm/' . $merchant->display_url . '/aboutus')
                                ->setLastModificationDate($modified_date)
                                ->setPriority(1))
                            ->add(Url::create(env('SWIPEZ_BASE_URL') . 'm/' . $merchant->display_url . '/contactus')
                                ->setLastModificationDate($modified_date)
                                ->setPriority(1))
                            ->writeToFile(public_path('merchant-sites/' . $merchant->display_url . '.xml'));
                    }
                }
                if ($key > 0) {
                    $file_count = '-' . $key;
                } else {
                    $file_count = '';
                }

                #Save merchant-sites.xml Sitemap file
                $file_name = 'merchant-sites-' . $year . $file_count . '.xml';
                $sitemapindex->writeToFile(public_path($file_name));
            }
        }
    }
}
