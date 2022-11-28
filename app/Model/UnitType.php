<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Auth;

class UnitType extends Model
{
    protected $table = 'merchant_unit_type';
    protected $primaryKey = 'id';

    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'last_update_date';
    protected static $logFillable = true;
    protected $guarded = []; 

    public function saveUnitType($saveUnitData) {
        $saveUnitData['created_date'] = Carbon::now();
        $saveUnitData['last_update_date'] = Carbon::now();
        $saveUnitData['created_by'] = Auth::id();
        $saveUnitData['last_update_by'] = Auth::id();
        
        $savedQuery = UnitType::create($saveUnitData);
        return $savedQuery;
    }
}
