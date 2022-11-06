<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\User;

class EmailExists implements Rule
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
        $u = User::where('email',$value)->where('client_slug',client('slug'))->first();
        if($u)
            return False;
        else
            return True;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Email exists in our database, kindly reset password or use a different email id.';
    }
}
