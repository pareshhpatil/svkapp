<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Auth;

class ProductCategory extends Model
{
    protected $table = 'product_category';
    protected $primaryKey = 'id';

    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'last_update_date';
    protected static $logFillable = true;
    protected $guarded = []; 


    public function saveProductCategory($saveProductCategory) {
        $saveProductCategory['created_date'] = Carbon::now();
        $saveProductCategory['last_update_date'] = Carbon::now();
        $saveProductCategory['created_by'] = Auth::id();
        $saveProductCategory['last_update_by'] = Auth::id();
        
        $savedQuery = ProductCategory::create($saveProductCategory);
        return $savedQuery;
    }

}
