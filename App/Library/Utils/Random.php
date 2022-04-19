<?php
namespace App\Library\Utils;

final class Random
{
    const DEFAULTS = [
        0    =>  '0123456789',
        1    =>  'abcdefghijklmnopqrstuvwxyz',
        2    =>  'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
        3    =>  '0123456789abcdefghijklmnopqrstuvwxyz',
        4    =>  '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ',
        5    =>  'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
        6    =>  '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
        7    =>  '3456789abcdefghijkmnpqrstuvwxyABCDEFGHJKLMNPQRSTUVWXY',
        8    =>  '23456789ABCDEFGHJKLMNPQRSTUVWXY',
        9    =>  '6345829ABDEGHCJKLMNPQRSFTUWXY',
        10   =>  '123456789'
    ];

    public static function condition($length = 0, $type = 0) :string
    {
        if (false === array_key_exists($type, self::DEFAULTS)) {
            $type = 6;
        }
        $character = '';
        $maxLength = strlen(self::DEFAULTS[$type])-1;
        for ($i = 0; $i < $length; ++$i) {
            $character .= self::DEFAULTS[$type][mt_rand(0, $maxLength)];
        }
        return $character;
    }

}