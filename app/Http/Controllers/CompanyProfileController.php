<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AppController;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;
use Spatie\Sitemap\SitemapIndex;
use Illuminate\Support\Facades\File;
use App\Jobs\SubDomainManagement;
use App\Model\CompanyProfile;
use Illuminate\Http\Request;
use App\Libraries\Helpers;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Swipez\ShortUrl\ShortUrl;
use Validator;
use SwipezShortURLWrapper;
use Illuminate\Support\Facades\Redis;

class CompanyProfileController extends AppController
{
    public function __construct()
    {
        parent::__construct();
        $this->CompanyProfileModel = new CompanyProfile();
        $this->richtext = true;
        view::share("richtext", $this->richtext);
    }

    public function profile($type)
    {
        $merchant = $this->CompanyProfileModel->getTableRow('merchant', 'merchant_id', $this->merchant_id);
        $details = $this->CompanyProfileModel->getTableRow('merchant_landing', 'merchant_id', $this->merchant_id);
        $default = $this->CompanyProfileModel->getTableRow('default_industry_merch_landing', 'industry_id', $merchant->industry_type);
        $industry_id = $merchant->industry_type;
        if ($default == false) {
            $default = $this->CompanyProfileModel->getTableRow('default_industry_merch_landing', 'industry_id', 28);

            $industry_id = 28;
        }
        $industry_name = $this->CompanyProfileModel->getIndustry($industry_id);
        if ($details == false) {
            $details = $default;
        } else {
            foreach ($default as $key => $row) {
                if (isset($details->$key)) {
                    if ($details->$key == '' || $details->$key == null) {
                        $details->$key = $default->$key;
                    }
                } else {
                    $details->$key = $default->$key;
                }
            }
        }
        $data = Helpers::setBladeProperties('Company - ' . ucfirst($type), [], [14]);
        $data['industry_name'] = $industry_name;
        $data['details'] = $details;
        $data['merchant'] = $merchant;
        $data['type'] = $type;
        $route = str_replace(' ', '', $type);
        ////show tour flag is set for hero help tour
        $data['showTour'] = isset($details->is_complete_company_page) ? $details->is_complete_company_page : 0;

        return view('app.merchant.company-profile.' . $route, $data);
    }

    public function updateBannerImages($request)
    {
        if ($request->banner) {
            $bannerName = date('Ymd') . time() . '.' . $request->banner->extension();
            $this->CompanyProfileModel->updateTable('merchant_landing', 'merchant_id', $this->merchant_id, 'banner', $bannerName, 'is_complete_company_page', 1);
            $request->banner->move(public_path('uploads/images/landing'), $bannerName);
        }
        if ($request->logo) {
            $logoName = date('Ymd') . time() . '.' . $request->logo->extension();
            $this->CompanyProfileModel->updateTable('merchant_landing', 'merchant_id', $this->merchant_id, 'logo', $logoName, 'is_complete_company_page', 1);
            $request->logo->move(public_path('uploads/images/landing'), $logoName);
        }
    }

    public function deleteSiteMap($merchant, $phpArray, $sitemapindex)
    {
        if (File::exists('merchant-sites/' . $merchant->display_url . '.xml')) {
            File::delete('merchant-sites/' . $merchant->display_url . '.xml');
        }
        foreach ($phpArray['sitemap'] as $item) {
            if ($item['loc'] != env('APP_URL') . '/' . $merchant->display_url . '.xml') {
                // if($item['loc'] != 'https://www.swipez.in/merchant-sites/'.$merchant->display_url.'.xml') {
                $sitemapindex->add($item['loc']);
            }
        }
        return $sitemapindex;
    }

