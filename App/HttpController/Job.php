<?php
namespace App\HttpController;

use App\Constants\Common;
use EasySwoole\FastCache\Cache;
use EasySwoole\Http\Message\Status;
use App\Library\Base\AbstractController;

class Job extends AbstractController
{

    public function queues()
    {
        $data = Cache::getInstance()->queueList();
        if ($data == null) {
            $data = [];
        }
        return $this->JsonResponse(Status::CODE_OK,Common::SUCCESS,$data);
    }

    public function consumer()
    {
        $job = Cache::getInstance()->getJob('jobs');
        Cache::getInstance()->deleteJob($job);
        return $this->JsonResponse(Status::CODE_OK,Common::SUCCESS,$job);
    }

}