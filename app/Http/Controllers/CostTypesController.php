<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AppController;
use App\Model\CostType;
use App\Model\Master;
use App\Model\InvoiceFormat;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Validator;
use App\Libraries\Helpers;
use App\Libraries\Encrypt;
use Illuminate\Support\Facades\Session;

class CostTypesController extends AppController
{
    protected $userID;
    protected $merchantID;

    public function __construct()
    {
        $this->merchantID = Encrypt::decode(Session::get('merchant_id'));
        $this->userID = Encrypt::decode(Session::get('userid'));
    }

    /***
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $title = 'Cost Type list';
        $data = Helpers::setBladeProperties($title, ['units', 'template'], []);

        $CostTypes = (new CostType())->getCostTypeList();

        $data['costTypes'] = $CostTypes;
        $data['datatablejs'] = 'table-no-export';

        return view('app/merchant/cost-types/index', $data);
    }

    /**
     * Show the form for creating a new cost type.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function getCreate()
    {
        $title = 'Create Cost Type';
        $data = Helpers::setBladeProperties($title, ['units', 'template'], []);

        return view('app/merchant/cost-types/create', $data);
    }

    /**
     * Master a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function createCostTypes(Request $request)
    {
        try {
            $rules = [
                'name' => 'required',
                'abbrevation' => 'unique:cost_types|max:2'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors($validator->messages());
            }

            $abbrevation = $request->get('abbrevation');
            if (empty($abbrevation)) {
                $abbrevation = Str::upper(substr($request->get('name'), 0, 1));
            }

            //Check If abbrevation already exists
            $exists = CostType::query()
                                ->where('abbrevation', $abbrevation)
                                ->exists();

            if($exists) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors([
                        'abbrevation' => 'The abbrevation has already been taken.'
                    ]);
            }

            $request->merge(['abbrevation' => $abbrevation]);

            $model = new CostType();

            $model->saveCostType($request, $this->userID);

            return redirect()->route('merchant.cost-types.index')->with('success', "Cost type has been created");
        } catch (Exception $exception) {
            Log::error($exception->getMessage());

            return redirect()->route('merchant.cost-types.index')->with('error', "Something went wrong!");
        }
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function getUpdate($id)
    {
        $title = 'Update Cost Type';
        $data = Helpers::setBladeProperties($title, ['units', 'template'], []);

        $CostType = CostType::find($id);

        $data['costType'] = $CostType;

        return view('app/merchant/cost-types/edit', $data);
    }


    public function updateCostType($id, Request $request)
    {
        try {
            $rules = [
                'name' => 'required',
                'abbrevation' => 'max:2'
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors($validator->messages());
            }

            $abbrevation = $request->get('abbrevation');
            if (empty($abbrevation)) {
                $abbrevation = Str::upper(substr($request->get('name'), 0, 1));
            }


            //Check If abbrevation already exists
            $exists = CostType::query()
                ->where('id', '!=', $id)
                ->where('abbrevation', $abbrevation)
                ->exists();

            if($exists) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors([
                        'abbrevation' => 'The abbrevation has already been taken.'
                    ]);
            }

            $request->merge(['abbrevation' => $abbrevation]);

            $model = new CostType();

            $model->updateCostType($id, $request, $this->userID);

            return redirect()->route('merchant.cost-types.index')->with('success', "Cost type has been updated");
        } catch (Exception $exception) {
            Log::error($exception->getMessage());

            return redirect()->route('merchant.cost-types.index')->with('error', "Something went wrong!");
        }
    }
}