    public function createSiteMap($merchant, $request, $phpArray, $sitemapindex)
    {
        $sitemapindex = $this->deleteSiteMap($merchant, $phpArray, $sitemapindex);
        $sitemapindex->add(env('APP_URL') . '/merchant-sites/' . $request->display_url . '.xml');
        $sitemapindex->writeToFile('merchant-sites.xml');
        if (File::exists('merchant-sites/' . $merchant->display_url . '.xml')) {
            File::delete('merchant-sites/' . $merchant->display_url . '.xml');
        }
        $this->CompanyProfileModel->updateRows('merchant_landing', 'merchant_id',  $this->merchant_id, ['overview' => $request->overview, 'publishable' => $request->publish, 'banner_text' => $request->banner_text, 'banner_paragraph' => $request->banner_paragraph]);
        SitemapGenerator::create('https://' . $request->display_url . '.swipez.in')->getSitemap()->add(Url::create(env('SWIPEZ_BASE_URL') . 'm/' . $request->display_url . '/')
            ->setLastModificationDate(Carbon::yesterday())
            ->setPriority(1))
            ->add(Url::create(env('SWIPEZ_BASE_URL') . 'm/' . $request->display_url . '/policies')
                ->setLastModificationDate(Carbon::yesterday())
                ->setPriority(1))
            ->add(Url::create(env('SWIPEZ_BASE_URL') . 'm/' . $request->display_url . '/paymybill')
                ->setLastModificationDate(Carbon::yesterday())
                ->setPriority(1))
            ->add(Url::create(env('SWIPEZ_BASE_URL') . 'm/' . $request->display_url . '/payment-link')
                ->setLastModificationDate(Carbon::yesterday())
                ->setPriority(1))
            ->add(Url::create(env('SWIPEZ_BASE_URL') . 'm/' . $request->display_url . '/aboutus')
                ->setLastModificationDate(Carbon::yesterday())
                ->setPriority(1))
            ->add(Url::create(env('SWIPEZ_BASE_URL') . 'm/' . $request->display_url . '/contactus')
                ->setLastModificationDate(Carbon::yesterday())
                ->setPriority(1))
            ->writeToFile('merchant-sites/' . $request->display_url . '.xml');
    }

