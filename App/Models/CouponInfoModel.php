<?php
namespace App\Models;

use App\Library\Base\AbstractBaseModel;
use EasySwoole\ORM\Utility\Schema\Table;

/**
 * Class CouponInfoModel
 *
 * Create With Automatic Generator
 *
 * @property $id int | 订单id
 * @property $name string | 优惠券名称
 * @property $price double | 优惠金额
 * @property $status tinyint | 优惠券状态
 * @property $createTime dateTime | 创建时间
 * @property $updateTime dateTime | 更新时间
 *
 */
class CouponInfoModel extends AbstractBaseModel
{
    public $tableName = 'tc_coupon_info';

    public $connectionName = 'default';

    /**
     * @param bool $isCache
     * @return Table
     */
    public function schemaInfo(bool $isCache = true): Table
    {
        $table = new Table($this->tableName);
        $table->colInt('id')->setIsPrimaryKey()->setIsAutoIncrement();
        $table->colVarChar('name', 25)->setIsNotNull()->setColumnComment('优惠券名称');
        $table->colDouble('price', 12,2)->setIsNotNull()->setColumnComment('优惠券金额');
        $table->colTinyInt('status',1)->setDefaultValue(0)->setColumnComment('优惠券状态：0=有效 1=无效');
        $table->colDateTime('create_time')->setIsNotNull(false)->setColumnComment('创建时间');
        $table->colDateTime('update_time')->setIsNotNull(false)->setColumnComment('更新时间');
        return $table;
    }
}