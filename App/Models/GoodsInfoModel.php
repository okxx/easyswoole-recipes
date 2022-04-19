<?php
namespace App\Models;

use App\Library\Base\AbstractBaseModel;
use EasySwoole\ORM\Utility\Schema\Table;

/**
 * Class GoodsInfoModel
 * Create With Automatic Generator
 *
 * @property $id int |
 * @property $name string | 商品名称
 * @property $image string | 商品图片
 * @property $price double | 商品金额
 * @property $createTime int | 创建时间
 * @property $updateTime int | 更新时间
 */
class GoodsInfoModel extends AbstractBaseModel
{
    public $tableName = 'tc_goods_info';

    public $connectionName = 'default';

    /**
     * @param bool $isCache
     * @return Table
     */
    public function schemaInfo(bool $isCache = true): Table
    {
        $table = new Table($this->tableName);
        $table->colInt('id')->setIsPrimaryKey()->setIsAutoIncrement();
        $table->colChar('name', 25)->setIsNotNull()->setColumnComment('商品名称');
        $table->colChar('image', 255)->setIsNotNull()->setColumnComment('商品图片');
        $table->colChar('price', 255)->setIsNotNull()->setColumnComment('商品金额');
        $table->colDateTime('create_time')->setIsNotNull(false)->setColumnComment('创建时间');
        $table->colDateTime('update_time')->setIsNotNull(false)->setColumnComment('更新时间');
        return $table;
    }
}