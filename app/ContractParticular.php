<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractParticular extends Model
{
    use HasFactory;

    protected $primaryKey = 'contract_id';

    protected $table = 'contract';

    protected $fillable = [
        'contract_code',
        'merchant_id',
        'version',
        'contract_amount',
        'customer_id',
        'project_id',
        'contract_amount',
        'contract_date',
        'bill_date',
        'billing_frequency',
        'particulars',
        'created_by',
        'last_update_by',
        'created_date',
        'status'
    ];

    protected $rules = [

    ];

    protected $casts = [
//        'particulars' => 'array'
    ];

    public $timestamps = false;
}
