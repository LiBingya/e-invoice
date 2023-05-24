<?php

namespace EInvoice\Tools;

use EInvoice\Core\Core;
use EInvoice\Exceptions\InvalidResponseException;

class DataTransform {

    public static function xml2array($xml) {
        $data = simplexml_load_string($xml);
        $json = json_encode($data);
        $rs   = json_decode($json, true);
        if (empty($rs)) {
            throw new InvalidResponseException('invalid response.', 0);
        }
        if (isset($rs['returnStateInfo']['returnCode']) && $rs['returnStateInfo']['returnCode'] != Core::RETURN_SUCC) {
            throw new InvalidResponseException(is_string($rs['returnStateInfo']['returnMessage']) ? base64_decode($rs['returnStateInfo']['returnMessage']) : null, $rs['returnStateInfo']['returnCode']);
        }
        return $rs;
    }
}