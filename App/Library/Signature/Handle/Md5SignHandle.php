<?php
namespace App\Library\Signature\Handle;

use App\Library\Signature\SignStrategy;
use App\Models\SignModel;

class Md5SignHandle extends SignModel implements SignStrategy
{
    private $salt = 'R2e0C2i2P0e1S01';

    private $destr = 'recipes';

    public function encrypt(): string
    {
        if ($this->data) {
            $this->destr = http_build_query($this->data, '','&',PHP_QUERY_RFC3986);
        }
        return md5(md5($this->salt). $this->destr);
    }

    public function decrypt(): bool
    {
        if (!isset($this->data['sign'])) {
            return false;
        }
        $this->sign = $this->data['sign'];
        if ($this->sign) {
            unset($this->data['sign']);
            return $this->sign == $this->encrypt();
        }
        return false;

    }
}