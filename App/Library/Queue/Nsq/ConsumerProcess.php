<?php
namespace App\Queue\Nsq;

use EasySwoole\Component\Process\AbstractProcess;
use EasySwoole\Nsq\Nsq;
use EasySwoole\Nsq\Config;
use EasySwoole\Nsq\Lookup\Nsqlookupd;
use EasySwoole\Nsq\Connection\Consumer;

class ConsumerProcess extends AbstractProcess
{
    protected function run($arg)
    {
        go(function () {
            $topic      = "topic.test";
            $config     = new Config();
            $nsqlookup  = new Nsqlookupd($config->getNsqdUrl());
            $hosts      = $nsqlookup->lookupHosts($topic);
            foreach ($hosts as $host) {
                $nsq = new Nsq();
                $nsq->subscribe(
                    new Consumer($host, $config, $topic, 'test.consuming'),
                    function ($item) {
                        var_dump($item['message']);
                    }
                );
            }
        });
    }


}