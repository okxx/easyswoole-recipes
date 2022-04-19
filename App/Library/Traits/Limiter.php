<?php
namespace App\Library\Traits;

use App\Constants\Common;
use EasySwoole\Component\Di;
use Swoole\Http\Status;

trait Limiter
{
    protected $limiter;

    public function limiterProcess(string $action) :void
    {
        $default = 100;
        if ($action == 'register') {
            $default = 2;
        }
        $this->limiter = Di::getInstance()->get('limiter');
        if ($this->limiter != null && !$this->limiter->access($action,$default)) {
            $this->JsonResponse(Status::BAD_REQUEST,Common::ROUTE_THROTTLING_DENIED);
        }
    }

}