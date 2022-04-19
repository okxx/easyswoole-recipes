<?php
namespace App\HttpController;

use App\Constants\Common;
use App\Library\Base\AbstractController;
use App\Logic\GoodsLogic;
use EasySwoole\Http\Message\Status;
use EasySwoole\ORM\Exception\Exception;
use phpDocumentor\Reflection\Types\This;

class Goods extends AbstractController
{

    /**
     * 创建商品
     *
     * @return bool
     * @throws Exception
     * @throws \Throwable
     */
    public function create(): bool
    {
        $this->validator([
            'name'      => 'required|notEmpty',
            'image'     => 'required|notEmpty',
            'price'     => 'required|float',
        ]);
        $result = GoodsLogic::getInstance()->create($this->request()->getRequestParam());
        return $this->JsonResponse(Status::CODE_OK,Common::SUCCESS,$result);
    }

    /**
     * 商品信息
     *
     * @return bool
     * @throws Exception
     * @throws \EasySwoole\Mysqli\Exception\Exception
     * @throws \Throwable
     */
    public function info() :bool
    {
        $goodsInfo = GoodsLogic::getInstance()->info($this->request()->getRequestParam('id'));
        if (!$goodsInfo) {
            return $this->JsonResponse(Status::CODE_BAD_REQUEST,Common::GOODS_NOT_FOUND);
        }
        return $this->JsonResponse(Status::CODE_OK,Common::SUCCESS,$goodsInfo);
    }


    /**
     * @return bool
     * @throws Exception
     * @throws \Throwable
     */
    public function paginate(): bool
    {
        $goodsList = GoodsLogic::getInstance()->pageList($this->request()->getRequestParam());
        return $this->JsonResponse(Status::CODE_OK,Common::SUCCESS,$goodsList);
    }

    /**
     * 编辑商品
     *
     * @return bool
     * @throws Exception
     * @throws \EasySwoole\Mysqli\Exception\Exception
     * @throws \Throwable
     */
    public function edit() :bool
    {
        $edit = GoodsLogic::getInstance()->edit($this->request()->getRequestParam('id'),$this->request()->getRequestParam());
        if (!$edit) {
            return $this->JsonResponse(Status::CODE_BAD_REQUEST,Common::GOODS_EDIT_FAIL);
        }
        return $this->JsonResponse();
    }

    /**
     * @return bool
     * @throws Exception
     * @throws \Throwable
     */
    public function delete()
    {
        $this->validator(['id' => 'required|Integer']);
        GoodsLogic::getInstance()->deleteById($this->request()->getRequestParam('id'));
        return $this->JsonResponse();
    }

}