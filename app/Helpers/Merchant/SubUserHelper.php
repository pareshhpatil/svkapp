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

/**
 * @author Nitish
 */
class SubUserHelper
{
    /**
     * @param $authUserID
     * @return mixed
     */
    public function getGroupID($authUserID)
    {
        $authUser = DB::table(ITable::USER)
            ->where(IColumn::USER_ID, $authUserID)
            ->first();

        if($authUser->user_group_type == 1) {
            return DB::table('merchant')
                ->where(IColumn::USER_ID, $authUserID)
                ->pluck('group_id')
                ->first();
        }

        return DB::table('merchant')
            ->where('group_id', $authUser->group_id)
            ->pluck('group_id')
            ->first();
    }

    /**
     * @param $authUserID
     * @param $request
     * @return bool[]
     */
    public function storeUser($authUserID, $request)
    {
        $groupID = $this->getGroupID($authUserID);

        $checkEmail = DB::table('user')
            ->where('email_id', $request->get('email_id'))
            ->where('user_status', '!=', '21')
            ->exists();

        if($checkEmail) {
            return [
                'email_exist' => true
            ];
        }

        $userIDSequence = $this->createUserIDSequence();

        /** @var SubUser $SubUser */
        $SubUser = new SubUser();

        $SubUser->user_id = $userIDSequence;
        $SubUser->name = $request->get('first_name')." ".$request->get('last_name');
        $SubUser->first_name = $request->get('first_name');
        $SubUser->last_name = $request->get('last_name');
        $SubUser->email_id = $request->get('email_id');
        $SubUser->user_status = 20;
        $SubUser->group_id = $groupID;
        $SubUser->user_group_type = 2;
        $SubUser->user_type = 0;
        $SubUser->franchise_id = 0;
        $SubUser->created_by = $authUserID;
        $SubUser->created_date = Carbon::now()->toDateTimeString();
        $SubUser->last_updated_by = $authUserID;
        $SubUser->last_updated_date = Carbon::now()->toDateTimeString();

        $SubUser->save();

        $this->addUserRole($SubUser, $request->get('role'));

        $this->addUserPrefrences($SubUser);

        return [
            'user_create' => true,
            'user' => $SubUser
        ];
    }

    /**
     * @param $authUserID
     * @param $userID
     * @param $request
     * @return void
     */
    public function updateUser($authUserID, $userID, $request)
    {
        /** @var SubUser $SubUser */
        $SubUser = SubUser::query()
            ->where(IColumn::USER_ID, Encrypt::decode($userID))
            ->first();

        $SubUser->name = $request->get('first_name')." ".$request->get('last_name');
        $SubUser->first_name = $request->get('first_name');
        $SubUser->last_name = $request->get('last_name');
        $SubUser->last_updated_by = $authUserID;
        $SubUser->last_updated_date = Carbon::now()->toDateTimeString();

        $SubUser->save();

        $this->addUserRole($SubUser, $request->get('role'));
    }

    /**
     * @param $userID
     * @return \Illuminate\Support\Collection
     */
    public function indexTableData($userID)
    {
        $groupID = $this->getGroupID($userID);

        $subUsers = DB::table(ITable::USER)
            ->where('group_id', $groupID)
            ->whereIn('user_status', [19, 20, 15])
            ->get()
            ->collect();

        $userStatusConfigs = DB::table(ITable::CONFIG)
            ->where('config_type', 'user_status')
            ->select(['config_key', 'config_value'])
            ->get()
            ->collect();

        $userRoles = DB::table(ITable::BRIQ_USER_ROLES)
            ->select(['user_id', 'role_name'])
            ->get()
            ->collect();

        $subUsers->map(function($subUser) use ($userStatusConfigs, $userRoles) {
            $userStatus = $userStatusConfigs->where('config_key', $subUser->user_status)
                ->first();

            $userRole = $userRoles->where('user_id', $subUser->user_id)
                ->first();

            $subUser->user_id = Encrypt::encode($subUser->user_id);
            $subUser->user_status_label = $userStatus->config_value;
            $subUser->user_role_name = $userRole->role_name ?? '';

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
            ->updateOrInsert(
                ['user_id' => $SubUser->user_id],
                [
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
    public function addUserPrefrences(SubUser $SubUser)
    {
        DB::table(ITable::PREFERENCES)
            ->insert(
                [
                    'user_id' => $SubUser->user_id,
                    'send_sms' => 1,
                    'send_email' => 1,
                    'send_push' => 1,
                    'send_app' => 1,
                    'timezone' => 'UTC',
                    'currency' => 'USD',
                    'date_format' => 'M d yyyy',
                    'time_format' => '12',
                    'created_date'  => Carbon::now()->toDateTimeString(),
                    'last_update_date'  => Carbon::now()->toDateTimeString()
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
     * @param $merchantID
     * @return array
     */
    public function getRoles($merchantID)
    {
        return DB::table(ITable::BRIQ_ROLES)
            ->where('merchant_id', $merchantID)
            ->get()
            ->toArray();
    }

    /**
     * @param $merchantID
     * @return array
     */
    public function getAdminRole($merchantID)
    {
        return DB::table(ITable::BRIQ_ROLES)
            ->where('merchant_id', $merchantID)
            ->where('name', 'Admin')
            ->first();
    }
}