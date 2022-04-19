<?php
namespace App\Queue\Nsq;

use EasySwoole\Component\Process\AbstractProcess;
use EasySwoole\Nsq\Nsq;
use EasySwoole\Nsq\Config;
use EasySwoole\Nsq\Message\Message;
use EasySwoole\Nsq\Lookup\Nsqlookupd;
use EasySwoole\Nsq\Connection\Producer;

class ProducerProcess extends AbstractProcess
{
    protected function run($arg)
    {
        go(function () {
            $config = new Config();
            $topic  = "topic.test";
            $lookup = new Nsqlookupd($config->getNsqdUrl());
            $hosts = $lookup->lookupHosts($topic);
            foreach ($hosts as $host) {
                $nsq = new Nsq();
                for ($i = 0; $i < 10; $i++) {
                    $msg = new Message();
                    $msg->setPayload("test$i");
                    $nsq->push(new Producer($host, $config), $topic, $msg);
                }
            }
        });
    }


}