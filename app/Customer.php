<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customer';
    protected $primaryKey = 'customer_id';

    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'last_update_date';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'merchant_id', 'customer_code', 'first_name', 'last_name', 'email', 'mobile', 'address', 'city', 'state', 'zipcode', 'customer_group', 'is_active', 'email_comm_status', 'sms_comm_status', 'gst_number', 'created_by', 'last_update_by',
    ];
   
}
