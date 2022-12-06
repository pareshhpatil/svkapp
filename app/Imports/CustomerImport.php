<?php

namespace App\Imports;

use App\Imports\ProjectImport;

use App\Customer;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

use Maatwebsite\Excel\Facades\Excel;

class CustomerImport implements ToModel, WithStartRow
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
        $customer = Customer::create([
            'merchant_id' =>  $this->merchant_id,
            'user_id'     => $row[3],
            'customer_code'     => $row[4],
            'first_name'     => is_null($row[5]) ? '' : $row[5],
            'last_name'     => is_null($row[6]) ? '' : $row[6],
            'email'     => $row[7],
            'mobile'     => $row[8],
            'address'     => is_null($row[9]) ? '' : $row[9],
            'address2'     => $row[10],
            'city'     => $row[11],
            'state'     => $row[12],
            'country'     => $row[13],
            'zipcode'     => $row[14],
            'customer_group'     => $row[15],
            'customer_merchant_id'     => $row[16],
            'customer_status'     => $row[18],
            'payment_status'     => $row[19],
            'email_comm_status'     => $row[20],
            'sms_comm_status'     => $row[21],
            'qr_image_path'     => $row[22],
            'password'     => $row[23],
            'company_name'     => $row[24],
            'gst_number'     => $row[25],
            'type'     => $row[26],
            'balance'     => $row[27],
            'bulk_id'     => $row[28],
            'password'     => $row[29],
            'created_by' =>  $this->user_id,
            'last_update_by' =>  $this->user_id
        ]);

        $customer_id = $customer->customer_id;
        
        Excel::import(new ProjectImport($this->merchant_id, $this->user_id, $customer_id), env('BRIQ_TEST_DATA_PROJECT_FILE'));

        return $customer;
    }
}
