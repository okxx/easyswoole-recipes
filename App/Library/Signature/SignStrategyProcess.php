<?php
namespace App\Library\Signature;

/**
 * Sign strategy process
 */
class SignStrategyProcess
{

    private $_strategy;

    public function __construct(SignStrategy $signStrategy)
    {
        $this->_strategy = $signStrategy;
    }

    public function encrypt()
    {
        return $this->_strategy->encrypt();
    }

    public function decrypt() {
        return $this->_strategy->decrypt();
    }
}