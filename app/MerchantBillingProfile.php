<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MerchantBillingProfile extends Model
{
    protected $table = 'merchant_billing_profile';
    protected $primaryKey = 'id';

    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'last_update_date';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'merchant_id', 
        'profile_name', 
        'company_name', 
        'gst_number', 
        'state', 
        'address',
        'business_email', 
        'business_contact', 
        'country', 
        'city', 
        'zipcode', 
        'pan', 
        'tan', 
        'cin_no', 
        'sac_code', 
        'business_category', 
        'reg_address', 
        'reg_city', 
        'reg_state', 
        'reg_zipcode', 
        'is_default', 
        'gsp_id', 
        'is_active', 
        'reg_country', 
        'created_by', 
        'last_update_by',
        'gstin'
    ];
}
