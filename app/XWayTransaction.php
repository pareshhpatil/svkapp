<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class XWayTransaction extends Model
{
    protected $table = 'xway_transaction';
    protected $primaryKey = 'xway_transaction_id';

    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'last_update_date';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'xway_transaction_id',
        'merchant_id',
        'franchise_id', 
        'vendor_id', 
        'xway_transaction_status', 
        'referrer_url', 
        'account_id', 
        'secure_hash',
        'return_url', 
        'reference_no', 
        'amount', 
        'absolute_cost', 
        'surcharge_amount', 
        'discount', 
        'currency', 
        'description', 
        'customer_code', 
        'name', 
        'address', 
        'address', 
        'city', 
        'state', 
        'postal_code', 
        'phone', 
        'email', 
        'payment_id', 
        'pg_ref_no1', 
        'pg_ref_no2',
        'pg_id',
        'mdd',
        'udf1',
        'udf2',
        'udf3',
        'udf4',
        'udf5',
        'payment_mode',
        'narrative',
        'coupon_id',
        'payment_request_id',
        'plan_id',
        'type',
        'webhook_id',
        
    ];
}
