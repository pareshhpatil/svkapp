<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Model\User;

class Emailexist implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $user_model = new User();
        $exist = $user_model->emailExist($value);
        $valid = false;
        if ($exist == false) {
            $valid = true;
        }
        return $valid;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return [
            'Emailexist' => 'Email id already exists. Would you like to <a href="/login">Login</a>',
        ];
    }
}
