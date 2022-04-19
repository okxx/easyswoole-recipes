<?php
namespace App\HttpController;

use App\Constants\Common;
use App\Library\Base\AbstractController;
use App\Library\Signature\Handle\Md5SignHandle;
use App\Library\Signature\SignStrategyProcess;
use Swoole\Http\Status;

class Sign extends AbstractController
{

    public function encrypt()
    {
        $md5 = new Md5SignHandle();
        $md5->setData($this->request()->getQueryParams());
        $signStrategyProcess = new SignStrategyProcess($md5);
        return $this->JsonResponse(Status::OK,Common::SUCCESS,['sign' => $signStrategyProcess->encrypt()]);
    }

}