<?php
namespace App\Library\Traits;

use App\Constants\Common;
use App\Library\Signature\Config\SignConfig;
use App\Library\Signature\SignStrategyProcess;
use EasySwoole\EasySwoole\Config;
use Swoole\Http\Status;

trait Sign
{
    /**
     * TODO 如果签约了其他签名算法会通过配置实例化具体的处理类。这里默认为MD5
     *
     * @return bool|void
     */
    public function checkSign()
    {
        if (!in_array($this->request()->getServerParams()['path_info'],Config::getInstance()->getConf('SIGN_ACTIONS'))) {
            $handle = SignConfig::getInstance()->handle();// Obtaining the signature algorithm through configuration
            $handle->setData($this->request()->getQueryParams());// Write signature verification params
            $signStrategyProcess = new SignStrategyProcess($handle);
            if (!$signStrategyProcess->decrypt()) {
                return $this->JsonResponse(Status::BAD_REQUEST,Common::SIGNATURE_VERIFICATION_FAILED);
            }
        }
    }
}