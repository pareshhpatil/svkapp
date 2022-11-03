<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Validate extends Model {

    protected $table = 'migration_validation';
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'last_update_date';

}
