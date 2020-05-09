<?php

namespace App\Service;

class RandomCodeGenerator
{
    public function generate(int $length): string
    {
        if ($length <= 0)
        {
            throw new \Error('Length is too small');
        }
        $randomBytes = random_bytes($length);
        return bin2hex($randomBytes);
    }
}