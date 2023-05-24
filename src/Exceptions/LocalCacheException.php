<?php

namespace EInvoice\Exceptions;

/**
 * 本地缓存错误
 * Class LocalCacheException
 * Author: DQ
 * @package ListenRobot\Exceptions
 */
class LocalCacheException extends \Exception {
    public $raw = [];

    public function __construct($message = "", $code = 0, $raw = []) {
        parent::__construct($message, intval($code));
        $this->raw = $raw;
    }
}