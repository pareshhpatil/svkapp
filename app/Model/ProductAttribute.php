<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Auth;
use App\Model\ParentModel;

class ProductAttribute extends Model
{
    protected $table = 'merchant_product_attribute_metadata';
    protected $primaryKey = 'id';

    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'last_update_date';
    protected static $logFillable = true;
    protected $guarded = []; 

    public function saveProductAttribute($saveProductAttribute=null) {
        $saveProductAttribute['created_date'] = Carbon::now();
        $saveProductAttribute['last_update_date'] = Carbon::now();
        $saveProductAttribute['created_by'] = (isset($saveProductAttribute['created_by']) && $saveProductAttribute['created_by'] != '') ? $saveProductAttribute['created_by'] : Auth::id();
        $saveProductAttribute['last_update_by'] = (isset($saveProductAttribute['last_update_by']) && $saveProductAttribute['last_update_by'] != '') ? $saveProductAttribute['last_update_by'] : Auth::id();
        $savedQuery = ProductAttribute::create($saveProductAttribute);
        return $savedQuery;
    }

    public function checkProductAttrExist($name=null,$merchant_id=null) {
        $getProductAttrDetail = ProductAttribute::select('id','default_values')->where('name',$name)->where('merchant_id', $merchant_id)->where('is_active',1)->first();
        return $getProductAttrDetail;
    }
}