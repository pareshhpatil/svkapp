<?php

namespace App\Helpers\Merchant;

use App\Constants\Models\IColumn;
use App\Constants\Models\ITable;
use App\Libraries\Encrypt;
use App\Mail\SubUserWelcome;
use App\Model\Merchant\SubUser\Role;
use App\Model\Merchant\SubUser\SubUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SubUserHelper
{
    /**
     * @param $userID
     * @return \Illuminate\Support\Collection
     */
    public function indexTableData($userID)
    {
        $groupID = DB::table(ITable::USER)
            ->where('user_id', $userID)
            ->where('user_group_type', '1')
            ->pluck('group_id')
            ->first();

        $subUsers = DB::table(ITable::USER)
            ->where('group_id', $groupID)
            ->where('user_group_type', 2)
            ->whereIn('user_status', [19, 20])
            ->get()
            ->collect();

        $userStatusConfigs = DB::table(ITable::CONFIG)
            ->where('config_type', 'user_status')
            ->select(['config_key', 'config_value'])
            ->get()
            ->collect();

        $subUsers->map(function($subUser) use ($userStatusConfigs) {
            $userStatus = $userStatusConfigs->where('config_key', $subUser->user_status)
                ->first();

            $subUser->user_status_label = $userStatus->config_value;

            return $subUser;
        });

        return $subUsers;
    }

    /**
     * @return string
     */
    public function createUserIDSequence()
    {
        DB::table('sequence')
            ->where('seqname', 'User_id')
            ->increment('val');

        $UserSequence = DB::table('sequence')
            ->where('seqname', 'User_id')
            ->first();

        return $UserSequence->subscript . str_pad($UserSequence->val,9,"0",STR_PAD_LEFT);
    }

    /**
     * @param SubUser $SubUser
     * @param $roleID
     * @param $userID
     * @return void
     */
    public function addUserRole(SubUser $SubUser, $roleID)
    {
        /** @var Role $Role */
        $Role = Role::query()
            ->where(IColumn::ID, $roleID)
            ->first();

        DB::table(ITable::BRIQ_USER_ROLES)
            ->insert([
                'user_id' => $SubUser->user_id,
                'role_id' => $Role->id,
                'role_name' => $Role->name,
                'created_by' => $SubUser->created_by,
                'updated_by' => $SubUser->created_by,
                IColumn::CREATED_AT  => Carbon::now()->toDateTimeString(),
                IColumn::UPDATED_AT  => Carbon::now()->toDateTimeString()
            ]);
    }

    /**
     * @param SubUser $SubUser
     * @return void
     */
    public function sendVerifyMail(SubUser $SubUser)
    {
        $token = Encrypt::encode($SubUser->user_id .'_'. $SubUser->created_date->timestamp);
        $url = config('app.url'). '/merchant/register/verifyemail/new?token=' . $token;

        Mail::to($SubUser->email_id)->send(new SubUserWelcome($url));
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return DB::table(ITable::BRIQ_ROLES)
                    ->get()
                    ->toArray();
    }
}