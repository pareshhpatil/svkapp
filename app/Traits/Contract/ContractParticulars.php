<?php

namespace App\Traits\Contract;

use App\CsiCode;

trait ContractParticulars
{
    public $particulars = [];
    public $csi_codes = [];
    public $groups = [];
    public $total = 0;

    public $row = [
        'bill_code' => null,
        'bill_type' => null,
        'original_contract_amount' => null,
        'retainage_percent' => null,
        'retainage_amount' => null,
        'project' => null,
        'project_code' => null,
        'cost_code' => null,
        'cost_type' => null,
        'group' => null,
        'bill_code_detail' => null
    ];

    public $particular_column = [
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
    ];

    public $billTypes = ['% Complete', 'Unit', 'Calculated'];

    public $bill_code_detail = ['Yes', 'No'];

    public function getRow(){
        return $this->row;
    }

    public function initializeParticulars(){
        $this->particulars[] = $this->getRow();
    }

    public function getBillCodes($project_id)
    {
        $this->csi_codes = CsiCode::where('project_id', $this->project_id)
            ->where('merchant_id', $this->merchant_id)
            ->where('is_active', 1)
            ->get()->toArray();

        $this->dispatchBrowserEvent('update-csi-codes', ['csi_codes' => $this->csi_codes, 'project_id' => $this->project_id, 'project_name' => $this->project_name]);
    }

    public function calculateTotal(){
        foreach ($this->particulars as $row) {
            if($row['bill_code'] != '') {
                if ($row['group'] != '') {
                    if (!in_array($row['group'], $this->groups)) {
                        $this->groups[] = $row['group'];
                    }
                }
                $original_contract_amount = str_replace(',', '', $row['original_contract_amount']);
                $this->total = $this->total + $original_contract_amount;
            }
        }
    }
}