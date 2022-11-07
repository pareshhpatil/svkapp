<?php

namespace App\Http\Livewire\Contract;

use App\ContractParticular;
use App\Libraries\Encrypt;
use App\Libraries\Helpers;
use App\Model\Master;
use App\Project;
use App\Traits\Contract\ContractParticulars;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Validator;

class Create extends Component
{
    use ContractParticulars;

    public $allSteps = true;
    public $step = 1;
    public $contract_id = null;
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
    public $version = 'v5';
    public $project = null;
    public $project_name = null;
    public $title = null;

    public $masterModel;


    public function mount($contract_id = 0)
    {
        $this->masterModel = new Master();
        $this->merchant_id = Encrypt::decode(Session::get('merchant_id'));
        $this->user_id = Encrypt::decode(Session::get('userid'));
        if($this->contract_id){
            $this->contract = ContractParticular::find($this->contract_id);
            $this->setContract();
        }else {
            $this->initializeParticulars();
        }
    }
    public function render()
    {
        if (!is_null($this->project_id)) {
            $this->getProject();
            $this->getBillCodes($this->project_id);
        }

        $project_list = $this->masterModel->getProjectList($this->merchant_id);
        return view('livewire.contract.create', compact('project_list'));
    }

    public function getProject(){

        $this->project = Project::where('id', $this->project_id)->where('project.is_active', 1)
            ->join('customer', 'customer.customer_id', 'project.customer_id')
            ->select([
                'id', 'project_id',  'project_name', 'project.customer_id',
                'company_name', 'sequence_number',
                DB::raw("concat(project.customer_id, ' | ' ,company_name) as customer_company_code"),
                'customer.email', 'customer.mobile', DB::raw("concat(first_name,' ', last_name) as name")
            ])
            ->first();

        $this->project_name = $this->project->project_name;
        $this->customer_id = $this->project->customer_id;
        $this->emit('setProjectId', $this->project_id);
    }

    public function setContract(){

        $this->project_id = $this->contract->project_id;
        $this->contract_amount = $this->contract->contract_amount;
        $this->contract_code = $this->contract->contract_code;
        $this->contract_date = Helpers::htmlDate($this->contract['contract_date']);
        $this->bill_date = Helpers::htmlDate($this->contract['bill_date']);
        $this->billing_frequency = $this->contract->billing_frequency;
        $this->particulars = json_decode($this->contract->particulars,1);

        $this->getBillCodes($this->project_id);
        $this->calculateTotal();
    }

    public function storeContract()
    {
        $this->validate($this->informationRules());

        if (is_null($this->contract))
            $this->contract = ContractParticular::create($this->getContractDetails());
        else
            $this->contract->update($this->getContractDetails());
        $this->contract_id = $this->contract->contract_id;
//        $this->emit('setContract', $this->contract_id);

        $this->step++;
    }

    public function informationRules(){
        return [
            'contract_code' => 'required',
            'merchant_id' => 'required',
            'version' => 'required',
            'contract_amount' => 'required',
            'customer_id' => 'required',
            'project_id' => 'required',
            'contract_date' => 'required',
            'bill_date' => 'required',
            'billing_frequency' => 'required',
        ];
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
