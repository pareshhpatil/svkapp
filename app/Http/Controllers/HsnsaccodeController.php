<?php

namespace App\Http\Controllers;

use App\Model\Hsnsaccode;
use Illuminate\Http\Request;
use Exception;
use Validator;
use App\Libraries\Helpers;
use App\Libraries\Encrypt;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;

class HsnsaccodeController extends Controller
{
    
    private $user_id = null;
    
    public function __construct()
    {
        $this->user_id = Encrypt::decode(Session::get('userid'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'HSN/SAC code list';
        $data = Helpers::setBladeProperties($title,  [] ,  []);
        $hsn_sac_codes = Hsnsaccode::select('id','code','type','gst','description')->where('is_active',1)->take(50)->get();
        foreach ($hsn_sac_codes as $ck=>$code) {
            $hsn_sac_codes[$ck]['encrypted_id'] = Encrypt::encode($code['id']);
        }
        $data['hsn_sac_codes'] = $hsn_sac_codes;
        $data['datatablejs'] = 'table-no-export';
        return view('app/merchant/hsn-sac-code/index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Create HSN/SAC code';
        $data = Helpers::setBladeProperties($title,  [] ,  []);
        return view('app/merchant/hsn-sac-code/create', $data);
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
            'code' => 'required|numeric|digits_between:3,8',
            'type' => 'required',
            'gst' => 'nullable|numeric',
            'description' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        } else {
            $saveCode = $request->all();
            $Hsnsaccode = new Hsnsaccode();
            $savedQuery = $Hsnsaccode->saveCode($saveCode);
            return redirect('merchant/hsn-sac-code/index')->with('success',"HSN/SAC code has been created");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Hsnsaccode  $hsnsaccode
     * @return \Illuminate\Http\Response
     */
    public function show(Hsnsaccode $hsnsaccode)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Hsnsaccode  $hsnsaccode
     * @return \Illuminate\Http\Response
     */
    public function edit($hsnsaccode)
    {
        $title = 'Update HSN/SAC code';
        $data = Helpers::setBladeProperties($title,  [] ,  []);
        $hsnsaccode_id = Encrypt::decode($hsnsaccode);
        $getCode = Hsnsaccode::find($hsnsaccode_id);
        $data['hsnsaccode'] = $getCode;
        return view('app/merchant/hsn-sac-code/edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Hsnsaccode  $hsnsaccode
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Hsnsaccode $hsnsaccode)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|numeric|digits_between:3,8',
            'type' => 'required',
            'gst' => 'nullable|numeric',
            'description' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        } else {
            //$updateCode = $request->except('_token');
            $updateCode['code'] = $request['code'];
            $updateCode['type'] = $request['type'];
            $updateCode['gst'] = $request['gst'];
            $updateCode['description'] = $request['description'];
            $Hsnsaccode = new Hsnsaccode();
            $updatedQuery = $Hsnsaccode->updateCode($updateCode,$request['id']);
            return redirect('merchant/hsn-sac-code/index')->with('success',"HSN/SAC code has been updated");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Hsnsaccode  $hsnsaccode
     * @return \Illuminate\Http\Response
     */
    public function destroy($hsnsaccode)
    {
        if($hsnsaccode) {
            $hsnsaccodeID = Encrypt::decode($hsnsaccode);
            $deleteCode = Hsnsaccode::where('id', $hsnsaccodeID)->update(['is_active' => 0]);
            if ($deleteCode){
                return redirect('merchant/hsn-sac-code/index')->with('success',"HSN/SAC code has been deleted");
            }else{
                return redirect('merchant/hsn-sac-code/index')->with('error',"HSN/SAC code can not be deleted");
            }
        } else {
            return redirect('merchant/hsn-sac-code/index')->with('error',"HSN/SAC code can not be deleted");
        }
    }
}