    public function updateHome(Request $request)
    {
        //$merchant = $this->CompanyProfileModel->getTableRow('merchant', 'merchant_id', $this->merchant_id);
        //$xmlString = file_get_contents(public_path('merchant-sites.xml'));
        //$xmlObject = simplexml_load_string($xmlString);
        //$json = json_encode($xmlObject);
        // $phpArray = json_decode($json, true);
        // $sitemapindex = SitemapIndex::create(env('APP_URL'));
        $viewPageUrl = env('SWIPEZ_BASE_URL') . 'm/' . $request->display_url . '/';

        if ($request->publish == 0) {
            $this->CompanyProfileModel->updateTable('merchant_landing', 'merchant_id', $this->merchant_id, 'publishable', $request->publish, 'is_complete_company_page', 1);
            //$merchant_landing = $this->CompanyProfileModel->getTableRow('merchant_landing', 'merchant_id', $this->merchant_id);
            //if ($merchant_landing->cf_id != null) {
            // SubDomainManagement::dispatch($this->merchant_id, $request->display_url, $merchant->display_url, 'delete')->onQueue(env('SQS_SUBDOAMIN_MANAGEMENT'));
            //}
            // $this->deleteSiteMap($merchant, $phpArray, $sitemapindex);
            // $sitemapindex->writeToFile('merchant-sites.xml');
            return redirect()->back()->with('success', "Your changes have been saved. Your company pages are disabled now.");
        } else {
            $validator = Validator::make($request->all(), [
                'display_url' => 'required|min:3|max:15|string',
                'banner_text' => 'max:100',
                'banner_paragraph' => 'max:250',
                'location' => 'max:255',
                'contact_no' => 'max:13',
                'email_id' => 'max:250',
                'banner' => 'max:2048|mimes:jpeg,jpg,png,gif',
                'logo' =>  'mimes:jpeg,jpg,png,gif|max:1024'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            }
            $display_url = $this->CompanyProfileModel->getColumnValue('merchant', 'merchant_id',  $this->merchant_id, 'display_url');
            if ($display_url != $request->display_url) {
                $existing_url = $this->CompanyProfileModel->getTableRow('merchant', 'display_url', $request->display_url);
                if ($existing_url != null) {
                    if ($existing_url->merchant_id != $this->merchant_id) {
                        return redirect()->back()->with('error', 'Display URL is not available. Please try a different display URL as the URL you have chosen in not available');
                    }
                }
                $this->CompanyProfileModel->updateTable('merchant', 'merchant_id', $this->merchant_id, 'display_url', $request->display_url);
                $short_link = $this->getShortURL($viewPageUrl . 'payment-link');
                $this->CompanyProfileModel->updateTable('merchant_setting', 'merchant_id', $this->merchant_id, 'directpay_link', $short_link);
            }

            // $merchant_landing = $this->CompanyProfileModel->getTableRow('merchant_landing', 'merchant_id', $this->merchant_id);
            //  if ($merchant->display_url != $request->display_url || $merchant_landing->cf_id == null) {
            // SubDomainManagement::dispatch($this->merchant_id, $request->display_url, $merchant->display_url, 'create')->onQueue(env('SQS_SUBDOAMIN_MANAGEMENT'));
            //  }

            $this->updateBannerImages($request);
            // updating sitemaps
            //$this->createSitemap($merchant, $request, $phpArray, $sitemapindex);
            // end of updating sitemaps
            //update company policy, about_us, contact_us
            $this->CompanyProfileModel->UpdateRows(
                'merchant_landing',
                'merchant_id',
                $this->merchant_id,
                [
                    'terms_condition' => $request->terms_condition,
                    'banner_text' => $request->banner_text,
                    'banner_paragraph' => $request->banner_paragraph,
                    'cancellation_policy' => $request->cancellation_policy,
                    'about_us' => $request->about_us,
                    'overview' => $request->why_work_with_us_text,
                    'office_location' => $request->location,
                    'contact_no' => $request->contact_no,
                    'email_id' => $request->email_id,
                    'is_complete_company_page' => 1
                ]
            );

            return redirect()->back()->with('success', "Your changes have been saved. Click here to view your changes. <a href='$viewPageUrl' class='btn btn-xs blue' target='_blank'>View Pages</a>");
        }
    }

    public function setComplatedCompanyPage(Request $request)
    {
        if ($request->flag == 1) {
            $this->CompanyProfileModel->updateTable('merchant_landing', 'merchant_id', $this->merchant_id, 'is_complete_company_page', 1);
            $response['status'] = 1;
        } else {
            $response['status'] = 0;
        }
        echo json_encode($response);
    }

    function getShortURL($long_url)
    {
        try {
            $long_urls[] = $long_url;
            define('SWIPEZ_UTIL_PATH', getenv('SWIPEZ_BASE') . 'swipezutil');
            require SWIPEZ_UTIL_PATH . '/src/shorturl/SwipezShortURLWrapper.php';
//            $shortUrlWrap = new SwipezShortURLWrapper();
            $shortUrlWrap = new ShortUrl();
            $shortUrls = $shortUrlWrap->SaveUrl($long_urls);
            $shortUrl = $shortUrls[0];
            return $shortUrl;
        } catch (Exception $e) {
            app('sentry')->captureException($e);
        }
    }


    public function collect_payment_landingpage()
    {
        $data = $this->setBladeProperties('Collect payments', [], [3]);

        $user = null;

        if ($user == null) {
            $user = '[
                {
                    "name": "INVOICE",
                    "item_list": [
                        {
                            "title": "Create contract",
                            "desc": "Create your construction contracts. Contracts will be used to create invoices (G702/G703) as your project develops",
                            "link": "/merchant/contract/create"
                        },
                        {
                            "title": "Create invoice",
                            "desc": "Create and send invoices to your customers. Customize your invoice as per your business needs and add online payment collection options to your invoice",
                            "link": "/merchant/invoice/create"
                        },
                        {
                            "title": "Change orders (CO)",
                            "desc": "Create and send invoices to your customers. Customize your invoice as per your business needs and add online payment collection options to your invoice",
                            "link": "/merchant/order/create"
                        },
                        {
                            "title": "Create request for payment",
                            "desc": "Create and send request for payment to your vendors. Customize your request as per your business needs and add online payment collection options to your request for payment",
                            "link": "/merchant/subcontract/requestpayment/create"
                        }
                    ]
                }
            ]';
        }

        $return_arr = array();
        $row_array['name'] = 'Create invoice';
        $row_array['link'] = '/merchant/invoice/create';
        $return_arr[] = $row_array;
        $row_array['name'] = 'Create estimate';
        $row_array['link'] = '/merchant/invoice/create/estimate';
        $return_arr[] = $row_array;
        $row_array['name'] = 'Quick link';
        $row_array['link'] = '/merchant/directpaylink';
        $return_arr[] = $row_array;

        Redis::set('floatingMenuList', json_encode($return_arr));
        $mn = Redis::get('floatingMenuList');

        $mn1 = Redis::get('merchantMenuList' . $this->merchant_id);

        $page_data = json_decode($user, 1);

        $menu_list = '';
        if ($mn1 == null)
            $menu_list = $mn;
        else
            $menu_list = $mn1;


        $item_list = json_decode($menu_list, 1);

        if (count($item_list) > 6) {
            $d = count($item_list);
            $item_list = array_slice($item_list,  $d - 6);
        }
        //dd(count($item_list));
        $item_list = array_values(array_unique(array_reverse($item_list), SORT_REGULAR));

        $data['page_data'] = $page_data;
        $data['menu_list'] = $item_list;
        $data['talk_email_id'] = Session::get('email_id');
        $data['company_name'] = Session::get('company_name');
        $data['first_name'] = Session::get('display_name');
        $data['last_name'] = Session::get('last_name');
        $data['mobile'] = Session::get('mobile');

        return view('app/merchant/collect-payment/index', $data);
    }

