<?php
namespace App\Logic;

use App\Library\Utils\Date;
use App\Models\CouponInfoModel;
use App\Models\OrderInfoModel;
use EasySwoole\Component\Singleton;
use EasySwoole\Utility\Random;
use EasySwoole\Utility\Time;

final class CouponLogic
{
    use Singleton;

    /**
     * 优惠券信息
     *
     * @param string $couponId
     * @return CouponInfoModel|array|bool|\EasySwoole\ORM\AbstractModel|\EasySwoole\ORM\Db\Cursor|\EasySwoole\ORM\Db\CursorInterface|null
     * @throws \EasySwoole\Mysqli\Exception\Exception
     * @throws \EasySwoole\ORM\Exception\Exception
     * @throws \Throwable
     */
    public function info(string $couponId)
    {
        return CouponInfoModel::create()->where('id',$couponId)->where('status',0)->get();
    }

}