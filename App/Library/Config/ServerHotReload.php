<?php
namespace App\Library\Config;

use EasySwoole\Component\Singleton;
use EasySwoole\EasySwoole\ServerManager;
use EasySwoole\FileWatcher\FileWatcher;
use EasySwoole\FileWatcher\WatchRule;

class ServerHotReload
{

    use Singleton;


    public function initialize()
    {
        $watcher = new FileWatcher();
        $rule = new WatchRule(EASYSWOOLE_ROOT);
        $watcher->addRule($rule);
        $watcher->setOnChange(function () {
            ServerManager::getInstance()->getSwooleServer()->reload();
        });
        $watcher->attachServer(ServerManager::getInstance()->getSwooleServer());
    }


}