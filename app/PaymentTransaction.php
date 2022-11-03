<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    protected $table = 'payment_transaction';
    protected $primaryKey = 'payment_transaction_id';

    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'last_update_date';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pay_transaction_id',
        'payment_request_id',
        'estimate_request_id', 
        'customer_id', 
        'patron_user_id', 
        'merchant_id', 
        'merchant_user_id', 
        'amount',
        'unit_price', 
        'quantity', 
        'grand_total', 
        'convenience_fee', 
        'discount', 
        'deduct_amount', 
        'deduct_text', 
        'coupon_id', 
        'tax', 
        'payment_request_type', 
        'payment_transaction_status', 
        'bank_status', 
        'pg_id', 
        'fee_id', 
        'pg_ref_no', 
        'pg_ref_1', 
        'pg_ref_2', 
        'payment_mode', 
        'narrative', 
        'late_payment',
        'paid_on',
        'is_availed',
        'franchise_id',
        'vendor_id',
        'commission',
        'is_partial_payment',
        'short_url',
        'created_by',
        'last_update_by',
    ];
}
