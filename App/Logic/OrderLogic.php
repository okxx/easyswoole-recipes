<?php
namespace App\Logic;

use App\Constants\Common;
use App\HttpController\Order;
use App\Library\Utils\Date;
use App\Models\OrderInfoModel;
use EasySwoole\Component\Singleton;
use EasySwoole\Http\Message\Status;
use EasySwoole\Utility\Random;

final class OrderLogic
{
    use Singleton;

    /**
     * 创建订单
     *
     * @param array $params
     * @return bool|int
     * @throws \Throwable
     */
    public function create(array $params)
    {
        $model = OrderInfoModel::create([
            'on'                    => Random::number(18),// 防止订单编号重复，实际业务中需要制定策略,
            'user_id'               => $params['userId'],
            'payment_on'            => $params['paymentOn'],
            'amount_price'          => $params['amountPrice'],
            'actual_price'          => $params['actualPrice'],
            'goods_id'              => $params['goodsId'],
            'goods_meta'            => $params['goodsMeta'],
            'coupon_id'             => $params['couponId'] ?? 0,
            'coupon_meta'           => $params['couponMeta']??null,
            'payment_expire_time'   => $params['paymentExpireTime'],
            'create_time'           => Date::getInstance()->now(),
            'update_time'           => Date::getInstance()->now(),
        ]);
        return $model->save();
    }


    /**
     * 检查未支付订单
     *
     * @param $userId
     * @param $goodsId
     * @return bool
     * @throws \EasySwoole\Mysqli\Exception\Exception
     * @throws \EasySwoole\ORM\Exception\Exception
     * @throws \Throwable
     */
    public function checkOrderNotPaid($userId,$goodsId): bool
    {
        $orderInfo = OrderInfoModel::create()->get([
            'user_id'       => $userId,
            'goods_id'      => $goodsId,
            'payment_state' => 0,
            'status'        => 0,
        ]);
        if ($orderInfo) {
            throw new \Exception(Common::ORDER_NOT_PAID,Status::CODE_BAD_REQUEST);
        }
        return true;
    }


    /**
     * payment callback action.
     *
     * @param string $orderId
     * @param array $data
     * @return bool
     * @throws \EasySwoole\Mysqli\Exception\Exception
     * @throws \EasySwoole\ORM\Exception\Exception
     * @throws \Throwable
     */
    public function payCallback(string $orderId, array $data): bool
    {
        $model = OrderInfoModel::create()->get(function ($builder) use ($orderId,$data) {
            $builder->selectForUpdate(true)// lock for update.
                ->where('id',$orderId)->where('status',0)->where('payment_state',0)
                ->where('update_time',$data['updateTime'])->where('payment_expire_time',$data['paymentExpireTime'])
                ->get('tc_order_info');
        });
        return $model->update([
            'payment_state' => 1,
            'update_time'   => Date::getInstance()->now(),
        ]);
    }

    /**
     * 订单信息
     *
     * @param string $orderId
     * @return OrderInfoModel|array|bool|\EasySwoole\ORM\AbstractModel|\EasySwoole\ORM\Db\Cursor|\EasySwoole\ORM\Db\CursorInterface|null
     * @throws \EasySwoole\Mysqli\Exception\Exception
     * @throws \EasySwoole\ORM\Exception\Exception
     * @throws \Throwable
     */
    public function info(string $orderId)
    {
        return OrderInfoModel::create()->where('id',$orderId)->get();
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
        $model = OrderInfoModel::create()->limit($limit * ($page - 1), $limit)->withTotalCount();
        if (isset($params['name']) && !empty($params['name'])) {
            $model->where('on',$params['name']);
        }
        if (isset($params['status']) && $params['status'] > -1) {
            $model->where('status',$params['status']);
        }
        if (isset($params['paymentState']) && $params['paymentState'] > -1) {
            $model->where('payment_state',$params['paymentState']);
        }
        return $model->paginate();
    }

}