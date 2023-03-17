<?php

namespace App\Imports;

use App\model\Merchant\SubUser\SubUsersRoles;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class BriqUserRolesImport implements ToModel, WithStartRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public function  __construct($merchant_id, $user_id, $role_id)
    {
        $this->merchant_id = $merchant_id;
        $this->user_id = $user_id;
        $this->role_id = $role_id;
    }

    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        return new SubUsersRoles([
            'user_id'    => $this->user_id,
            'role_id' =>   $this->role_id,
            'role_name' =>   $row[3],
            'created_by' =>  $this->user_id,
            'updated_by' =>  $this->user_id
        ]);

    }
}
