<?php
namespace App\Library\Utils;

use EasySwoole\Component\Singleton;

/**
 * Date util
 */
final class Date
{
    use Singleton;

    /**
     * 当前时间
     *
     * @param string $format
     * @return false|string
     */
    public function now(string $format = 'Y-m-d H:i:s')
    {
        return date($format);
    }

    /**
     * @param string $format
     * @return false|string
     */
    public function condition(string $format = 'Y-m-d H:i:s')
    {
        return date($format);
    }

}