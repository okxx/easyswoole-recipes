<?php
namespace App\HttpController;

use App\Constants\Common;
use App\Library\Base\AbstractController;
use App\Logic\UserLogic;
use Swoole\Http\Status;

class User extends AbstractController
{
    public function info()
    {
        echo date('Y-m-d H:i:s',time()+3600).PHP_EOL;
        return $this->JsonResponse(Status::OK,Common::SUCCESS,$this->getUserInfo());
    }

    public function list()
    {
        $users = UserLogic::getInstance()->page($this->request()->getRequestParam('page'),$this->request()->getRequestParam('limit'));
        return $this->JsonResponse(Status::OK,Common::SUCCESS,$users);
    }

}