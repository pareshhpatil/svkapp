<?php

namespace App\Imports;

use App\model\Merchant\SubUser\Role;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

use App\Imports\BriqUserRolesImport;


use Maatwebsite\Excel\Facades\Excel;

class BriqRolesImport implements ToModel, WithStartRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public function  __construct($merchant_id, $user_id)
    {
        $this->merchant_id= $merchant_id;
        $this->user_id= $user_id;
    }

    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        $role = Role::create([
            'merchant_id' =>  $this->merchant_id,
            'name'    => $row[2],
            'description' =>  $row[3],
            'permissions' => '',
            'created_by' =>  $this->user_id,
            'last_updated_by' =>  $this->user_id
        ]);

        $role_id = $role->id;
        
        Excel::import(new BriqUserRolesImport($this->merchant_id, $this->user_id, $role_id), env('BRIQ_TEST_DATA_USER_ROLES_FILE'));

    }
}
