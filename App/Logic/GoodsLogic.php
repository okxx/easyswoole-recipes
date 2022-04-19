<?php
namespace App\Logic;

use App\Constants\Common;
use App\Constants\Status;
use App\Library\Utils\Date;
use App\Models\GoodsInfoModel;
use EasySwoole\Component\Singleton;

final class GoodsLogic
{
    use Singleton;

    /**
     * 创建商品
     *
     * @param array $params
     * @return bool
     * @throws \EasySwoole\ORM\Exception\Exception
     * @throws \Throwable
     */
    public function create(array $params): bool
    {
        $params['create_time'] = Date::getInstance()->now();
        $params['update_time'] = Date::getInstance()->now();
        $model = GoodsInfoModel::create($params);
        return $model->save();
    }


    /**
     * @param array $params
     * @return array
     * @throws \EasySwoole\ORM\Exception\Exception
     * @throws \Throwable
     */
    public function pageList(array $params): array
    {
        $page = $params['page'] ?? 1;
        $limit = $params['limit'] ?? 20;
        $model = GoodsInfoModel::create()->limit($limit * ($page - 1), $limit)->withTotalCount();
        if (isset($params['name']) && !empty($params['name'])) {
            $model->where('name',$params['name']);
        }
        return [
            'list' => $model->all(),
            'total' => $model->lastQueryResult()->getTotalCount()
        ];
    }

    /**
     * 商品信息
     *
     * @param string $goodsId
     * @return GoodsInfoModel|array|bool|\EasySwoole\ORM\AbstractModel|\EasySwoole\ORM\Db\Cursor|\EasySwoole\ORM\Db\CursorInterface|null
     * @throws \EasySwoole\Mysqli\Exception\Exception
     * @throws \EasySwoole\ORM\Exception\Exception
     * @throws \Throwable
     */
    public function info(string $goodsId)
    {
        return GoodsInfoModel::create()->where('id',$goodsId)->get();
    }

    /**
     * 编辑商品
     *
     * @param string $goodsId
     * @param array $data
     * @return bool
     * @throws \EasySwoole\Mysqli\Exception\Exception
     * @throws \EasySwoole\ORM\Exception\Exception
     * @throws \Throwable
     */
    public function edit(string $goodsId, array $data)
    {
        $goodsModel = GoodsInfoModel::create()->get(['id' => $goodsId]);
        if (!$goodsModel) {
            return false;
        }
        $goodsModel->entityMappings([
            'name'          => $data['name'],
            'image'         => $data['image']??$goodsModel->getOriginData()['image'],
            'price'         => $data['price'],
            'update_time'   => Date::getInstance()->now()
        ]);
        return $goodsModel->update();
    }

    /**
     * @param string $goodsId
     * @return bool
     * @throws \EasySwoole\ORM\Exception\Exception
     * @throws \Throwable
     */
    public function deleteById(string $goodsId)
    {
        if (!GoodsInfoModel::create()->destroy($goodsId)) {
            throw new \Exception(Common::GOODS_DELETE_FAIL,Status::CODE_BAD_REQUEST);
        }
        return true;
    }
}