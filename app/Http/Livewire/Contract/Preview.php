<?php

namespace App\Http\Livewire\Contract;

use App\Libraries\Encrypt;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use App\ContractParticular;
use Log;
class Preview extends Component
{
    public $particulars = null;
    public $contract;

    public $project_id = null;
    public $contract_id = null;
    public $csi_codes =[];

    public $fields = [];


    protected $listeners = ['setContract' => 'setContract','setPreview' => 'setPreview'];
    /**
     * @var false|string
     */
    public $merchant_id;

    public function mount()
    {
        $this->merchant_id = Encrypt::decode(Session::get('merchant_id'));
    }

    public function setContract($contract_id){
        $this->contract_id = $contract_id;
        $this->contract = ContractParticular::find($contract_id);
    }
    public function setPreview($contract_id){
        
        $this->contract_id = $contract_id;
        $this->contract = ContractParticular::find($contract_id);
        Log::error($this->contract);
    }

    public function render()
    {
        $particular_column = array('bill_code' => 'Bill Code', 'bill_type' => 'Bill Type', 'original_contract_amount' => 'Original Contract Amount', 'retainage_percent' => 'Retainage %', 'retainage_amount' => 'Retainage amount', 'project_code' => 'Project id', 'cost_code' => 'Cost Code', 'cost_type' => 'Cost Type', 'group' => 'Sub total group', 'bill_code_detail' => 'Bill code detail');
        return view('livewire.contract.preview', compact('particular_column'));

    }
}
