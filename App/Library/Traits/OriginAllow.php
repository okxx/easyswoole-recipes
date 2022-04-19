<?php
namespace App\Library\Traits;

use App\Constants\Common;
use EasySwoole\Http\Message\Status;

trait OriginAllow
{

    protected function origin() :bool
    {
        $origin = $this->request()->getHeader('origin')[0] ?? '*';
        $this->response()->withHeader('Access-Control-Allow-Origin', $origin);
        $this->response()->withHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
        $this->response()->withHeader('Access-Control-Allow-Credentials', 'true');
        $this->response()->withHeader('Access-Control-Allow-Headers', Common::HEADER_ALLOW_HEADERS);
        if ($this->request()->getMethod() === 'OPTIONS') {
            $this->response()->withStatus(Status::CODE_OK);
            $this->response()->end();
            return false;
        }
        return false;
    }

}