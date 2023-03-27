<?php

namespace App\Imports;

use App\model\Merchant\SubUser\Permission;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class BriqPermissionsImport implements ToModel, WithStartRow
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
        return new Permission([
            'name'    => $row[1],
            'slug' =>  $row[2],
            'group' =>   $row[3],
        ]);

    }
}
