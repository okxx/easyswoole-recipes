<?php
namespace App\Library\Utils;

use EasySwoole\Component\Singleton;

final class Mid
{
    use Singleton;

    public function buildPassword(string $password, string $salting): string
    {
        return md5($password.md5($salting));
    }

}