<?php
namespace App\HttpController;

use App\Constants\Common;
use App\Library\Base\AbstractController;
use App\Logic\CouponLogic;
use App\Logic\GoodsLogic;
use App\Logic\OrderLogic;
use EasySwoole\Http\Message\Status;
use EasySwoole\ORM\DbManager;
use EasySwoole\RedisPool\RedisPool;
use EasySwoole\Utility\Random;

class Order extends AbstractController
{

    /**
     * @return bool
     * @throws Exception
     * @throws \Throwable
     */
    public function paginate(): bool
    {
        $goodsList = OrderLogic::getInstance()->pageList($this->request()->getRequestParam());
        return $this->JsonResponse(Status::CODE_OK,Common::SUCCESS,$goodsList);
    }

    /**
     * 创建预支付订单：
     *  1.校验商品
     *  2.检测优惠流程
     *  3.创建预支付订单
     *
     * @return bool
     * @throws \EasySwoole\Mysqli\Exception\Exception
     * @throws \EasySwoole\ORM\Exception\Exception
     * @throws \Throwable
     */
    public function create()
    {
        $this->validator([
            'goodsId'   => 'required|notEmpty',//商品id
        ]);

        $orderParams                      = $this->request()->getRequestParam();
        $orderParams['userId']            = $this->getUserInfo()->id;// 用户id
        $orderParams['paymentOn']         = Random::number(26);// todo 暂时不接入支付平台，随机生成一个第三方单号
        $orderParams['paymentExpireTime'] = date('Y-m-d H:i:s',strtotime('+1 hour',time()));// 订单支付的过期时间（一个小时）
        try {
            $goodsInfo = GoodsLogic::getInstance()->info($orderParams['goodsId']);
            if (!$goodsInfo) {
                return $this->JsonResponse(Status::CODE_BAD_REQUEST,Common::GOODS_NOT_FOUND);
            }

            // NOTE:获取商品金额,赋值应付金额
            $orderParams['goodsMeta']     = json_encode($goodsInfo->getOriginData());
            $orderParams['amountPrice']   = $orderParams['actualPrice'] = $goodsInfo->price;

            // NOTE:检查用户下相同商品是否存在未支付订单
            OrderLogic::getInstance()->checkOrderNotPaid($orderParams['userId'],$orderParams['goodsId']);

            // NOTE:优惠券
            if(!empty($orderParams['couponId'])) { // 优惠券字段不为空
                $couponId = $orderParams['couponId'];

                // NOTE:查询并校验优惠券
                $couponInfo = CouponLogic::getInstance()->info($couponId);
                if (!$couponInfo) {
                    return $this->JsonResponse(Status::CODE_BAD_REQUEST,Common::COUPON_NOT_FOUND);
                }

                // NOTE:更新实付金额
                $orderParams['actualPrice'] = bcsub($orderParams['amountPrice'],$couponInfo->price,2);

                // NOTE:锁住优惠券和预支付订单一起释放。
                if (!RedisPool::defer('redis')->set('COUPON-'.$couponId,$orderParams['paymentExpireTime'],['NX','EX' => Common::EXPIRE_HOUR])) {
                    return $this->JsonResponse(Status::CODE_BAD_REQUEST,Common::COUPON_ARE_USED);
                }
            }

            // NOTE:创建未支付订单
            $createOrder = OrderLogic::getInstance()->create($orderParams);
            if (!$createOrder) {
                return $this->JsonResponse(Status::CODE_BAD_REQUEST,Common::ORDER_CREATE_FAIL);
            }

            // todo 生成第三方支付订单返回给客户端...

            return $this->JsonResponse(Status::CODE_OK,Common::SUCCESS,[
                'no'        => $createOrder,
                'paymentNo' => $orderParams['paymentOn'],
            ]);
        } catch (\Exception $e) {
            if(!empty($orderParams['couponId'])) {
                RedisPool::defer('redis')->del('COUPON-'.$orderParams['couponId']);// 异常释放
            }
            return $this->JsonResponse($e->getCode(),$e->getMessage());
        }
    }


    /**
     * @return bool
     * @throws \Throwable
     */
    public function callback()
    {
        $this->validator([
            'orderId'   => 'required|Integer',
            'paymentOn' => 'required|Integer'
        ]);

        try {
            $orderId    = $this->request()->getRequestParam('orderId');
            $paymentOn  = $this->request()->getRequestParam('paymentOn');
            $orderInfo  = OrderLogic::getInstance()->info($orderId);
            if (!$orderInfo) {
                return $this->JsonResponse(Status::CODE_BAD_REQUEST,Common::ORDER_NOT_FOUND);
            }

            // start transaction
            DbManager::getInstance()->startTransaction();

            // callback 方法执行mysql行锁
            $callbackAction = OrderLogic::getInstance()->payCallback($orderId,[
                'paymentOn'             => $paymentOn,
                'updateTime'            => $orderInfo->getOriginData()['update_time'],
                'paymentExpireTime'     => $orderInfo->getOriginData()['payment_expire_time']
            ]);
            if (!$callbackAction) {
                DbManager::getInstance()->rollback();
                return $this->JsonResponse(Status::CODE_BAD_REQUEST,Common::ORDER_CALLBACK_FAIL);
            }

            // commit transaction
            DbManager::getInstance()->commit();
            return $this->JsonResponse(Status::CODE_OK,Common::SUCCESS,[
                'orderId'   => $orderId,
            ]);
        } catch (\Exception $e) {
            DbManager::getInstance()->rollback();
            return $this->JsonResponse(Status::CODE_BAD_REQUEST,$e->getMessage());
        }
    }


}