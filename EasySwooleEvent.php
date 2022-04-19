<?php

namespace EasySwoole\EasySwoole;

use App\Library\Base\LogHandler;
use App\Library\Config\ServerHotReload;
use App\Library\Config\ServerLimiter;
use App\WebSocket\SocketEvent;
use EasySwoole\Component\Di;
use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\FastCache\Cache;
use EasySwoole\ORM\Db\Connection;
use EasySwoole\ORM\DbManager;
use EasySwoole\Redis\Config\RedisConfig;
use EasySwoole\RedisPool\RedisPool;

class EasySwooleEvent implements Event
{

    public static function initialize()
    {
        date_default_timezone_set('Asia/Shanghai');

        RedisPool::getInstance()->register(new RedisConfig(Config::getInstance()->getConf('REDIS')),'redis');

        $config = new \EasySwoole\ORM\Db\Config(Config::getInstance()->getConf('MYSQL'));
        DbManager::getInstance()->addConnection(new Connection($config));
        
        Di::getInstance()->set(SysConst::LOGGER_HANDLER, new LogHandler());
    }

    public static function mainServerCreate(EventRegister $register)
    {
        $server = ServerManager::getInstance()->getSwooleServer();
        $register->set(EventRegister::onOpen,[SocketEvent::class,'onOpen']);
        $register->set(EventRegister::onClose,[SocketEvent::class,'onClose']);
        $register->set(EventRegister::onMessage,[SocketEvent::class,'onMessage']);
        //$register->add(EventRegister::onWorkerStart,function ($server, $workerId) {
        //});

        ServerLimiter::getInstance()->initialize();
        ServerHotReload::getInstance()->initialize();

        Cache::getInstance()->setTempDir(EASYSWOOLE_TEMP_DIR)->attachToServer($server);
    }
}