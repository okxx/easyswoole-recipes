<?php
namespace App\Library\Signature;

interface SignStrategy
{
    
    public function encrypt();

    public function decrypt();

}