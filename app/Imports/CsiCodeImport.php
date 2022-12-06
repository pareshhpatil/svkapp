<?php

namespace App\Imports;

use App\CsiCode;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class CsiCodeImport implements ToModel, WithStartRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public function  __construct($merchant_id, $user_id, $project_id)
    {
        $this->merchant_id= $merchant_id;
        $this->user_id= $user_id;
        $this->project_id= $project_id;
    }

    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        return new CsiCode([
            'project_id'     => $this->project_id,
            'merchant_id' =>  $this->merchant_id,
            'code'    => $row[3],
            'title' =>  $row[4],
            'description' =>  $row[5],
            'created_by' =>  $this->user_id,
            'last_update_by' =>  $this->user_id
        ]);
    }
}
