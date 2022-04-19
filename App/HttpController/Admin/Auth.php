<?php
namespace App\HttpController\Admin;

use App\Constants\Common;

use App\Library\Base\AbstractController;
use App\Logic\AdminAuthLogic;
use EasySwoole\Http\Message\Status;

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
            'account'       => 'required|notEmpty',
            'password'      => 'required|notEmpty',
        ]);
        $user = AdminAuthLogic::getInstance()->login($this->request()->getRequestParam('account'),$this->request()->getRequestParam('password'));
        return $this->JsonResponse(Status::CODE_OK,Common::SUCCESS,[
            'token' => $this->buildToken($user),
            'username'  => $user['account'],
        ]);
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
        $registerLogic  = AdminAuthLogic::getInstance()->register($account,$password,$requestCode);
        return $this->JsonResponse(Status::CODE_OK,Common::SUCCESS,$registerLogic->getOriginData());
    }

}