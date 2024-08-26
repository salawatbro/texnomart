<?php

namespace App\Services\User;


use App\Models\User;

class CheckUserExists
{
    /**
     * @param $phone
     * @return bool
     */
    public function execute($phone): bool
    {
        return User::where('phone', $phone)->exists();
    }
}
