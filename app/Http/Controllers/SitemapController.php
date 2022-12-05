<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Spatie\ArrayToXml\ArrayToXml;
use Carbon\Carbon;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Spatie\Sitemap\SitemapGenerator;
use Illuminate\Support\Facades\File;
use Spatie\Sitemap\SitemapIndex;

class SitemapController extends Controller
{
    public function createSiteMap($url)
    {
        $xmlString = file_get_contents(public_path('merchant-sites.xml'));
        $xmlObject = simplexml_load_string($xmlString);
        $json = json_encode($xmlObject);
        $phpArray = json_decode($json, true);
        $sitemapindex = SitemapIndex::create(env('APP_URL'));
        $counter = 0;
        foreach ($phpArray['sitemap'] as $item) {
            if ($item['loc'] == env('APP_URL') . '/merchant-sites/' . $url . '.xml') {
                $counter++;
            }
            $sitemapindex->add($item['loc']);
        }
        if ($counter == 0) {
            $sitemapindex->add(env('APP_URL') . '/merchant-sites/' . $url . '.xml');
        }
        $sitemapindex->writeToFile(public_path('merchant-sites.xml'));
        SitemapGenerator::create('https://' . $url . env('SUB_DOMAIN_URL'))->getSitemap()->add(Url::create(env('SWIPEZ_BASE_URL') . 'm/' . $url . '/')
            ->setLastModificationDate(Carbon::yesterday())
            ->setPriority(1))
            ->add(Url::create(env('SWIPEZ_BASE_URL') . 'm/' . $url . '/polices')
                ->setLastModificationDate(Carbon::yesterday())
                ->setPriority(1))
            ->add(Url::create(env('SWIPEZ_BASE_URL') . 'm/' . $url . '/paymybill')
                ->setLastModificationDate(Carbon::yesterday())
                ->setPriority(1))
            ->add(Url::create(env('SWIPEZ_BASE_URL') . 'm/' . $url . '/paymentlink')
                ->setLastModificationDate(Carbon::yesterday())
                ->setPriority(1))
            ->add(Url::create(env('SWIPEZ_BASE_URL') . 'm/' . $url . '/aboutus')
                ->setLastModificationDate(Carbon::yesterday())
                ->setPriority(1))
            ->add(Url::create(env('SWIPEZ_BASE_URL') . 'm/' . $url . '/contactus')
                ->setLastModificationDate(Carbon::yesterday())
                ->setPriority(1))
            ->writeToFile(public_path('merchant-sites/' . $url . '.xml'));
    }
}
