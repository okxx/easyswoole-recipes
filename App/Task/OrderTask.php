<?php

namespace App\Task;

use EasySwoole\Component\WaitGroup;
use EasySwoole\EasySwoole\Logger;
use EasySwoole\Task\AbstractInterface\TaskInterface;
use Swoole\Coroutine\Channel;

class OrderTask implements TaskInterface
{

    public $data;

    public function __construct($data) {
        $this->data = $data;
    }

    function run(int $taskId, int $workerIndex)
    {
        // init channel
        $ch = new Channel();

        // waitGroup
        $wg = new WaitGroup();
        $wg->add();
        go(function () use (&$wg,&$taskId,&$workerIndex,$ch) {
            $m = $taskId. " data : ". json_encode($this->data) . ' workerId : '.$workerIndex.PHP_EOL;
            $ch->push($m);
           $wg->done();
        });
        $wg->wait();

        echo $ch->pop();// channel pop
        $ch->close(); // close channel
    }

    function onException(\Throwable $throwable, int $taskId, int $workerIndex)
    {
        Logger::getInstance()->error($throwable->getMessage());
    }

}