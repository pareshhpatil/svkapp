<?php

namespace App\Helpers\Merchant;

use App\Constants\Models\IColumn;
use App\Constants\Models\ITable;
use App\Model\Merchant\SubUser\Permission;
use App\Model\Merchant\SubUser\Role;
use Illuminate\Support\Facades\DB;

class RoleHelper
{
    /**
     * @param $roleID
     * @param $permissions
     * @return void
     */
    public function updateRolePermissions($roleID, $permissions)
    {
        /** @var Role $Role */
        $Role = Role::query()
                    ->where('id', $roleID)
                    ->first();

        /** @var Permission $Permissions */
        $Permissions = Permission::query()
                            ->whereIn('slug', $permissions)
                            ->get();

        DB::table(ITable::BRIQ_ROLES_PERMISSIONS)
            ->where('role_id', $Role->id)
            ->delete();

        foreach ($Permissions as $permission) {
            /** @var Permission $permission */
            DB::table(ITable::BRIQ_ROLES_PERMISSIONS)
                ->updateOrInsert([
                    "role_id"         => $Role->id,
                    "permission_id"   => $permission->id,
                    "permission_slug" => $permission->slug,
                ]);
        }

    }

    /**
     * @param $roleID
     * @return array
     */
    public function getRolePermissions($roleID)
    {
        return DB::table(ITable::BRIQ_ROLES_PERMISSIONS)
                    ->where(IColumn::ROLE_ID, $roleID)
                    ->pluck(IColumn::PERMISSION_SLUG)
                    ->toArray();
    }
}