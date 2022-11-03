<?php


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

namespace App\Model;

use Log;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Model\ParentModel;

class RigionSetting extends ParentModel
{
    public function updateRegion($data,$user_id)
    {
        DB::table('preferences')
            ->where('user_id', $user_id)
            ->update([
                 'timezone' => $data->selecttimezone,
                 'currency' => $data->selectcurrency,
                'date_format' => $data->dateformat,
                'time_format' => $data->timeformat,
                'last_update_date' => date('Y-m-d H:i:s')
            ]);
    }
    public function getRegionSetting($user_id)
    {
        $retObj = DB::table('preferences')
            ->select(DB::raw('timezone,currency,date_format,time_format'))
            ->where('user_id', $user_id)
            ->get();
        if ($retObj->isEmpty()) {
            return array();
        } else {
            return $retObj;
        }
    }

}
