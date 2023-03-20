<?php

namespace App\Model\Merchant;

use App\Constants\Models\ITable;
use App\Model\Base;
use App\Model\ParentModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
/**
 * @property mixed $id
 * @property mixed $name
 * @property mixed $abbrevation
 * @property mixed $merchant_id
 * @property mixed $created_by
 * @property mixed $last_update_by
 * @property false|mixed|string $created_at
 * @property false|mixed|string $updated_at
 *
 */
class CostType extends Base
{
    use SoftDeletes;

    protected $table = ITable::COST_TYPES;

    protected $fillable = [
        'name',
        'abbrevation',
        'merchant_id',
        'created_by',
        'last_update_by'
    ];

    public function createCostType($cost_type,$merchant_id,$user_id){
        $costTypeDetails = CostType::where('name', $cost_type)
        ->where('merchant_id', $merchant_id)
        ->where('is_active',1)
        ->first();

        if($costTypeDetails!=null) {
            $data['cost_type']=$costTypeDetails->id;
        } else {
            //save cost type and fetch its id
            //Check If abbrevation already exists
            $abbrevation = Str::upper(substr($cost_type, 0, 1));
            $exists = CostType::query()->where('abbrevation', $abbrevation)
            ->where('merchant_id',$merchant_id)->where('is_active',1)->exists();

            if($exists) {
                $abbrevation = Str::upper(substr($cost_type, 0, 2));
            }
            
            $costType = CostType::create([
                'name' => $cost_type,
                'abbrevation' => $abbrevation,
                'merchant_id' => $merchant_id,
                'created_by' => $user_id,
                'created_at' => date('Y-m-d H:i:s'),
                'last_update_by' => $user_id
                ]);
            
            $data['cost_type'] = $costType->id;
        }

        return $data['cost_type'];
    }

}