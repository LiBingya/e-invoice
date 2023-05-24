<?php

namespace EInvoice\Kernel;

use EInvoice\Exceptions\InvalidArgumentException;
use EInvoice\Exceptions\InvalidResponseException;
use EInvoice\Tools\DataArray;
use EInvoice\Tools\DataTransform;
use EInvoice\Tools\RequestTool;

class Basic {

    const VERSION = '1.1';

    public $config;

    public $url = null;

    public $key = null;

    // access token
    public $sign = '';

    // 当前请求方法
    private $_currentMethod = [];

    // 是否是重试
    private $_isTry = false;

    public function __construct(array $options) {


        //        if (!isset($options['config']['terminalCode']) || !in_array($options['config']['terminalCode'], [0, 1])) {
        //            throw new InvalidArgumentException('miss config [terminalCode]');
        //        }
        if (!isset($options['config']['appId']) || !in_array($options['config']['appId'], ['DZFP', 'ZZS_PT_DZFP'])) {
            throw new InvalidArgumentException('miss config [appId]');
        }
        if (!isset($options['config']['version'])) {
            $options['config']['version'] = '1.1';
        }
        if (!isset($options['config']['userName'])) {
            throw new InvalidArgumentException('miss config [userName]');
        }
        //        if (!isset($options['config']['passWord'])) {
        //            throw new InvalidArgumentException('miss config [passWord]');
        //        }
        if (!isset($options['config']['requestCode'])) {
            $options['config']['requestCode'] = $options['config']['userName'];
        }
        if (!isset($options['config']['taxpayerId'])) {
            throw new InvalidArgumentException('miss config [taxpayerId]');
        }
        if (!isset($options['config']['authorizationCode'])) {
            throw new InvalidArgumentException('miss config [authorizationCode]');
        }
        if (!isset($options['config']['responseCode'])) {
            $options['config']['responseCode'] = 121;
        }
        $this->config = $options['config'];
        $this->url    = $options['url'];
        $this->key    = $options['key'];
    }

    /**
     * 获取灵声版本号
     * @return string
     * Author: DQ
     */
    public function getVersion() {
        return self::VERSION;
    }

    /**
     * 遇到错误是否再次尝试
     *
     * @param bool $bool
     *                  true 再次尝试
     *                  false 不会再次尝试
     *                  Author: DQ
     */
    public function tryAgain($bool = false) {
        $this->_isTry = $bool == false;
    }

    public function getUrl($uri = '') {
        return sprintf("%s%s", $this->url);
    }

    /**
     * 注册请求
     *
     * @param       $method
     *                        方法
     * @param array $arguments
     *                        参数
     *
     * @throws \ErrorException
     * @throws \ListenRobot\Exceptions\InvalidResponseException
     * @throws \ListenRobot\Exceptions\LocalCacheException
     * Author: DQ
     */
    protected function registerApi($method, $arguments = []) {
        $this->_currentMethod = ['method' => $method, 'arguments' => $arguments];
    }

    /**
     * post 请求返回json 数组
     *
     * @param       $url
     * @param       $data
     * @param array $headers
     *
     * @return mixed
     * @throws \ErrorException
     * @throws \EInvoice\Exceptions\InvalidResponseException
     * Author: DQ
     */
    public function httpPostJson($url, $data, $headers = []) {
        try {
            $this->registerApi(__FUNCTION__, func_get_args());
            $response = RequestTool::post($url, $data, $headers);
            $result   = DataTransform::xml2array($response);

            return $result;
        } catch (InvalidResponseException $e) {
            if (!$this->_isTry) {
                $this->_isTry = true;

                return call_user_func_array([
                    $this,
                    $this->_currentMethod['method']
                ], $this->_currentMethod['arguments']);
            }

            throw new InvalidResponseException($e->getMessage(), $e->getCode());
        }
    }

    protected function _encrypt($xml, $key) {
        return openssl_encrypt($xml, 'DES-EDE3', $key);
    }

    protected function _decrypt($xml = [], $key) {
        $content = "";
        if ($xml['Data']['dataDescription']['zipCode'] == 0) {
            $content = openssl_decrypt(base64_decode($xml['Data']['content']), 'DES-EDE3', $key);
        } else {
            $content = openssl_decrypt(zlib_decode(base64_decode($xml['Data']['content'])), 'DES-EDE3', $key);
        }
        if ($content) {
            // 控制字符
            $content = preg_replace('/[[:cntrl:]]/', '', $content);
            $data    = simplexml_load_string($content);
            $json    = json_encode($data);
            $rs      = json_decode($json, true);

            $xml['Data']['content'] = $rs;
        }

        return $xml;
    }

}