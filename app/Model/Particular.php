<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Particular extends Model {

    protected $table = 'invoice_particular';
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'last_update_date';

}
