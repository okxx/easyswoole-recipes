<?php
namespace App\HttpController;

use App\Constants\Common;
use App\Library\Utils\Random;
use EasySwoole\Http\Message\Status;
use App\Library\Base\AbstractController;

class Code extends AbstractController
{

    // 生成验证码
    public function generate()
    {
        $account = $this->request()->getRequestParam('account');
        $code = Random::condition(4,10);
        $setNx = $this->rds()->set($account,$code,['NX','EX' => Common::EXPIRE_HOUR]);
        if (!$setNx) {
            return $this->JsonResponse(Status::CODE_BAD_REQUEST,Common::CODE_GENERATE_EXISTS);
        }
        return $this->JsonResponse();
    }

    public function fetch()
    {
        $account = $this->request()->getRequestParam('account');
        $code = $this->rds()->get($account);
        if (!$code) {
            return $this->JsonResponse(Status::CODE_BAD_REQUEST,Common::CODE_NOT_EXISTS);
        }
        return $this->JsonResponse(Status::CODE_OK,Common::SUCCESS,['code' => $code]);
    }

}