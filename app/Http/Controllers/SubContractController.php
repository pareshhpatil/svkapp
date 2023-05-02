<?php

namespace App\Http\Controllers;

use App\Constants\Models\IColumn;
use App\CsiCode;
use App\Http\Controllers\API\APIController;
use App\Libraries\Encrypt;
use App\Libraries\Helpers;
use App\Model\Master;
use App\Model\Merchant\CostType;
use App\Model\SubContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Validator;

class SubContractController extends Controller
{
    private $sub_contract_model = null;
    private $masterModel = null;
    private $merchant_id = null;
    private $user_id = null;

    public function __construct()
    {
        $this->sub_contract_model = new SubContract();
        $this->masterModel = new Master();
        $this->merchant_id = Encrypt::decode(Session::get('merchant_id'));
        $this->user_id = Encrypt::decode(Session::get('userid'));
        $this->apiController = new APIController();
    }

    /***
     * @return \Illuminate\Contracts\View\View
     * @author Nitish
     */
    public function index(Request $request)
    {
        $title = 'Sub Contracts List';

        $data = Helpers::setBladeProperties($title,  [],  [5, 179]);

        $list = $this->sub_contract_model->getSubContractList($this->merchant_id);
        foreach ($list as $ck => $row) {
            $list[$ck]->encrypted_id = Encrypt::encode($row->sub_contract_id);
        }
        $data['list'] = $list;

        $data['datatablejs'] = 'table-no-export';
        $data['auth_user_role'] = Session::get('user_role');

        return view('app/merchant/sub-contract/index', $data);
    }

    /**
     * Show the form for creating a new sub_contract.
     *
     * @return \Illuminate\Contracts\View\View
     * @author Nitish
     */
    public function create($step = 1, $subContractID = null)
    {
        $title = 'Create sub-contract';
        
        $userRole = Session::get('user_role');

        $data = Helpers::setBladeProperties($title, ['expense', 'contract2', 'product', 'template', 'invoiceformat2'], [3, 179]);

        $data['step'] = $step;
        $data['sub_contract_id'] = $subContractID;
        $data['project_id'] = '';

        $data['sub_contract'] = null;

        $data['vendor_list'] = $this->masterModel->getVendorList($this->merchant_id);
        $data['project_list'] = $this->masterModel->getProjectList($this->merchant_id, [], $userRole);
        $data['needValidationOnStep2'] = true;
        $data['particulars'] = [];

        if($step == 2) {
            /** @var SubContract $SubContract*/
            $SubContract = SubContract::find(Encrypt::decode($subContractID));
            $data['SubContract'] = $SubContract;
            $data = $this->step2Data($data, $SubContract, $SubContract->project_id ?? '', $step);
            
            $data['project_id'] = $SubContract->project_id;
            $data['project'] = $this->masterModel->getTableRow('project', 'id', $SubContract->project_id);
        }

        return view('app/merchant/sub-contract/create', $data);
    }
    
    public function store(Request $request)
    {
        $step = $request->get('step');

        switch ($step) {
            case 1:
                $data = $this->step1Store($request);

                if($data['has_validation_errors'] === true) {
                    return redirect()->back()->withInput()->withErrors($data['errors']);
                }

                $step++;
                break;
            case 2:
                break;
        }

        return redirect()->route('merchant.subcontract.create', ['step' => $step, 'subcontractID' => Encrypt::encode($data['sub_contract_id'])]);

    }

    public function step1Store(Request $request)
    {
        $rules = [
            'vendor_id' => 'required',
            'project_id' => 'required',
            'title' => 'required',
            'start_date' => 'required',
            'end_date' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return [
                'has_validation_errors' => true,
                'errors' => $validator
            ];
        }

        $subContractID = DB::table('sub_contract')
                            ->insertGetId([
                                'merchant_id' => $this->merchant_id,
                                'vendor_id' => $request->get('vendor_id'),
                                'project_id' => $request->get('project_id'),
                                'title' => $request->get('title'),
                                'start_date' => Helpers::sqlDate($request->get('start_date')),
                                'end_date' => Helpers::sqlDate($request->get('end_date')),
                                'default_retainage' => $request->get('default_retainage'),
                                'sub_contract_code' => $request->get('sub_contract_code'),
                                'status' => 1,
                                'sign' => $request->get('sign'),
                                'description' => $request->get('description'),
                                'created_by' => $this->user_id,
                                'last_update_by' => $this->user_id,
                                'created_date' => date('Y-m-d H:i:s'),
                                'last_update_date' => date('Y-m-d H:i:s')
                            ]);

        return [
            'has_validation_errors' => false,
            'sub_contract_id' => $subContractID
        ];
    }

    /**
     * @param $data
     * @param SubContract $SubContract
     * @param $project_id
     * @param $step
     * @return mixed
     */
    public function step2Data($data, $SubContract, $project_id, $step)
    {
        [$total, $groups, $particulars] = $SubContract->calculateTotal();

        if ($step == 3) {
            $data['particulars'] = ($SubContract != null && !empty($particulars)) ? $particulars : [];
        } else {
            $data['particulars'] = ($SubContract != null && !empty($particulars)) ? $particulars : SubContract::initializeParticulars($project_id);
        }
        $data['bill_codes'] = $this->getBillCodes($SubContract->project_id);
        $data['cost_types'] = $this->getCostTypes();

        $merchant_cost_types_array = $this->getKeyArrayJson($data['cost_types'], 'value');
        $data['cost_types_array'] = $merchant_cost_types_array;
        $data['csi_codes_array'] = $this->getKeyArrayJson($data['bill_codes'], 'value');

        $data['project_id'] = $SubContract->project_id;
        $data['total'] = $total;
        $data['groups'] = $groups;
        $data['row'] = SubContract::$row;

        return $data;
    }

