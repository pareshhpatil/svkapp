<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * App\Model\SubContract
 *
 * @property mixed                           $sub_contract_id
 * @property mixed                           $merchant_id
 * @property mixed                           $vendor_id
 * @property mixed                           $project_id
 * @property string                          $title
 * @property \Illuminate\Support\Carbon|null $start_date
 * @property \Illuminate\Support\Carbon|null $end_date
 * @property string                          $default_retainage
 * @property string                          $contract_code
 * @property int                             $status
 * @property mixed                           $sign
 * @property mixed                           $description
 * @property mixed                           $attachments
 * @property mixed                           $created_by
 * @property mixed                           $last_updated_by
 * @property \Illuminate\Support\Carbon|null $created_date
 * @property \Illuminate\Support\Carbon|null $last_updated_date
 * @method static Builder|SubContract whereCreatedAt($value)
 * @method static Builder|SubContract whereId($value)
 * @method static Builder|SubContract whereName($value)
 * @mixin \Eloquent
 */
class SubContract extends Base
{
    protected $primaryKey = 'sub_contract_id';
    protected $table = 'sub_contract';

    protected $fillable = [
        'particulars',
        'sub_contract_amount',
        'last_updated_by'
    ];

    public $timestamps = false;

    public static $row = [
        'bill_code' => null,
        'bill_type' => null,
        'task_number' => null,
        'unit' => null,
        'rate' => null,
        'original_contract_amount' => null,
        'retainage_percent' => null,
        'retainage_amount' => null,
        'project' => null,
        'project_code' => null,
        'cost_type' => null,
        'cost_code' => null,
        'description' => null,
        'introw' => 0,
        'pint' => 0,
        'group' => null,
        'bill_code_detail' => 'Yes',
        'show' => false,
    ];

    public static $particular_column = [
        'bill_code' => [ 'title'=>'Bill code', 'type' => 'select' ],
        'bill_type' => [ 'title'=>'Bill type', 'type' => 'select' ],
        'cost_type' => [ 'title'=> 'Cost type', 'type' => 'select' ] ,
        'unit' => [ 'title'=> 'Unit', 'type' => 'input'],
        'rate' => [ 'title'=> 'Rate', 'type' => 'input'],
        'original_contract_amount' => [ 'title'=> 'Original Contract Amount', 'type' => 'input', 'visible' => false ],
        'retainage_percent' => [ 'title'=> 'Retainage %', 'type' => 'input', 'visible' => false ],
        'retainage_amount' => [ 'title'=> 'Retainage amount', 'type' => 'input' ],
        'project' => [ 'title'=> 'Project id', 'type' => 'input', 'visible' => false ],
        'cost_code' => [ 'title'=> 'Cost code', 'type' => 'input', 'visible' => false ],
        'task_number' => [ 'title'=> 'Task No.', 'type' => 'input', 'visible' => false ],
        'group' => [ 'title'=> 'Sub total group', 'type' => 'select' ] ,
        'bill_code_detail' => [ 'title'=> 'Bill code detail', 'type' => 'select' ],
    ];

    /**
     * @param $project_id
     * @param $retainage_percent
     * @return array
     */
    public static function initializeParticulars($project_id = '', $retainage_percent = ''): array
    {
        $particulars = [];
        $particulars[] = self::$row;
        if ($project_id != '')
            $particulars[0]['project'] = $project_id;
            $particulars[0]['retainage_percent'] = $retainage_percent;
        return $particulars;
    }

    /**
     * @return array
     */
    public function calculateTotal() {
        $total =0;
        $groups = [];
        $particulars = json_decode($this->particulars ?? [], true);
        if(!empty($particulars)) {
            foreach ($particulars as $key => $row) {
                if ($row['bill_code'] != '') {
                    if ($row['group'] != '') {
                        if (!in_array($row['group'], $groups)) {
                            $groups[] = $row['group'];
                        }
                    }
                    $total = $total + str_replace(',', '', $row['original_contract_amount']);
                    $particulars[$key]['original_contract_amount'] = str_replace(',', '', $row['original_contract_amount']);
                }
            }
        }
        return [$total, $groups, $particulars];
    }

    public function getSubContractList($merchant_id)
    {
        $retObj = DB::table('sub_contract')
                        ->join('project', 'sub_contract.project_id', '=', 'project.id')
                        ->join('vendor', 'sub_contract.vendor_id', '=', 'vendor.vendor_id')
                        ->where('sub_contract.merchant_id', $merchant_id)
                        ->where('sub_contract.is_active', 1)
                        ->where('sub_contract.status', 1)
                        ->select('sub_contract.*', 'project.project_name', 'project.project_id as project_code', 'vendor.vendor_name', 'vendor.vendor_code')
                        ->get();

        return $retObj;
    }
}