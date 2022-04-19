<?php
namespace App\HttpController;

use App\Constants\Common;
use App\Library\Utils\Date;
use App\Library\Utils\Mid;
use App\Library\Utils\Random;

use App\Library\Base\AbstractController;
use App\Logic\AuthLogic;
use App\Models\UserInfoModel;
use App\Task\UserTask;
use EasySwoole\EasySwoole\Task\TaskManager;
use EasySwoole\Http\Message\Status;
use phpDocumentor\Reflection\Types\This;

class Auth extends AbstractController
{

    /**
     * 登录
     *
     * @return bool
     * @throws \EasySwoole\Mysqli\Exception\Exception
     * @throws \EasySwoole\ORM\Exception\Exception
     * @throws \Throwable
     */
    public function login(): bool
    {
        $this->validator([
            'account'       => 'required|notEmpty|Integer',
            'password'      => 'required|notEmpty',
        ]);
        $user = AuthLogic::getInstance()->login($this->request()->getRequestParam('account'),$this->request()->getRequestParam('password'));
        return $this->JsonResponse(Status::CODE_OK,Common::SUCCESS,['token' => $this->buildToken($user)]);
    }

    /**
     * 注册
     *
     * @return bool
     * @throws \EasySwoole\Mysqli\Exception\Exception
     * @throws \EasySwoole\ORM\Exception\Exception
     * @throws \Throwable
     */
    public function register(): bool
    {
        $this->validator([
            'account'       => 'required|notEmpty|Integer',
            'password'      => 'required|notEmpty',
            'code'          => 'required|notEmpty|Integer'
        ]);
        $account        = $this->request()->getRequestParam('account');
        $password       = $this->request()->getRequestParam('password');
        $requestCode    = $this->request()->getRequestParam('code');
        $registerLogic  = AuthLogic::getInstance()->register($account,$password,$requestCode);
        return $this->JsonResponse(Status::CODE_OK,Common::SUCCESS,$registerLogic->getOriginData());
    }

}