    /**
     * @param $project_id
     * @return mixed
     */
    public function getBillCodes($project_id)
    {
        return CsiCode::where('project_id', $project_id)
            ->select(['id as value', DB::raw('CONCAT(code, " | ", title) as label'), 'description'])
            ->where('merchant_id', $this->merchant_id)
            ->where('is_active', 1)
            ->get()->toArray();
    }

    public function getCostTypes(): array
    {
        return CostType::where(IColumn::MERCHANT_ID, $this->merchant_id)
            ->select(['id as value', DB::raw('CONCAT(abbrevation, " - ", name) as label')])
            ->get()->toArray();
    }

    public function updateParticulars(Request $request)
    {
        $id = Encrypt::decode($request->link);
        $formData = $request->form_data;
        $SubContract = SubContract::find($id);

        $particulars = json_decode($formData, true);
        $particulars = json_decode($particulars, true);
        $array = ['bill_code', 'bill_type', 'amount', 'retainage_percent', 'retainage_amount', 'project', 'project_code', 'cost_code', 'cost_type', 'task_number', 'group', 'bill_code_detail'];
        foreach ($particulars as $k => $v) {
            foreach ($array as $a) {
                if (isset($v['show' . $a])) {
                    unset($particulars[$k]['show' . $a]);
                }
            }
        }
        $SubContract->update(['particulars' => json_encode($particulars), 'sub_contract_amount' => $request->sub_contract_amount, 'last_update_by' => $this->user_id]);

        return response()->json([
            'message' => 'Particulars saved properly'
        ], 200);
    }

    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\Contracts\View\View
     * @author Nitish
     */
    public function edit($step = 1, $subContractID = null)
    {
        $title = 'Update sub-contract';

        $userRole = Session::get('user_role');

        $data = Helpers::setBladeProperties($title, ['expense', 'contract2', 'product', 'template', 'invoiceformat2'], [3, 179]);

        if (!empty($subContractID)) {
            $subContract = SubContract::find(Encrypt::decode($subContractID));

            $data['sub_contract'] = $subContract;
        }

        $data['step'] = $step;
        $data['sub_contract_id'] = $subContractID;

        $data['vendor_list'] = $this->masterModel->getVendorList($this->merchant_id);
        $data['project_list'] = $this->masterModel->getProjectList($this->merchant_id, [], $userRole);
        $data['needValidationOnStep2'] = true;
        $data['particulars'] = [];

        if($step == 2) {
            /** @var SubContract $SubContract*/
            $SubContract = SubContract::find(Encrypt::decode($subContractID));
            $data['SubContract'] = $SubContract;
            $data = $this->step2Data($data, $SubContract, $SubContract->project_id ?? '', $step);

            $data['project'] = $this->masterModel->getTableRow('project', 'id', $SubContract->project_id);
        }

        return view('app/merchant/sub-contract/edit', $data);
    }

    public function update(Request $request)
    {
        $step = $request->get('step');

        switch ($step) {
            case 1:
                $data = $this->step1Update($request);

                if($data['has_validation_errors'] === true) {
                    return redirect()->back()->withInput()->withErrors($data['errors']);
                }

                $step++;
                break;
            case 2:
                break;
        }

        return redirect()->route('merchant.subcontract.update', ['step' => $step, 'subcontractID' => $request->sub_contract_id]);

    }

    public function step1Update(Request $request)
    {
        $rules = [
            'vendor_id' => 'required',
            'project_id' => 'required',
            'title' => 'required',
            'start_date' => 'required',
            'end_date' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return [
                'has_validation_errors' => true,
                'errors' => $validator
            ];
        }

        DB::table('sub_contract')
            ->where('sub_contract_id', Encrypt::decode($request->sub_contract_id))
            ->update([
                'merchant_id' => $this->merchant_id,
                'vendor_id' => $request->get('vendor_id'),
                'project_id' => $request->get('project_id'),
                'title' => $request->get('title'),
                'start_date' => Helpers::sqlDate($request->get('start_date')),
                'end_date' => Helpers::sqlDate($request->get('end_date')),
                'default_retainage' => $request->get('default_retainage'),
                'sub_contract_code' => $request->get('sub_contract_code'),
                'status' => $request->get('status'),
                'sign' => $request->get('sign'),
                'description' => $request->get('description'),
                'last_update_by' => $this->user_id,
                'last_update_date' => date('Y-m-d H:i:s')
            ]);

        return [
            'has_validation_errors' => false
        ];
    }

    public function delete($subContractID)
    {
        if ($subContractID) {
            $id = Encrypt::decode($subContractID);
            $this->masterModel->deleteTableRow('sub_contract', 'sub_contract_id', $id, $this->merchant_id, $this->user_id);
            return redirect('merchant/sub-contracts')->with('success', "Record has been deleted");
        } else {
            return redirect('merchant/sub-contracts')->with('error', "Record code can not be deleted");
        }
    }

    /**
     * @param $array
     * @param $key
     * @return false|string
     */
    private function getKeyArrayJson($array, $key)
    {
        $data = [];
        foreach ($array as $row) {
            $data[$row[$key]] = $row;
        }
        return json_encode($data);
    }

}
