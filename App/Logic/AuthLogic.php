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
use EasySwoole\RedisPool\RedisPool;

/**
 * AuthLogic
 */
final class AuthLogic
{
    use Singleton;

    /**
     * 登录
     *
     * @param string $account
     * @param string $password
     * @return array
     * @throws \EasySwoole\Mysqli\Exception\Exception
     * @throws \EasySwoole\ORM\Exception\Exception
     * @throws \Throwable
     */
    public function login(string $account,string $password): array
    {
        $user = UserInfoModel::create()->where('account',$account)->get();
        if (!$user) {
            throw new \Exception(Common::USER_NOTFOUND,Status::CODE_BAD_REQUEST);
        }
        if (Mid::getInstance()->buildPassword($password,$user['salting']) != $user['password']) {
            throw new \Exception(Common::USER_PASSWORD_INCORRECT,Status::CODE_BAD_REQUEST);
        }
        // TaskManager::getInstance()->async(new UserTask());// 用户登录成功的异步任务
        return $user->getOriginData();
    }


    /**
     * @param $account
     * @param $password
     * @param $requestCode
     * @return UserInfoModel|\EasySwoole\ORM\AbstractModel
     * @throws \EasySwoole\Mysqli\Exception\Exception
     * @throws \EasySwoole\ORM\Exception\Exception
     * @throws \Throwable
     */
    public function register($account,$password,$requestCode)
    {
        $salting        = Random::condition(16,5);
        $userInfo       = UserInfoModel::create()->get(['account' => $account]);
        if ($userInfo) {
            throw new \Exception(Common::USER_EXISTS,Status::CODE_BAD_REQUEST);
        }

        // $code = 8888;
        $code = RedisPool::defer('redis')->get($account);
        if (!$code || $requestCode != $code) {
            throw new \Exception(Common::CODE_NOT_EQUAL,Status::CODE_BAD_REQUEST);
        }

        $date = Date::getInstance()->now();
        $userModel = UserInfoModel::create([
            'account'       => $account,
            'password'      => Mid::getInstance()->buildPassword($password,$salting),
            'salting'       => $salting,
            'nickname'      => Random::condition(8,5),
            'create_time'   => $date,
            'update_time'   => $date,
        ]);
        if (!$userModel->save()) {
            throw new \Exception(Common::USER_SAVE_FAIL,Status::CODE_BAD_REQUEST);
        }
        return $userModel;
    }


}