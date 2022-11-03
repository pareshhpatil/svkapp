<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Auth;

class Hsnsaccode extends Model
{
    protected $table = 'hsn_sac_code';
    protected $primaryKey = 'id';

    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'last_update_date';
    protected static $logFillable = true;
    protected $guarded = []; 


    public function saveCode($saveCode) {
        $saveCode['created_date'] = Carbon::now();
        $saveCode['last_update_date'] = Carbon::now();
        $saveCode['created_by'] = Auth::id();
        $saveCode['last_update_by'] = Auth::id();
        
        $savedQuery = Hsnsaccode::create($saveCode);
        return $savedQuery;
    }

    public function updateCode($updatecode=null,$id=null) {
        $updatedQuery = Hsnsaccode::where('id',$id)->update($updatecode);
        return $id;
    }

}
