<?php
namespace App\Logic;

use App\Constants\Common;
use App\Library\Utils\Date;
use App\Library\Utils\Mid;
use App\Library\Utils\Random;
use App\Models\UserInfoModel;
use App\Task\UserTask;
use EasySwoole\Component\Singleton;
use EasySwoole\EasySwoole\Task\TaskManager;
use EasySwoole\Http\Message\Status;

/**
 * UserLogic
 */
final class UserLogic
{
    use Singleton;

    /**
     * 通过手机号查询账户信息
     *
     * @param string $account
     * @return UserInfoModel|array|bool|\EasySwoole\ORM\AbstractModel|\EasySwoole\ORM\Db\Cursor|\EasySwoole\ORM\Db\CursorInterface|null
     * @throws \EasySwoole\Mysqli\Exception\Exception
     * @throws \EasySwoole\ORM\Exception\Exception
     * @throws \Throwable
     */
    public function infoByAccount(string $account)
    {
        return UserInfoModel::create()->where('account',$account)->get();
    }


    public function page($page,$limit)
    {
        return UserInfoModel::create()->limit($page,$limit)->all();
    }


    /**
     * @param array $ids
     * @return array
     * @throws \EasySwoole\Mysqli\Exception\Exception
     * @throws \EasySwoole\ORM\Exception\Exception
     * @throws \Throwable
     */
    public function userListByIdsToKv(array $ids) :array
    {
        $data = [];
        $model = UserInfoModel::create()->where('id', $ids, 'in')->where('status',0)->all();
        foreach ($model as $val) {
            $item = $val->toArray();
            $data[$item['id']] = $item;
        }
        return $data;
    }

}