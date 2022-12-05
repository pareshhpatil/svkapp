<?php

namespace App\Libraries;

use App\Model\User;

class DataValidation
{

    public static function validMobile($data, $indian = false)
    {
        $valid = false;
        if (preg_match("/^(\+[\d]{1,5}|0)?[1-9]\d{9}$/", $data)) {
            if ($indian == true) {
                if (strlen($data) == 10) {
                    $valid = true;
                }
            } else {
                $valid = true;
            }
        }
        return $valid;
    }

    public static function validEmail($data)
    {
        $valid = false;
        if (preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/", $data)) {
            $valid = true;
        }
        return $valid;
    }

    public static function emailExist($data)
    {
        $user_model = new User();
        $exist = $user_model->emailExist($data);
        $valid = false;
        if ($exist == false) {
            $valid = true;
        }
        return $valid;
    }
}
