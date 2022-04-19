<?php
namespace App\Library\Traits;

use App\Constants\Common;
use App\Models\UserInfoModel;
use EasySwoole\EasySwoole\Config;
use App\COnstants\Status;

trait CheckLogin
{
    public $userInfo;

    protected $isLogin = true;

    /**
     * @return bool
     * @throws \Throwable
     */
    public function checkLogin() :bool
    {
        $this->isLogin = !in_array($this->request()->getServerParams()['path_info'],Config::getInstance()->getConf('BASIC_ACTIONS'));
        if ($this->isLogin) {
            //$admin = preg_match('%^/admin/%', $this->request()->getUri()->getPath());
            $rrs = $this->request()->getHeaderLine('rrs');
            if ($rrs) {
                // check admin token
                $token = $this->request()->getCookieParams('admin-user-token');
                if ($token == null) {
                    $token = $this->request()->getHeaderLine('admin-user-token');
                    if ($token == null) {
                        return $this->JsonResponse(Status::CODE_BAD_REQUEST,Common::TOKEN_NOT_FOUND);
                    }
                    return $this->checkAdminToken($token);
                }
            } else {
                // check user token...
                $token = $this->request()->getCookieParams('token');
                if ($token == null) {
                    $token = $this->request()->getHeaderLine('token');
                    if ($token == null) {
                        return $this->JsonResponse(Status::TOKEN_INVALID,Common::TOKEN_NOT_FOUND);
                    }
                    return $this->checkToken($token);
                }
            }
        }

        // check ticket...

        return true;
    }

    public function getUserInfo() :UserInfoModel
    {
        return $this->userInfo;
    }
}