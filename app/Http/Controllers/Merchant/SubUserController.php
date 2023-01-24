<?php

namespace App\Http\Controllers\Merchant;

use App\Constants\Models\IColumn;
use App\Constants\Models\ITable;
use App\Helpers\Merchant\SubUserHelper;
use App\Http\Controllers\AppController;
use App\Http\Requests\StoreUserRequest;
use App\Libraries\Encrypt;
use App\Libraries\Helpers;
use App\Model\Merchant\SubUser\SubUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class SubUserController extends AppController
{
    protected $subUserHelper;

    public function __construct()
    {
        $this->subUserHelper = new SubUserHelper();

        parent::__construct();
    }

    /***
     * @return \Illuminate\Contracts\View\View
     * @author Nitish
     */
    public function index()
    {
        $title = 'Sub merchant list';

        $data = Helpers::setBladeProperties($title);

        $data['subUsers'] = $this->subUserHelper->indexTableData($this->user_id);
        $data['datatablejs'] = 'table-no-export';

        return view('app/merchant/subuser/index', $data);
    }

    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\Contracts\View\View
     * @author Nitish
     */
    public function create()
    {
        $title = 'Create sub-user';
        $data = Helpers::setBladeProperties($title);

        $data['briqRoles'] = $this->subUserHelper->getRoles();

        return view('app/merchant/subuser/create', $data);
    }

    /**
     * @param StoreUserRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @author Nitish
     */
    public function store(StoreUserRequest $request)
    {
        $this->subUserHelper->storeUser($this->user_id, $request);

        return redirect()->to('merchant/subusers')->with('success', "Sub Merchant has been created");
    }

    /**
     * Show the form for edit user.
     *
     * @return \Illuminate\Contracts\View\View
     * @author Nitish
     */
    public function edit($userID)
    {
        $title = 'Edit sub-user';
        $data = Helpers::setBladeProperties($title);

        $data['briqRoles'] = $this->subUserHelper->getRoles();

        /** @var \App\User $User */
        $User = \App\User::query()
                    ->where(IColumn::USER_ID, Encrypt::decode($userID))
                    ->first();

        $data['selected_role_id'] = $User->role()->id ?? '';

        $User->user_id = Encrypt::encode($User->user_id);

        $data['user'] = $User;

        return view('app/merchant/subuser/edit', $data);
    }

    /**
     * @param $userID
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @author Nitish
     */
    public function update($userID, Request $request)
    {
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

        $this->subUserHelper->updateUser($this->user_id, $userID, $request);

        return redirect()->to('merchant/subusers')->with('success', "Sub Merchant has been updated");
    }

    /**
     * @param $userID
     * @return \Illuminate\Http\RedirectResponse
     * @author Nitish
     */
    public function delete($userID)
    {
        $countAdminUsers = DB::table(ITable::USER)
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
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @author Nitish
     */
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

        $title = 'Email Verification Successful';

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
     * @author Nitish
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
