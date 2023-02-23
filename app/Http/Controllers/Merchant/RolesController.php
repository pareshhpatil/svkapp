<?php

namespace App\Http\Controllers\Merchant;

use App\Constants\Models\IColumn;
use App\Helpers\Merchant\RoleHelper;
use App\Http\Controllers\AppController;
use App\Http\Requests\StoreRoleRequest;
use App\Libraries\Helpers;
use App\Model\Merchant\SubUser\Permission;
use App\Model\Merchant\SubUser\Role;
use App\Model\Merchant\SubUser\SubUser;
use App\Model\Merchant\SubUser\SubUsersRoles;
use App\Repositories\Merchant\RolesRepository;
use Illuminate\Http\Request;
use Validator;

class RolesController extends AppController
{
    protected $repository;

    public function __construct(RolesRepository $repository)
    {
        $this->repository = $repository;

        parent::__construct();
    }

    /***
     * @return \Illuminate\Contracts\View\View
     * @author Nitish
     */
    public function index()
    {
        $title = 'Roles list';

        $data = Helpers::setBladeProperties($title);

        $data['roles'] = $this->repository->all();

        $data['datatablejs'] = 'table-no-export';

        return view('app/merchant/roles/index', $data);
    }

    /**
     * Show the form for creating a new cost type.
     *
     * @return \Illuminate\Contracts\View\View
     * @author Nitish
     */
    public function create()
    {
        $title = 'Create Role';
        $data = Helpers::setBladeProperties($title);

        return view('app/merchant/roles/create', $data);
    }

    /**
     * @param StoreRoleRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @author Nitish
     */
    public function store(StoreRoleRequest $request)
    {
        $this->repository->create([
            'merchant_id' => $this->merchant_id,
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'created_by' => $this->user_id,
            'last_updated_by' => $this->user_id
        ]);

        return redirect()->to('merchant/roles')->with('success', "Role has been created");
    }

    /**
     * @return \Illuminate\Contracts\View\View
     * @author Nitish
     */
    public function edit($roleID)
    {
        $title = 'Update Role';
        $data = Helpers::setBladeProperties($title);

        $Role = $this->repository->show($roleID);

        $data['role'] = $Role;

        return view('app/merchant/roles/edit', $data);
    }

    /**
     * @param $roleID
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @author Nitish
     */
    public function update($roleID, Request $request)
    {
        $rules = [
            'name' => 'required|unique:briq_roles,name,'.$roleID,
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator->messages());
        }

        $this->repository->update([
            'merchant_id' => $this->merchant_id,
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'last_updated_by' => $this->user_id
        ], $roleID);

        return redirect()->to('merchant/roles')->with('success', "Role has been updated");
    }

    /**
     * @param $roleID
     * @return \Illuminate\Http\RedirectResponse
     * @author Nitish
     */
    public function delete($roleID)
    {
        //Check User Role Exists
        $UserRoleExist = SubUsersRoles::query()
                                    ->where(IColumn::ROLE_ID, $roleID)
                                    ->exists();

        //if exists then return error
        if(!empty($UserRoleExist)) {
            return redirect()->to('merchant/roles')->with('error', "You can't delete this bcz user exists on this role");
        }

        Role::find($roleID)->delete();

        return redirect()->to('merchant/roles')->with('success', "Role has been deleted");
    }

}
