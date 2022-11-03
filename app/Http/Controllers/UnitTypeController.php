<?php

namespace App\Http\Controllers;

use App\Model\UnitType;
use Illuminate\Http\Request;
use App\Libraries\Helpers;
use App\Libraries\Encrypt;
use Illuminate\Support\Facades\Session;
use Exception;
use Validator;
use Illuminate\Validation\Rule;

class UnitTypeController extends Controller
{
    private $merchant_id = null;
    private $user_id = null;
    
    public function __construct()
    {
        $this->merchant_id = Encrypt::decode(Session::get('merchant_id'));
        $this->user_id = Encrypt::decode(Session::get('userid'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Unit type list';
        $data = Helpers::setBladeProperties($title,  ['units', 'template'] ,  []);
        $getUnits = UnitType::select('id','name')->where('merchant_id',$this->merchant_id)->where('is_active', 1)->get();
        foreach ($getUnits as $uk=>$unit) {
            $getUnits[$uk]['encrypted_id'] = Encrypt::encode($unit['id']);
        }
        $data['units'] = $getUnits;
        $data['datatablejs'] = 'table-no-export';
        return view('app/merchant/unit-type/index', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required','min:2','max:50',Rule::unique('merchant_unit_type','name')
            ->where('merchant_id',$this->merchant_id)->where('is_active',1)
        ]]);

        if ($validator->fails()) {
            if(isset($request->response_type) && ($request->response_type == 'json')) {
                $haserror['status'] = 0;
                $haserror['error'] = response()->json(['error'=>$validator->errors()->all()]);
                echo json_encode($haserror);
            } else {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            //return redirect('merchant/unit-type/index')->withErrors($validator);
        } else {
            $saveUnit['name'] = ucwords(strtolower($request->name));
            $saveUnit['merchant_id'] = $this->merchant_id;
            $unit = new UnitType();
            $savedQuery = $unit->saveUnitType($saveUnit);

            if(isset($request->response_type) && ($request->response_type == 'json')) {
                $response['name'] = $saveUnit['name'];
                $response['id'] = $savedQuery->id;
                $response['status'] = 1;
                echo json_encode($response);
            } else {
                return redirect('merchant/unit-type/index')->with('success',"Unit type has been created");
            }
        }
    }

     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UnitType  $unitType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UnitType $unitType)
    {
        $unit_id = Encrypt::decode($request->id);
        $validator = Validator::make($request->all(), [
            'name' => ['required','min:2','max:50',Rule::unique('merchant_unit_type','name')
            ->where('merchant_id',$this->merchant_id)->where('is_active',1)->ignore($unit_id,'id')
        ]]);

        if ($validator->fails()) {
            return redirect('merchant/unit-type/index')->withErrors($validator);
        } else {
            $saveUnit['name'] = ucwords(strtolower($request->name));
            $saveUnit['merchant_id'] = $this->merchant_id;
            $update = UnitType::where('id', Encrypt::decode($request->id))->update($saveUnit);
            if($update) {
                return redirect('merchant/unit-type/index')->with('success',"Unit type has been updated");
            } else {
                return redirect('merchant/unit-type/index')->with('error',"Unit type can not be updated");
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\UnitType  $unitType
     * @return \Illuminate\Http\Response
     */
    public function destroy($unitType)
    {
        if($unitType) {
            $unitTypeId = Encrypt::decode($unitType);
            //$deleteUnit = UnitType::where('id', $unitTypeId)->delete();
            $deleteUnit = UnitType::where('id', $unitTypeId)->update(['is_active' => 0]);
            if ($deleteUnit){
                return redirect('merchant/unit-type/index')->with('success',"Unit type has been deleted");
            }else{
                return redirect('merchant/unit-type/index')->with('error',"Unit type can not be deleted");
            }
        } else {
            return redirect('merchant/unit-type/index')->with('error',"Unit type can not be deleted");
        }
    }
}
