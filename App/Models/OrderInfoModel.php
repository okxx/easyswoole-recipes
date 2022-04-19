<?php
namespace App\Models;

use App\Library\Base\AbstractBaseModel;
use App\Logic\UserLogic;
use EasySwoole\ORM\Utility\Schema\Table;

/**
 * Class OrderInfoModel
 * Create With Automatic Generator
 *
 * @property $id int | 订单id
 * @property $on string | 订单编号(order number)
 * @property $paymentOn string | 第三方支付单号
 * @property $amountPrice double | 应付金额
 * @property $actualPrice double | 实付金额
 * @property $userId string | 用户id
 * @property $goodsId string | 支付商品的元数据
 * @property $goodsMeta string | 支付商品的元数据
 * @property $couponId string | 优惠券id
 * @property $couponMeta string | 优惠券元数据
 * @property $paymentState dateTime | 支付状态
 * @property $paymentExpireTime dateTime | 付款到期时间
 * @property $createTime dateTime | 创建时间
 * @property $updateTime dateTime | 更新时间
 */
class OrderInfoModel extends AbstractBaseModel
{
    public $tableName = 'tc_order_info';

    public $connectionName = 'default';

    /**
     * @param bool $isCache
     * @return Table
     */
    public function schemaInfo(bool $isCache = true): Table
    {
        $table = new Table($this->tableName);
        $table->colInt('id')->setIsPrimaryKey()->setIsAutoIncrement();
        $table->colVarChar('on', 255)->setIsNotNull()->setColumnComment('订单编号');
        $table->colVarChar('payment_on', 255)->setIsNotNull()->setColumnComment('第三方支付单号');
        $table->colDouble('amount_price', 12,2)->setIsNotNull()->setColumnComment('应付金额');
        $table->colVarChar('actual_price', 255)->setIsNotNull()->setColumnComment('实付金额');
        $table->colInt('user_id')->setIsNotNull()->setColumnComment('用户id');
        $table->colText('goods_id')->setIsNotNull(false)->setColumnComment('订单编号');
        $table->colText('goods_meta')->setIsNotNull(false)->setColumnComment('商品元数据');
        $table->colInt('coupon_id')->setIsNotNull(false)->setColumnComment('优惠券id');
        $table->colText('coupon_meta')->setIsNotNull(false)->setColumnComment('优惠券元数据');
        $table->colTinyInt('payment_state',1)->setDefaultValue(0)->setColumnComment('支付状态: 0=未支付 1=已支付');
        $table->colDateTime('payment_expire_time')->setIsNotNull(false)->setColumnComment('付款到期时间[NULL:永不过期]');
        $table->colDateTime('status')->setIsNotNull(false)->setDefaultValue(0)->setColumnComment('订单状态：0=有效 1=无效');
        $table->colDateTime('create_time')->setIsNotNull(false)->setColumnComment('创建时间');
        $table->colDateTime('update_time')->setIsNotNull(false)->setColumnComment('更新时间');
        return $table;
    }

    /**
     * @throws \Throwable
     * @throws \EasySwoole\ORM\Exception\Exception
     */
    public function paginate(): array
    {
        $data = [];
        $model = $this->all();
        $total = $this->lastQueryResult()->getTotalCount();
        if ($total > 0) {
            $userIdList = [];
            foreach ($model as $value) {
                $userIdList[] = $value->getOriginData()['user_id']; // 提取用户id
            }

            // 查询用户信息
            $users = UserLogic::getInstance()->userListByIdsToKv($userIdList);
            foreach ($model as $val) {
                $item = $val->toArray(); // 驼峰

                $item['paymentPlatform']    = "平台下单";

                // 商品元数据
                $item['goodsMeta']          = json_decode($item['goodsMeta']);

                // 用户信息
                $item['userInfo']           = $users[$item['userId']];

                // 优惠券信息

                // 订单状态
                $item['status']             = $item['status'] == 1?"无效":"有效";
                $item['paymentState']       = $item['paymentState'] == 1?"已支付":"未支付";
                $data[] = $item;
            }
        }
        return ['list' => $data,'total' => $total];
    }
}