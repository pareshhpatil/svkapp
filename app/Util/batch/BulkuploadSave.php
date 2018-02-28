<?php

namespace App\Util\Batch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

#Crypt::encryptString('Hello world.')
#Crypt::decryptString($encrypted)

class Bulkuploadsave extends Model {

    function test()
    {
        echo 'hii';
        
    }
}

$ab=new Bulkuploadsave();
$ab->test();
        