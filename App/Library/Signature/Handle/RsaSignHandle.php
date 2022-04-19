<?php
namespace App\Library\Signature\Handle;

use App\Library\Signature\SignStrategy;
use App\Models\SignModel;

class RsaSignHandle extends SignModel implements SignStrategy
{
    public function encrypt()
    {
        // rsa encrypt .
    }

    public function decrypt()
    {
        // rsa decrypt .
    }
}