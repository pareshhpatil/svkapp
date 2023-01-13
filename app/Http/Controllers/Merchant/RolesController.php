<?php

namespace App\Http\Controllers\Merchant;

use App\Constants\Models\IColumn;
use App\Constants\Models\ITable;
use App\Helpers\Merchant\RoleHelper;
use App\Http\Controllers\AppController;
use App\Libraries\Helpers;
use App\Model\Merchant\SubUser\Permission;
use App\Model\Merchant\SubUser\Role;
use App\Repositories\Merchant\RolesRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        $Permissions = Permission::all();

        $data['permissions'] = $Permissions;

        return view('app/merchant/roles/create', $data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            $rules = [
                'name' => 'required|unique:briq_roles',
                'permissions' => 'required'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors($validator->messages());
            }

            $permissions = $request->get('permissions');

            /** @var Role $Role */
            $Role = new Role();

            $Role->merchant_id = $this->merchant_id;
            $Role->name = $request->get('name');
            $Role->description = $request->get('description');
            $Role->created_by = $this->user_id;

            $Role->save();

            //Update Role Permissions
            (new RoleHelper())->updateRolePermissions($Role->id, $permissions);

            return redirect()->to('merchant/roles')->with('success', "Role has been created");
        } catch (Exception $exception) {
            Log::error($exception->getMessage());

            return redirect()->to('merchant/roles')->with('error', "Something went wrong!");
        }
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
        $Permissions = Permission::all();
        $data['permissions'] = $Permissions;
        $selectedPermissions = (new RoleHelper())->getRolePermissions($roleID);

        $data['selected_permissions'] = $selectedPermissions;

        return view('app/merchant/roles/edit', $data);
    }


    public function update($id, Request $request)
    {
        try {
            $rules = [
                'name' => 'required|unique:briq_roles,name,'.$id,
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors($validator->messages());
            }

            $permissions = $request->get('permissions');

            /** @var Role $Role */
            $Role = Role::query()
                        ->find($id);

            $Role->merchant_id = $this->merchant_id;
            $Role->name = $request->get('name');
            $Role->description = $request->get('description');
            $Role->last_updated_by = $this->user_id;

            $Role->save();

            //Update Role Permissions
            (new RoleHelper())->updateRolePermissions($Role->id, $permissions);
            
            return redirect()->to('merchant/roles')->with('success', "Role has been updated");
        } catch (Exception $exception) {
            Log::error($exception->getMessage());

            return redirect()->to('merchant/roles')->with('error', "Something went wrong!");
        }
    }

}
