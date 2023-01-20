<?php

namespace App\Http\Controllers\Merchant;

use App\Helpers\Merchant\RoleHelper;
use App\Http\Controllers\AppController;
use App\Http\Requests\StoreRoleRequest;
use App\Libraries\Helpers;
use App\Model\Merchant\SubUser\Permission;
use App\Model\Merchant\SubUser\Role;
use App\Repositories\Merchant\RolesRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Validator;

class RolesController extends AppController
{
    protected $repository;
    protected $roleHelper;

    public function __construct(RolesRepository $repository)
    {
        $this->repository = $repository;
        $this->roleHelper = new RoleHelper();

        parent::__construct();
    }

    /***
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $title = 'Roles list';

        $data = Helpers::setBladeProperties($title, ['units', 'template'], []);

        $data['roles'] = $this->repository->all();

        $data['datatablejs'] = 'table-no-export';

        return view('app/merchant/roles/index', $data);
    }

    /**
     * Show the form for creating a new cost type.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $title = 'Create Role';
        $data = Helpers::setBladeProperties($title, ['units', 'template'], []);

        /** @var Permission $Permissions */
        $data['permissions'] = Permission::all();

        return view('app/merchant/roles/create', $data);
    }

    /**
     * @param StoreRoleRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreRoleRequest $request)
    {
            $permissions = $request->get('permissions');

            /** @var Role $Role */
            $Role = $this->repository->create([
                                    'merchant_id' => $this->merchant_id,
                                    'name' => $request->get('name'),
                                    'description' => $request->get('description'),
                                    'created_by' => $this->user_id,
                                    'last_updated_by' => $this->user_id
                                ]);

            //Update Role Permissions
            $this->roleHelper->updateRolePermissions($Role->id, $permissions);

            return redirect()->to('merchant/roles')->with('success', "Role has been created");
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($roleID)
    {
        $title = 'Update Role';
        $data = Helpers::setBladeProperties($title, ['units', 'template'], []);

        $data['role'] = $this->repository->show($roleID);
        /** @var Permission $Permissions */
        $data['permissions'] = Permission::all();
        $selectedPermissions = $this->roleHelper->getRolePermissions($roleID);

        $data['selected_permissions'] = $selectedPermissions;

        return view('app/merchant/roles/edit', $data);
    }

    /**
     * @param $roleID
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
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

        $permissions = $request->get('permissions');

        /** @var Role $Role */
        $Role = $this->repository->update([
            'merchant_id' => $this->merchant_id,
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'last_updated_by' => $this->user_id
        ], $roleID);

        //Update Role Permissions
        $this->roleHelper->updateRolePermissions($Role->id, $permissions);

        return redirect()->to('merchant/roles')->with('success', "Role has been updated");
    }

    /**
     * @param $roleID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($roleID)
    {
        $this->roleHelper->deleteRole($roleID);

        return redirect()->to('merchant/roles')->with('success', "Role has been deleted");
    }

}
