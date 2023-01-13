<?php

namespace Database\Seeders;

use App\Model\Merchant\SubUser\Permission;
use Illuminate\Database\Seeder;
use App\Model\Merchant\SubUser\Role;
use App\Constants\Models\IColumn;
use App\Constants\Models\ITable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'name'        => 'Admin',
                'description' => 'Can create / edit users and any objects (invoice. contract, co) created by admin will not go through approval process'
            ],
            [
                'name'        => 'Creator',
                'description' => 'Can create or edit objects they have access to'
            ],
            [
                'name'        => 'Watcher',
                'description' => 'Watcher can view  all objects they have access to'
            ],
            [
                'name'        => 'Reviewer',
                'description' => 'Reviewer can view / edit all objects they have access to'
            ]
        ];

        foreach ($roles as &$role) {
            /** @var Role $Role */
            $Role = Role::firstOrNew([
                IColumn::NAME => $role['name'],
            ]);

            $Role->slug        = Str::slug($role['name']);
            $Role->description = $role['description'];
            $Role->save();
        }

        $this->assignRolesToPermissions();
    }

    private function assignRolesToPermissions()
    {
        // Truncate Table Before entering data
        DB::table(ITable::BRIQ_ROLES_PERMISSIONS)
            ->truncate();

        /**@var Role $CollectRole */
        $Roles = Role::all();

        /** @var Role $AdminRole */
        $AdminRole = $Roles->where(IColumn::SLUG, 'admin')
            ->first();

        /** @var Role $CreatorRole */
        $CreatorRole = $Roles->where(IColumn::SLUG, 'creator')
            ->first();

        /** @var Role $ReviewerRole */
        $ReviewerRole = $Roles->where(IColumn::SLUG, 'reviewer')
            ->first();

        /** @var Role $WatcherRole */
        $WatcherRole = $Roles->where(IColumn::SLUG, 'watcher')
            ->first();

        /** @var Permission $Permissions */
        $Permissions = Permission::all();

        $adminRolePermissions = [];
        $creatorRolePermissions = [];
        $reviewerRolePermissions = [];
        $watcherRolePermissions = [];

        foreach ($Permissions as $Permission) {
            /** @var Permission $Permission */

            //Set Admin Role and Permissions
            $adminRolePermissions[] = [
                "role_id"         => $AdminRole->id,
                "role_slug"       => $AdminRole->slug,
                "permission_id"   => $Permission->id,
                "permission_slug" => $Permission->slug,
            ];

            //Set Creator Role and Permissions
            if ($Permission->group == 'Invoice'
                || $Permission->group == 'Change Order') {
                $creatorRolePermissions[] = [
                    "role_id"         => $CreatorRole->id,
                    "role_slug"       => $CreatorRole->slug,
                    "permission_id"   => $Permission->id,
                    "permission_slug" => $Permission->slug,
                ];
            }

            //Set Reviewer Role and Permissions
            if($Permission->slug == 'approve-invoice'
                || $Permission->slug == 'update-invoice'
                || $Permission->slug == 'approve-change-order'
                || $Permission->slug == 'update-change-order') {
                $reviewerRolePermissions[] = [
                    "role_id"         => $ReviewerRole->id,
                    "role_slug"       => $ReviewerRole->slug,
                    "permission_id"   => $Permission->id,
                    "permission_slug" => $Permission->slug,
                ];
            }
        }

        $data = array_merge(
            $adminRolePermissions,
            $creatorRolePermissions,
            $reviewerRolePermissions
        );

        //Insert Roles and Permission
        DB::table(ITable::BRIQ_ROLES_PERMISSIONS)
            ->insert($data);
    }
}
