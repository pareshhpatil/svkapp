<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsiCode extends Model
{
    use HasFactory;

    protected $table = 'csi_code';

    public $timestamps = false;

    protected $fillable = [
        'code', 'title', 'description', 'project_id', 'merchant_id'
    ];
}
