<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model {

    protected $table = 'invoice_tax';
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'last_update_date';

}
