<?php
namespace App\Library\Base\Manager;

use EasySwoole\Component\Singleton;

/**
 * param manager
 */
class RequestParams
{
    use Singleton;

    public $data = [];

    public function __construct(array $verifiedData)
    {
        var_dump($verifiedData);
        $this->data = $verifiedData;
    }

    public function get(string $name)
    {
        return $this->data[$name];
    }

    public function dump() {
        var_dump($this->data);
    }

}