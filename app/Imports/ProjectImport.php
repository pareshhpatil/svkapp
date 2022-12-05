<?php

namespace App\Imports;

use App\Project;
use App\CsiCode;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

use App\Imports\CsiCodeImport;

use Maatwebsite\Excel\Facades\Excel;

class ProjectImport implements ToModel, WithStartRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public function  __construct($merchant_id, $user_id, $customer_id)
    {
        $this->merchant_id= $merchant_id;
        $this->user_id= $user_id;
        $this->customer_id= $customer_id;
    }

    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        $project = Project::create([
            'project_id'     => $row[1],
            'project_name'    => $row[2],
            'customer_id' =>   $this->customer_id,
            'merchant_id' =>  $this->merchant_id,
            'start_date' =>  is_null($row[5])? '' : $row[5],
            'end_date' =>  is_null($row[6])? '' : $row[6],
            'sequence_number' =>  is_null($row[7])? '1' : $row[7],
            'created_by' =>  $this->user_id,
            'last_update_by' =>  $this->user_id
        ]);
        
        Excel::import(new CsiCodeImport( $this->merchant_id, $this->user_id, $project->id), env('BRIQ_TEST_DATA_CSI_CODE_FILE'));

        return  $project;
    }
}
