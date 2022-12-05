<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Project extends Model
{
    use HasFactory;

    protected $table = 'project';
    
    protected $guarded = [];  
    public $timestamps = false;

    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class,'customer_id', 'customer_id');
    }

    public function merchant(): HasOne
    {
        return $this->hasOne(Merchant::class, 'merchant_id', 'merchant_id');
    }

    public function bill_codes(): HasMany
    {
        return $this->hasMany(CsiCode::class, 'project_id','id');
    }
}
