<?php

namespace App\Task;

use EasySwoole\Task\AbstractInterface\TaskInterface;

class UserTask implements TaskInterface
{

    function run(int $taskId, int $workerIndex)
    {
        /**
         * todo 用户登录成功触发的异步处理流程
         *
         * 1.更新登录时间
         * 2.统计位置信息
         * 3.记录各种log
         * ....
         */
        echo $taskId. ' user tasking....'.PHP_EOL;
        sleep(3);
        echo $taskId. ' sleep over.'.PHP_EOL;

    }

    function onException(\Throwable $throwable, int $taskId, int $workerIndex)
    {
        // TODO: Implement onException() method.
    }
}