<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentRequest extends Model
{
    protected $table = 'payment_request';
    protected $primaryKey = 'payment_request_id';

    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'last_update_date';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'payment_request_id',
        'user_id',
        'merchant_id', 
        'customer_id', 
        'payment_request_type', 
        'invoice_type', 
        'template_id', 
        'billing_cycle_id',
        'absolute_cost', 
        'basic_amount', 
        'tax_amount', 
        'invoice_total', 
        'swipez_total', 
        'convenience_fee', 
        'late_payment_fee', 
        'grand_total', 
        'previous_due', 
        'advance_received', 
        'paid_amount', 
        'invoice_number', 
        'estimate_number', 
        'payment_request_status', 
        'bill_date', 
        'due_date', 
        'expiry_date', 
        'narrative', 
        'franchise_id', 
        'vendor_id',
        'is_active',
        'bulk_id',
        'unit_available',
        'converted_request_id',
        'parent_request_id',
        'notify_patron',
        'notification_sent',
        'webhook_id',
        'short_url',
        'document_url',
        'has_custom_reminder',
        'autocollect_payment_id',
        'plugin_value',
        'gst_number',
        'billing_profile_id',
        'created_by',
        'last_update_by',
    ];
}
