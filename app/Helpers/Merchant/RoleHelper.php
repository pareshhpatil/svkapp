<?php

namespace App\Helpers\Merchant;

use App\Constants\Models\IColumn;
use App\Constants\Models\ITable;
use App\Model\Merchant\SubUser\Permission;
use App\Model\Merchant\SubUser\Role;
use App\Model\Merchant\SubUser\SubUser;
use App\Model\Merchant\SubUser\SubUsersRoles;
use Illuminate\Support\Facades\DB;

/**
 * @author Nitish
 */
class RoleHelper
{
    /**
     * @param $permissionIDs
     * @return Permission
     */
    public function getPermissionIDSlugs($permissionIDs)
    {
        /** @var Permission $Permissions */
        $Permissions = Permission::query()
                                ->whereIn('id', $permissionIDs)
                                ->select(['id', 'slug'])
                                ->get()->toArray();

        return $Permissions;
    }

    /**
     * @param $roleID
     * @return void
     */
    public function deleteRole($roleID)
    {
        //Check User Role Exists
        $UserRoleExist = SubUsersRoles::query()
                                ->where(IColumn::ROLE_ID, $roleID)
                                ->exists();
        //if exists then soft delete
        if($UserRoleExist) {
            SubUser::query()
                ->where(IColumn::ROLE_ID, $roleID)
                ->delete();
        }

        Role::find($roleID)->delete();
    }
}