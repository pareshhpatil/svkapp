<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Constants\Models\IColumn;
use App\Model\Merchant\SubUser\Permission;
use Illuminate\Support\Str;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            [
                'name'  => 'Create Invoice',
                'group' => 'Invoice'
            ],
            [
                'name'  => 'Approve Invoice',
                'group' => 'Invoice'
            ],
            [
                'name'  => 'Update Invoice',
                'group' => 'Invoice'
            ],
            [
                'name'  => 'Create Change Order',
                'group' => 'Change Order'
            ],
            [
                'name'  => 'Approve Change Order',
                'group' => 'Change Order'
            ],
            [
                'name'  => 'Create Contract',
                'group' => 'Contract'
            ],
            [
                'name'  => 'Update Contract',
                'group' => 'Contract'
            ]
        ];

        foreach ($permissions as &$permission) {
            /** @var Permission $Permission */
            $Permission = Permission::firstOrNew([
                IColumn::NAME  => $permission['name'],
                IColumn::GROUP => $permission['group'],
            ]);

            $Permission->slug = Str::slug($permission['name']);
            $Permission->save();
        }
    }
}
