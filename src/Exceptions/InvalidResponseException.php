<?php

namespace EInvoice\Exceptions;

/**
 * 相应异常
 * Class InvalidResponseException
 * Author: DQ
 * @package ListenRobot\Exceptions
 */
class InvalidResponseException extends \Exception {
    public $raw = [];

    public function __construct($message = "", $code = 0, $raw = []) {
        parent::__construct($message, intval($code));
        $this->raw = $raw;
    }
}