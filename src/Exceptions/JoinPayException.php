<?php

namespace xyqWeb\JoinPay\Exceptions;


use Throwable;

class JoinPayException extends \Exception
{
    private $errorCode;

    public function __construct($message = '', $errorCode = '', $code = 0, Throwable $previous = null)
    {
        $this->errorCode = $errorCode;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return int|string
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

}