<?php

namespace App\Http\Livewire\Contract;

use App\ContractParticular;
use App\CsiCode;
use App\Libraries\Encrypt;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Particulars extends Component
{
    public $project_id = null;
    public $contract;
    public $contract_id = null;
    public $stopPolling = false;
    public $csi_codes = [];
    public $csi_code_json = '';

    public $particulars = [];
    public $fields = [];
    public $particular_json = '[]';
    public $pgroups = [];
    public $total = 0;

    protected $listeners = ['setProjectId' => 'setProjectId', 'setContract' => 'setContract', 'toggleStopPolling' => 'toggleStopPolling'];
    protected $billTypes = ['% Complete', 'Unit', 'Calculated'];

    /**
     * @var false|string
     */
    public $merchant_id;

    public function mount($particular = null, $project_id = 0)
    {
        $this->merchant_id = Encrypt::decode(Session::get('merchant_id'));
        if ($project_id > 0) {
            $this->fields = json_decode($particular, 1);
            foreach ($this->fields as $row) {
                if ($row['group'] != '') {
                    if (!in_array($row['group'], $this->pgroups)) {
                        $this->pgroups[] = $row['group'];
                    }
                }
                $original_contract_amount = str_replace(',', '', $row['original_contract_amount']);
                $this->total = $this->total + $original_contract_amount;
            }
            $this->total=number_format($this->total,2);
            $this->particular_json = json_encode($this->fields, true);
            $this->setProjectId($project_id);
        }
    }

    public function render()
    {
        $particular_column = array(
            'bill_code' => 'Bill Code',
            'bill_type' => 'Bill Type',
            'original_contract_amount' => 'Original Contract Amount',
            'retainage_percent' => 'Retainage %',
            'retainage_amount' => 'Retainage amount',
            'project' => 'Project id',
            'cost_code' => 'Cost Code',
            'cost_type' => 'Cost Type',
            'group' => 'Sub total group',
            'bill_code_detail' => 'Bill code detail'
        );

        return view('livewire.contract.particulars', compact('particular_column'));
    }

    public function setProjectId($project_id)
    {
        $this->project_id = $project_id;
        $this->csi_codes = $this->getCsiCodes()->toArray(); //dd($this->csi_codes);
        $this->csi_code_json = json_encode($this->csi_codes);
        $this->dispatchBrowserEvent('update-csi-codes', ['csi_codes' => $this->csi_codes, 'project_id' => $project_id]);
    }

    public function setContract($contract_id)
    {
        $this->contract_id = $contract_id;
        $this->contract = ContractParticular::find($contract_id);
    }

    public function updateParticulars()
    {
        //        dd($this->fields);
        if (!is_null($this->contract_id)) {
            $this->contract->update(['particulars' => $this->fields]);
            $this->dispatchBrowserEvent('particularsUpdated');
            $this->emit('setContract', $this->contract_id);
        }
    }

    public function getCsiCodes()
    {
        return CsiCode::where('project_id', $this->project_id)
            ->where('merchant_id', $this->merchant_id)
            ->where('is_active', 1)
            ->get();
    }

    public function toggleStopPolling()
    {
        $this->stopPolling = !$this->stopPolling;
    }
}
