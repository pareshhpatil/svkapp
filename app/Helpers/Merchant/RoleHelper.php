<?php

namespace App\Helpers\Merchant;

use App\Model\Merchant\SubUser\Permission;

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

}