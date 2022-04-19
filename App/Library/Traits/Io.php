<?php
namespace App\Library\Traits;

use App\Constants\Common;
use EasySwoole\Http\Message\Status;
use EasySwoole\Validate\Validate;

trait Io
{

    /**
     * @param array $rules
     * @param array $message
     * @return void
     * @throws \Throwable
     */
    protected function validator(array $rules, array $message = [])
    {
        $validate = Validate::make($rules, $message);
        if (!$validate->validate($this->request()->getRequestParam())) {
            throw new \Exception(sprintf(Common::PARAM_EXCEPTION,$validate->getError()->__toString()),Status::CODE_BAD_REQUEST);
        }
    }

    protected function JsonResponse($statusCode = Status::CODE_OK, $msg = Common::SUCCESS, $result = null): bool
    {
        $data = array(
            "code" => $statusCode,
            "result" => $result,
            "msg" => $msg
        );
        $this->response()->withStatus(Status::CODE_OK);
        $this->response()->withHeader('Content-type', 'application/json;charset=utf-8');
        $this->response()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        $this->response()->end();
        return false;
    }
}