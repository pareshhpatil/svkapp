<?php

namespace App\Http\Livewire\Contract;

use App\ContractParticular;
use App\Http\Controllers\ContractController;
use App\Libraries\Encrypt;
use App\Libraries\Helpers;
use App\Model\Master;
use App\Project;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Validator;

class Information extends Component
{
    public $project_id = null;
    public $contract;
    public $contract_amount = 0;
    public $contract_code = null;
    public $merchant_id = null;
    public $customer_id = null;
    public $contract_date = null;
    public $bill_date = null;
    public $billing_frequency = 2;
    public $particulars = [];
    public $user_id;
    public $version = 'v4';
    public $contract_id = null;


    public $masterModel;

    public function mount($contract_id = 0)
    {
        $this->masterModel = new Master();
        if ($contract_id > 0) {
            $this->contract_id = $contract_id;
            $this->contract = $this->masterModel->getTableRow('contract', 'contract_id', $this->contract_id);
            $this->project_id = $this->contract->project_id;
            $this->contract = (array) $this->contract;
            $this->contract_code = $this->contract['contract_code'];
            $this->contract_date = Helpers::htmlDate($this->contract['contract_date']);
            $this->bill_date = Helpers::htmlDate($this->contract['bill_date']);
        }


        $this->merchant_id = Encrypt::decode(Session::get('merchant_id'));
        $this->user_id = Encrypt::decode(Session::get('userid'));
    }

    public function render()
    {
        $project = null;

        if (!is_null($this->project_id)) {
            $project = Project::where('id', $this->project_id)->where('project.is_active', 1)
                ->join('customer', 'customer.customer_id', 'project.customer_id')
                ->select([
                    'id', 'project_id',  'project_name', 'project.customer_id',
                    'company_name', 'sequence_number', 'customer.customer_code',
                    DB::raw("concat(customer.customer_code, ' | ' ,company_name) as customer_company_code"),
                    'customer.email', 'customer.mobile', DB::raw("concat(first_name,' ', last_name) as name")
                ])
                ->first();
            $this->customer_id = $project->customer_id;
            $this->emit('setProjectId', $this->project_id);
        }
        $userRole = Session::get('user_role');

        if($userRole == 'Admin') {
            $projectPrivilegesIDs = ['all' => 'full'];
        } else {
            $projectPrivilegesIDs = json_decode(Redis::get('project_privileges_' . $this->user_id), true);
        }

        $whereProjectIDs = [];
        foreach ($projectPrivilegesIDs as $key => $privilegesID) {
            if($privilegesID == 'full') {
                $whereProjectIDs[] = $key;
            }
        }
        $project_list = $this->masterModel->getProjectList($this->merchant_id, $whereProjectIDs, $userRole);
        return view('livewire.contract.information', compact('project_list', 'project'));
    }

    public function store()
    {
        $validator = Validator::make([
            'bill_date' => $this->bill_date,
            'contract_date' => $this->contract_date
        ], [
            'bill_date' => 'required',
            'contract_date' => 'required'
        ]);
        //dd($this->bill_date, $this->contract_date);

        if (!$validator->fails()) {
            if (is_null($this->contract))
                $this->contract = ContractParticular::create($this->getContractDetails());
            else
                $this->contract->update($this->getContractDetails());
            $this->contract_id = $this->contract->contract_id;
            $this->emit('setContract', $this->contract_id);
        }
    }

    private function getContractDetails(): array
    {
        return [
            'contract_code' => $this->contract_code,
            'merchant_id' => $this->merchant_id,
            'customer_id' => $this->customer_id,
            'version' => $this->version,
            'project_id' => $this->project_id,
            'contract_amount' => $this->contract_amount,
            'contract_date' => Helpers::sqlDate($this->contract_date),
            'bill_date' => Helpers::sqlDate($this->bill_date),
            'billing_frequency' => $this->billing_frequency,
            'particulars' => json_encode($this->particulars),
            'created_by' => $this->user_id,
            'last_update_by' => $this->user_id,
            'created_date' => date('Y-m-d H:i:s')
        ];
    }
}
