<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostType extends Model
{
    use HasFactory;

    protected $table = 'cost_types';

    public $timestamps = false;

    protected $guarded = [];  
}
