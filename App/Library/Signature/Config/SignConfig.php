<?php
namespace App\Library\Signature\Config;

use App\Library\Signature\Handle\Md5SignHandle;
use App\Library\Signature\SignStrategy;
use EasySwoole\Component\Di;
use EasySwoole\Component\Singleton;

class SignConfig
{

    use Singleton;

    public function handle() :SignStrategy
    {
        Di::getInstance()->set('default',new Md5SignHandle());

        $handle = Di::getInstance()->get('default');
        if (!$handle) {
            $handle = new Md5SignHandle();// The default algorithm is MD5
        }
        return $handle;
    }

}