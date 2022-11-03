<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Auth;
use App\Model\ParentModel;

class ProductAttributeValue extends Model
{
    protected $table = 'merchant_product_attribute_values';
    protected $primaryKey = 'id';

    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'last_update_date';
    protected static $logFillable = true;
    protected $guarded = []; 

    public function saveProductAttrValue($saveAttributeValue=null) {
        $saveAttributeValue['created_date'] = Carbon::now();
        $saveAttributeValue['last_update_date'] = Carbon::now();
        $saveAttributeValue['created_by'] = (isset($saveAttributeValue['created_by']) && $saveAttributeValue['created_by'] != '') ? $saveAttributeValue['created_by'] : Auth::id();
        $saveAttributeValue['last_update_by'] = (isset($saveAttributeValue['last_update_by']) && $saveAttributeValue['last_update_by'] != '') ? $saveAttributeValue['last_update_by'] : Auth::id();
        
        $savedQuery = ProductAttributeValue::create($saveAttributeValue);
        return $savedQuery;
    }
}