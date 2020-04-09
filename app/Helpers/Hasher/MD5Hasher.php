<?php

namespace App\Helpers\Hasher;

use Illuminate\Contracts\Hashing\Hasher;

class MD5Hasher implements Hasher
{
    public function info($hashedValue)
    {
        return $hashedValue;
    }

    public function check($value, $hashedValue, array $options = [])
    {

        return $this->make($value) === $hashedValue;
    }

    public function needsRehash($hashedValue, array $options = [])
    {
        return false;
    }

    public function make($value, array $options = [])
    {
        return md5($value);
    }
}
