<?php
namespace App\Models;

use App\Library\Base\AbstractBaseModel;
use EasySwoole\ORM\Utility\Schema\Table;

/**
 * Class UserModel
 * Create With Automatic Generator
 *
 * @property $id int |
 * @property $account string | 用户登录名
 * @property $password string | 用户密码
 * @property $nickname string | 昵称
 * @property $avatar string | 头像
 * @property $salting string | 盐码
 * @property $createTime int | 创建时间
 * @property $updateTime int | 更新时间
 */
class UserInfoModel extends AbstractBaseModel
{
    public $tableName = 'tc_user_info';

    public $connectionName = 'default';

    /**
     * @param bool $isCache
     * @return Table
     */
    public function schemaInfo(bool $isCache = true): Table
    {
        $table = new Table($this->tableName);
        $table->colInt('id')->setIsPrimaryKey()->setIsAutoIncrement();
        $table->colChar('account', 25)->setIsNotNull()->setColumnComment('用户账号');
        $table->colChar('password', 255)->setIsNotNull()->setColumnComment('用户密码');
        $table->colChar('salting', 255)->setIsNotNull()->setColumnComment('用户盐码');
        $table->colChar('nickname', 255)->setIsNotNull(false)->setDefaultValue(null)->setColumnComment('用户昵称');
        $table->colChar('avatar', 255)->setIsNotNull(false)->setDefaultValue(null)->setColumnComment('用户头像');
        $table->colDateTime('create_time')->setIsNotNull(false)->setColumnComment('用户创建时间');
        $table->colDateTime('update_time')->setIsNotNull(false)->setColumnComment('用户更新时间');
        return $table;
    }
}