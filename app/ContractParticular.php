<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractParticular extends Model
{
    use HasFactory;

    protected $primaryKey = 'contract_id';

    protected $table = 'contract';

    protected $fillable = [
        'contract_code',
        'merchant_id',
        'version',
        'customer_id',
        'project_id',
        'contract_amount',
        'contract_date',
        'bill_date',
        'billing_frequency',
        'particulars',
        'created_by',
        'last_update_by',
        'created_date',
        'status'
    ];

    protected $rules = [

    ];

    protected $casts = [
//        'particulars' => 'array'
    ];

    public $timestamps = false;

    public static $row = [
        'bill_code' => null,
        'calculated_perc' => null,
        'calculated_row' => null,
        'description' => null,
        'introw' => 0,
        'bill_type' => null,
        'original_contract_amount' => null,
        'retainage_percent' => null,
        'retainage_amount' => null,
        'project' => null,
        'project_code' => null,
        'cost_code' => null,
        'cost_type' => null,
        'group' => null,
        'bill_code_detail' => null,
        'show' => false
    ];

    public static $particular_column = [
        'bill_code' => [ 'title'=>'Bill Code', 'type' => 'select' ],
        'bill_type' => [ 'title'=>'Bill Type', 'type' => 'select' ],
        'original_contract_amount' => [ 'title'=> 'Original Contract Amount', 'type' => 'input', 'visible' => false ],
        'retainage_percent' => [ 'title'=> 'Retainage %', 'type' => 'input', 'visible' => false ] ,
        'retainage_amount' => [ 'title'=> 'Retainage amount', 'type' => 'input' ] ,
        'project' => [ 'title'=> 'Project id', 'type' => 'input', 'visible' => false ] ,
        'cost_code' => [ 'title'=> 'Cost Code', 'type' => 'input', 'visible' => false ] ,
        'cost_type' => [ 'title'=> 'Cost Type', 'type' => 'input', 'visible' => false ] ,
        'group' => [ 'title'=> 'Sub total group', 'type' => 'select' ] ,
        'bill_code_detail' => [ 'title'=> 'Bill code detail', 'type' => 'select' ]
    ];


    public static function initializeParticulars(): array
    {
        $particulars = [];
        $particulars[] = self::$row;
        $particulars[] = self::$row;
        return $particulars;
    }

    public function calculateTotal(){
        $total =0;
        $groups = [];
        $particulars = json_decode($this->particulars??[]);
        if(!empty($particulars)) {
            foreach ($particulars as $key => $row) {
                if ($row['bill_code'] != '') {
                    if ($row['group'] != '') {
                        if (!in_array($row['group'], $groups)) {
                            $groups[] = $row['group'];
                        }
                    }
                    $total = $total + $row['original_contract_amount'];
                    $particulars[$key]['original_contract_amount'] = str_replace(',', '', $row['original_contract_amount']);
                }
            }
        }
        return [$total, $groups, $particulars];
    }

}
