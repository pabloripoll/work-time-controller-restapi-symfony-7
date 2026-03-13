<?php

namespace App\Infrastructure\Support;

class Random
{
    public function key(int $length = 64): string
    {
        return bin2hex(random_bytes($length / 2));
    }

    public function alphaNumeric(int $length = 16): string
    {
        return implode(array_map(fn() => array_merge(range(0,9),range('a','z'),range('A','Z'))[random_int(0,61)], range(1, $length)));
    }

    public function integer(int $length = 11): int
    {
        $numbers = '1234567890';
        $output = '';
        for ($i = 0; $i < $length; $i++) {
            $output .= $numbers[rand(0, strlen($numbers) - 1)];
        }

        return (int) $output;
    }

}
