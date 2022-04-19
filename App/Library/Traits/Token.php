<?php
namespace App\Library\Traits;

use App\Constants\Common;
use App\Logic\AdminAuthLogic;
use App\Logic\UserLogic;
use EasySwoole\EasySwoole\Config;
use EasySwoole\Jwt\Jwt;
use EasySwoole\Jwt\JwtObject;
use App\Constants\Status;

trait Token
{

    /**
     * @param mixed $user
     * @return JwtObject
     */
    public function buildToken($user) :string
    {
        $jwtObj = Jwt::getInstance()->setSecretKey(Config::getInstance()->getConf('SERVER_NAME'))->publish();
        $jwtObj->setAud($user); // user
        $jwtObj->setExp(time()+86400);// 有效期
        $jwtObj->setIat(time());
        $jwtObj->setData($user);
        return $jwtObj->__toString();
    }

    /**
     * @param string $token
     * @return bool
     * @throws \Throwable
     */
    public function checkToken(string $token) :bool
    {
        try {
            $jwtObj = Jwt::getInstance()->decode($token);
            $status = $jwtObj->getStatus();
            switch ($status) {
                case 1:
                    $this->userInfo = $jwtObj->getData(); // get user
                    if ($this->userInfo) {
                        $user = UserLogic::getInstance()->infoByAccount($this->userInfo['account']);
                        if (!$user) {
                            return $this->JsonResponse(Status::CODE_BAD_REQUEST, Common::USER_NOTFOUND);
                        }
                        $this->userInfo = $user;

                        // TODO 各种验证 (身份，权限，等)....
                    } else {
                        return $this->JsonResponse(Status::TOKEN_INVALID,Common::TOKEN_VERIFICATION_FAILED);
                    }
                    break;
                case -1:
                    return $this->JsonResponse(Status::TOKEN_INVALID,Common::TOKEN_IS_INVALID);
                case -2:
                    return $this->JsonResponse(Status::TOKEN_INVALID,Common::TOKEN_HAS_EXPIRED);
            }
        } catch (\Exception $e) {
            return $this->JsonResponse($e->getCode(),$e->getMessage());
        }
        return true;
    }


    /**
     * @param string $token
     * @return bool
     * @throws \Throwable
     */
    public function checkAdminToken(string $token) :bool
    {
        try {
            $jwtObj = Jwt::getInstance()->decode($token);
            $status = $jwtObj->getStatus();
            switch ($status) {
                case 1:
                    $this->userInfo = $jwtObj->getData(); // get user
                    if ($this->userInfo) {
                        $admin = AdminAuthLogic::getInstance()->infoByAccount($this->userInfo['account']);
                        if (!$admin) {
                            return $this->JsonResponse(Status::CODE_BAD_REQUEST, Common::USER_NOTFOUND);
                        }
                        $this->userInfo = $admin;
                        // TODO 各种验证 (身份，权限，等)....
                    } else {
                        return $this->JsonResponse(Status::TOKEN_INVALID,Common::TOKEN_VERIFICATION_FAILED);
                    }
                    break;
                case -1:
                    return $this->JsonResponse(Status::TOKEN_INVALID,Common::TOKEN_IS_INVALID);
                case -2:
                    return $this->JsonResponse(Status::TOKEN_INVALID,Common::TOKEN_HAS_EXPIRED);
            }
        } catch (\Exception $e) {
            return $this->JsonResponse($e->getCode(),$e->getMessage());
        }
        return true;
    }
}