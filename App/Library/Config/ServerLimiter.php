<?php
namespace App\Library\Config;

use EasySwoole\AtomicLimit\AtomicLimit;
use EasySwoole\Component\Di;
use EasySwoole\Component\Singleton;
use EasySwoole\EasySwoole\ServerManager;

class ServerLimiter
{
    use Singleton;

    /**
     * initialize
     * @return void
     */
    public function initialize() {
        $limit = new AtomicLimit();
        $limit->setLimitQps(20);
        $limit->attachServer(ServerManager::getInstance()->getSwooleServer());
        Di::getInstance()->set('limiter',$limit);
    }
}