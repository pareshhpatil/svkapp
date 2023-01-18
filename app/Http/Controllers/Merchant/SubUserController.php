<?php

namespace App\Http\Controllers\Merchant;

use App\Constants\Models\IColumn;
use App\Constants\Models\ITable;
use App\Helpers\Merchant\SubUserHelper;
use App\Http\Controllers\AppController;
use App\Libraries\Encrypt;
use App\Libraries\Helpers;
use App\Model\Merchant\SubUser\SubUser;
use App\Model\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Validator;

class SubUserController extends AppController
{

    /***
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $title = 'Sub merchant list';

        $data = Helpers::setBladeProperties($title, ['units', 'template'], []);

        $data['subUsers'] = (new SubUserHelper())->indexTableData($this->user_id);
        $data['datatablejs'] = 'table-no-export';

        return view('app/merchant/subuser/index', $data);
    }

    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $title = 'Create sub-user';
        $data = Helpers::setBladeProperties($title, ['units', 'template'], []);

        $data['briqRoles'] = (new SubUserHelper())->getRoles();

        return view('app/merchant/subuser/create', $data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            $rules = [
                'email_id' => 'required|max:255',
                'first_name' => 'required|max:50',
                'last_name' => 'required|max:50',
                'mob_country_code' => 'required|max:6',
                'mobile' => 'required|max:12',
                'password' => 'required|confirmed|max:40',
                'role' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors($validator->messages());
            }

            $groupID = DB::table(ITable::USER)
                ->where('user_id', $this->user_id)
                ->pluck('group_id')
                ->first();

            $userIDSequence = (new SubUserHelper())->createUserIDSequence();

            /** @var SubUser $SubUser */
            $SubUser = new SubUser();

            $SubUser->user_id = $userIDSequence;
            $SubUser->name = $request->get('first_name')." ".$request->get('last_name');
            $SubUser->first_name = $request->get('first_name');
            $SubUser->last_name = $request->get('last_name');
            $SubUser->email_id = $request->get('email_id');
            $SubUser->password = password_hash($request->get('password'), PASSWORD_DEFAULT);
            $SubUser->user_status = 19;
            $SubUser->group_id = $groupID;
            $SubUser->user_group_type = 2;
            $SubUser->user_type = 0;
            $SubUser->franchise_id = 0;
            $SubUser->mob_country_code = $request->get('mob_country_code');
            $SubUser->mobile_no = $request->get('mobile');
            $SubUser->created_by = $this->user_id;
            $SubUser->created_date = Carbon::now()->toDateTimeString();
            $SubUser->last_updated_by = $this->user_id;
            $SubUser->last_updated_date = Carbon::now()->toDateTimeString();

            $SubUser->save();

            (new SubUserHelper())->addUserRole($SubUser, $request->get('role'));

            (new SubUserHelper())->sendVerifyMail($SubUser);

            return redirect()->to('merchant/subusers')->with('success', "Sub Merchant has been created");
        } catch (Exception $exception) {
            Log::error($exception->getMessage());

            return redirect()->to('merchant/subusers')->with('error', "Something went wrong!");
        }
    }

    /**
     * Show the form for edit user.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($userID)
    {
        $title = 'Edit sub-user';
        $data = Helpers::setBladeProperties($title, ['units', 'template'], []);

        $data['briqRoles'] = (new SubUserHelper())->getRoles();

        /** @var \App\User $User */
        $User = \App\User::query()
                    ->where(IColumn::USER_ID, Encrypt::decode($userID))
                    ->first();

        $data['selected_role_id'] = $User->role()->role_id ?? '';

        $User->user_id = Encrypt::encode($User->user_id);

        $data['user'] = $User;

        return view('app/merchant/subuser/edit', $data);
    }

    /**
     * @param $userID
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($userID, Request $request)
    {
        try {
            $rules = [
                'first_name' => 'required|max:50',
                'last_name' => 'required|max:50',
                'mob_country_code' => 'required|max:6',
                'mobile' => 'required|max:12',
                'role' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors($validator->messages());
            }

            /** @var SubUser $SubUser */
            $SubUser = SubUser::query()
                            ->where(IColumn::USER_ID, Encrypt::decode($userID))
                            ->first();

            $SubUser->name = $request->get('first_name')." ".$request->get('last_name');
            $SubUser->first_name = $request->get('first_name');
            $SubUser->last_name = $request->get('last_name');
            $SubUser->mob_country_code = $request->get('mob_country_code');
            $SubUser->mobile_no = $request->get('mobile');
            $SubUser->last_updated_by = $this->user_id;
            $SubUser->last_updated_date = Carbon::now()->toDateTimeString();

            $SubUser->save();

            (new SubUserHelper())->addUserRole($SubUser, $request->get('role'));

            return redirect()->to('merchant/subusers')->with('success', "Sub Merchant has been updated");
        } catch (Exception $exception) {
            Log::error($exception->getMessage());

            return redirect()->to('merchant/subusers')->with('error', "Something went wrong!");
        }
    }

    /**
     * @param $userID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($userID)
    {
        try {
            $countAdminUsers = DB::table('user')
                ->join('briq_user_roles', 'user.user_id', '=', 'briq_user_roles.user_id')
                ->where('briq_user_roles.role_name', 'Admin')
                ->select('user.user_id', 'briq_user_roles.role_name')
                ->count();

            /** @var \App\User $User */
            $User = \App\User::query()
                            ->where(IColumn::USER_ID, $userID)
                            ->first();

            if(!$User) {
                return redirect()->to('merchant/subusers')->with('error', "Unable to find this User!");
            }

            if($User->role()->role_name == 'Admin' && $countAdminUsers == 1) {
                return redirect()->to('merchant/subusers')->with('error', "At least One Active Admin is required in the system!");
            }

            $this->updateMerchantUserStatus(21, $userID);

            return redirect()->to('merchant/subusers')->with('success', "Sub Merchant has been deleted");
        } catch (Exception $exception) {
            Log::error($exception->getMessage());

            return redirect()->to('merchant/subusers')->with('error', "Something went wrong!");
        }
    }

    public function verifyMail(Request $request)
    {
        $token = $request->get('token');
        $decodeToken = Encrypt::decode($token);
        $explodeDecodeToken = explode("_", $decodeToken);
        $userID = $explodeDecodeToken[0];
        $createdDate = Carbon::createFromTimestamp($explodeDecodeToken[1])->toDateTimeString();

        $checkUser = SubUser::where(IColumn::USER_ID, $userID)
                            ->where('created_date', $createdDate)
                            ->first();

        $title = 'Email Verification Successfull';

        $data = Helpers::setBladeProperties($title, ['units', 'template'], []);

        $status = '';
        if (!empty($checkUser)) {
            if ($checkUser->user_status == 11) {
                $this->updateMerchantUserStatus(15, $userID);
                $status = 'activated';
            } else if ($checkUser->user_status == 19) {
                $this->updateMerchantUserStatus(20, $userID);
            }
        }

        $data['status'] = $status;

        return view('app/merchant/subuser/thankyou', $data);
    }

    /**
     * @param $status
     * @param $userID
     * @return void
     */
    private function updateMerchantUserStatus($status, $userID)
    {
        /** @var SubUser $SubUser */
        $SubUser = SubUser::query()
                        ->where('user_id', $userID)
                        ->first();

        $SubUser->prev_status = $SubUser->user_status;
        $SubUser->user_status = $status;

        $SubUser->save();
    }
}
