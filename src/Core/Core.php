<?php

namespace EInvoice\Core;

use EInvoice\Kernel\Basic;
use EInvoice\Tools\Xml;

class Core extends Basic {

    // 推送方式 0邮箱 1手机号码
    const TSFS_EMAIL = 0;
    const TSFS_PHONE = 1;

    //发送结果
    const RETURN_SUCC           = '0000';
    const SEND_RETURN_CODE_FAIL = '9999';

    /**
     * 开票 接口
     *
     * @param array $FPKJXX_FPTXX
     * @param array $FPKJXX_XMXXS 二维数组
     * @param array $FPKJXX_DDXX
     *
     * @return mixed
     * @throws \ErrorException
     * @throws \EInvoice\Exceptions\InvalidResponseException
     * Author: DQ
     */
    public function issue($FPKJXX_FPTXX = [], $FPKJXX_XMXXS = [], $FPKJXX_DDXX = [], $FPKJXX_BDCZL = []) {
        $this->config['interfaceCode'] = 'ECXML.FPKJ.BC.E_INV';
        $config                        = $this->config;
        $config['requestTime']         = date('Y-m-d H:i:s s');
        $config['dataExchangeId']      = $this->config['userName'] . date('Ymd') . rand(100000000, 999999999);

        $xml = new Xml();

        // 使用 优惠政策标识
        if ($FPKJXX_XMXXS) {
            foreach ($FPKJXX_XMXXS as $key => $val) {
                if ($val['YHZCBS'] == 1) {
                    if (!isset($val['LSLBS'])) {
                        $FPKJXX_XMXXS[ $key ]['LSLBS'] = "1";
                    }
                    if (!isset($val['ZZSTSGL'])) {
                        $FPKJXX_XMXXS[ $key ]['ZZSTSGL'] = "免税";
                    }
                }
            }
        }

        $content = $xml->buildFpkjxx($FPKJXX_FPTXX, $FPKJXX_XMXXS, $FPKJXX_DDXX, $FPKJXX_BDCZL);

        $encrypt = $this->_encrypt($content, $this->key);

        $xmlText   = $xml->build();
        $xmlPublic = $xml->buildPublic($config);

        $fullText = sprintf($xmlText, $xmlPublic, $encrypt);

        return $this->httpPostJson($this->url, $fullText);
    }

    /**
     * 发票信息(不包含明细)下载API
     *
     * @param array $REQUEST_FPXXXZ_NEW
     *
     * @return mixed
     * @throws \ErrorException
     * @throws \EInvoice\Exceptions\InvalidResponseException
     * Author: DQ
     */
    public function download($REQUEST_FPXXXZ_NEW = []) {
        $this->config['interfaceCode'] = 'ECXML.FPXZ.CX.E_INV';
        $config                        = $this->config;
        $config['requestTime']         = date('Y-m-d H:i:s s');
        $config['dataExchangeId']      = $this->config['userName'] . date('Ymd') . rand(100000000, 999999999);

        $xml     = new Xml();
        $content = $xml->buildFpxxxzNew($REQUEST_FPXXXZ_NEW);

        $encrypt   = $this->_encrypt($content, $this->key);
        $xmlText   = $xml->build();
        $xmlPublic = $xml->buildPublic($config);

        $fullText = sprintf($xmlText, $xmlPublic, $encrypt);
        $result   = $this->httpPostJson($this->url, $fullText);
        $data     = $this->_decrypt($result, $this->key);

        return $data;
    }

    /**
     * 邮箱发送API
     *
     *
     * @param array $TSFSXX 邮箱发票推送-推送方式信息
     * @param array $FPXX   邮箱发票推送-发票信息（多条） 二维数组
     *
     *
     * @return array
     * @throws \ErrorException
     * @throws \EInvoice\Exceptions\InvalidResponseException
     * Author: DQ
     */
    public function send($TSFSXX = [], $FPXX = []) {
        $this->config['interfaceCode'] = 'ECXML.EMAILPHONEFPTS.TS.E.INV';
        $config                        = $this->config;
        $config['requestTime']         = date('Y-m-d H:i:s s');
        $config['dataExchangeId']      = $this->config['userName'] . date('Ymd') . rand(100000000, 999999999);

        $xml     = new Xml();
        $content = $xml->buildEmailPhonefpts($TSFSXX, $FPXX);

        $encrypt   = $this->_encrypt($content, $this->key);
        $xmlText   = $xml->build();
        $xmlPublic = $xml->buildPublic($config);

        $fullText = sprintf($xmlText, $xmlPublic, $encrypt);

        $result = $this->httpPostJson($this->url, $fullText);
        $data   = $this->_decrypt($result, $this->key);

        return $data;
    }

    /**
     * 邮件发送结果查询API
     *
     * @param array $RESULT
     *
     * @return array
     * @throws \EInvoice\Exceptions\InvalidResponseException
     * @throws \ErrorException
     * Author: DQ
     */
    public function sendCheck($RESULT = []) {
        $this->config['interfaceCode'] = 'ECXML.EMAILPHONEFPTS.TS.RESULT';
        $config                        = $this->config;
        $config['requestTime']         = date('Y-m-d H:i:s s');
        $config['dataExchangeId']      = $this->config['userName'] . date('Ymd') . rand(100000000, 999999999);

        $xml     = new Xml();
        $content = $xml->buildEmailPhoneResult($RESULT);

        $encrypt   = $this->_encrypt($content, $this->key);
        $xmlText   = $xml->build();
        $xmlPublic = $xml->buildPublic($config);

        $fullText = sprintf($xmlText, $xmlPublic, $encrypt);

        $result = $this->httpPostJson($this->url, $fullText);
        $data   = $this->_decrypt($result, $this->key);

        return $data;
    }

}
