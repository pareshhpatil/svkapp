<?php

namespace App\Http\Controllers;
use App\Model\ParentModel;
use Illuminate\Http\Request;

use App\Libraries\Helpers;
use App\Libraries\Encrypt;
use Illuminate\Support\Facades\Session;
use Exception;
use Validator;
use Illuminate\Validation\Rule;
use App\Model\RigionSetting;

class RegionSettingController extends Controller
{
    private $parentModel;
    //private $merchant_id = null;
    private $user_id = null;
    private $regionModel;
    public function __construct()
    {

    $this->parentModel = new ParentModel();
        $this->regionModel = new RigionSetting();
       // $this->merchant_id = Encrypt::decode(Session::get('merchant_id'));
        $this->user_id = Encrypt::decode(Session::get('userid'));
      

    }
    public function index()
    {
       
        $title = 'Region settings';
        $data = Helpers::setBladeProperties($title,  ['units', 'template'] ,  []);
        
       
        $curr_list= $this->parentModel->getMerchantCurrencyList(Session::get('currency'));
        $existdata= $this->regionModel->getRegionSetting($this->user_id);
        $data['default']=json_decode($existdata,1);
        $data['currency_list'] = json_decode($curr_list,1);
      
        $data['datatablejs'] = 'table-no-export';
        return view('app/merchant/region-setting/index', $data);
    }
    public function saveChanges(Request $request)
    {
        $this->regionModel->updateRegion($request,$this->user_id);
        Session::put('default_timezone',  $request->selecttimezone);
        Session::put('default_currency',  $request->selectcurrency);
        Session::put('default_date_format',  $request->dateformat);
        Session::put('default_time_format',  $request->timeformat);
        return redirect('/merchant/regionSettings')->with('success', "Region setting has been updated");
    }
}
