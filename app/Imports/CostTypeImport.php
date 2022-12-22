<?php

namespace App\Imports;

use App\CostType;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class CostTypeImport implements ToModel, WithStartRow
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
        return new CostType([
            'name'    => $row[1],
            'abbrevation' =>  $row[2],
            'merchant_id' =>  $this->merchant_id,
            'created_by' =>  $this->user_id,
            'last_update_by' =>  $this->user_id
        ]);

    }
}
