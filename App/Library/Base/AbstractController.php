<?php
namespace App\Library\Base;

use App\Library\Traits\CheckLogin;
use App\Library\Traits\Io;
use App\Library\Traits\Limiter;
use App\Library\Traits\OriginAllow;
use App\Library\Traits\Sign;
use App\Library\Traits\Token;
use EasySwoole\Http\AbstractInterface\Controller;
use EasySwoole\Redis\Redis as RedisClient;
use EasySwoole\RedisPool\RedisPool;

class AbstractController extends Controller
{
    use OriginAllow, CheckLogin, Token, Sign, Limiter, Io;

    /**
     * @param string|null $action
     * @return bool|null
     * @throws \Throwable
     */
    protected function onRequest(?string $action): ?bool
    {
        $this->origin(); // origin allow.

        $this->limiterProcess($action); // limiter

        $this->checkSign(); // sign

        $this->checkLogin(); // checking login

        if ($this->response()->isEndResponse()) {
            return false;
        }

        return true;
    }

    protected function onException(\Throwable $throwable): void
    {
        $this->JsonResponse($throwable->getCode(),$throwable->getMessage());
    }



    public function rds() :?RedisClient
    {
        return RedisPool::defer('redis');
    }


}