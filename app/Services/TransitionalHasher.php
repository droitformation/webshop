<?php

namespace App\Services;

use App\Droit\User\Entities\User;

class TransitionalHasher extends \Illuminate\Hashing\BcryptHasher
{
    public function check($value, $hashedValue, array $options = array())
    {
        // If check fails, is it an old MD5 hash?
        if ( !password_verify($value, $hashedValue) )
        {
            $user = User::where('password','=', md5($value) )->get();

            if(!$user->isEmpty()) // We found a user with a matching MD5 hash
            {
                $user = $user->first();
                // Update the password to Laravel's Bcrypt hash
                // If two users have matching passwords, we might update the
                // wrong user -- but it doesn't matter!
                $user->password = \Hash::make($value);
                $user->save();

                // Log in the user
                return true;
            }
        }

        return password_verify($value, $hashedValue);
    }
}