    public function imports()
    {

        $data = $this->setBladeProperties('Imports', [], [14]);

        $user = '[
                {
                    "name": "Imports",
                    "item_list": [
                        {
                            "title": "Customers",
                            "desc": "Import customer data with a simple excel upload. Add all necessary attributes to your customer data structure and import customer with an excel.",
                            "link": "/merchant/customer/bulkupload"
                        },
                        {
                            "title": "Bill codes",
                            "desc": "Bulk upload bill codes for a project with an excel upload. Prepare bill code information in excel and associate with a project with a simple upload.",
                            "link": "/merchant/code/import"
                        },
                        {
                            "title": "Bulk upload invoices / estimates",
                            "desc": "Create and send invoices to your customers. Customize your invoice as per your business needs and add online payment collection options to your invoice",
                            "link": "/merchant/bulkupload/newupload"
                        },
                        {
                            "title": "Bulk upload Contracts",
                            "desc": "Bulk upload contract for a project with an excel upload. Prepare contract information in excel and associate with a project with a simple upload.",
                            "link": "/merchant/contract/import"
                        },
                        {
                            "title": "Bulk upload Change order",
                            "desc": "Bulk upload change order for a contract with an excel upload. Prepare order information in excel and associate with a contract with a simple upload.",
                            "link": "/merchant/change-order/import"
                        }

                    ]
                }
            ]';


        $page_data = json_decode($user, 1);


        $data['page_data'] = $page_data;
        return view('app/merchant/collect-payment/import', $data);
    }



    public function  pay_your_bills()
    {

        $data = Helpers::setBladeProperties('Pay your bills', [], [170]);





        return view('app/merchant/pay-your-bills/index', $data);
    }
}
