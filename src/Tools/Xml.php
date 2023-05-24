<?php

namespace EInvoice\Tools;

class Xml {
    protected $config = null;

    public function __construct($config = []) {
        $this->config = $config;
    }

    public function build() {
        $content = <<<EOD
<?xml version="1.0" encoding="utf-8"?>
            <interface xmlns="" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.chinatax.gov.cn/tirip/dataspec/interfaces.xsd" version="DZFP1.0">
                <globalInfo>
                    <terminalCode>1</terminalCode>
                    <passWord/>
                    %s
                </globalInfo>
                <returnStateInfo>
                    <returnCode></returnCode>
                    <returnMessage></returnMessage>
                </returnStateInfo>
                <Data>
                    <dataDescription>
                        <zipCode>0</zipCode>
                        <encryptCode>1</encryptCode>
                        <codeType>3DES</codeType>
                    </dataDescription>
                    <content>%s</content>
                </Data>
            </interface>
EOD;

        return $content;
    }

    public function buildPublic($config) {
        $str = "";
        foreach ($config as $key => $val) {
            $str .= $this->_keyToXml($key, $val);
        }

        return $str;
    }

    /**
     * 发票开具拼凑数据
     *
     * Author: DQ
     */
    public function buildFpkjxx($FPKJXX_FPTXX = [], $FPKJXX_XMXXS = [], $FPKJXX_DDXX = [],$FPKJXX_BDCZL = []) {

        $FPKJXX_FPTXX_STRING = "";
        foreach ($FPKJXX_FPTXX as $key => $val) {
            $FPKJXX_FPTXX_STRING .= $this->_keyToXml($key, $val);
        }
        $FPKJXX_FPTXX_STRING = sprintf('<FPKJXX_FPTXX class="FPKJXX_FPTXX">%s</FPKJXX_FPTXX>', $FPKJXX_FPTXX_STRING);

        $FPKJXX_XMXXS_STRING = "";
        foreach ($FPKJXX_XMXXS as $key => $val) {
            $tmp = '';
            foreach ($val as $itemKey => $itemVal) {
                $tmp .= $this->_keyToXml($itemKey, $itemVal);
            }
            $FPKJXX_XMXXS_STRING .= sprintf("<FPKJXX_XMXX>%s</FPKJXX_XMXX>", $tmp);

        }
        $FPKJXX_XMXXS_STRING = sprintf('<FPKJXX_XMXXS class="FPKJXX_XMXX;" size="%d">%s</FPKJXX_XMXXS>', count($FPKJXX_XMXXS), $FPKJXX_XMXXS_STRING);

        $FPKJXX_DDXX_STRING = "";
        foreach ($FPKJXX_DDXX as $key => $val) {
            $FPKJXX_DDXX_STRING .= $this->_keyToXml($key, $val);
        }
        $FPKJXX_DDXX_STRING = sprintf('<FPKJXX_DDXX class="FPKJXX_DDXX">%s</FPKJXX_DDXX>', $FPKJXX_DDXX_STRING);

        $FPKJXX_BDCZL_STRING = "";
        foreach ($FPKJXX_BDCZL as $key => $val) {
            $FPKJXX_BDCZL_STRING .= $this->_keyToXml($key, $val);
        }
        $FPKJXX_BDCZL_STRING = sprintf('<FPKJXX_BDCZL class="FPKJXX_BDCZL">%s</FPKJXX_BDCZL>', $FPKJXX_BDCZL_STRING);


        return sprintf('<REQUEST_FPKJXX class="REQUEST_FPKJXX">%s</REQUEST_FPKJXX>', $FPKJXX_FPTXX_STRING . $FPKJXX_XMXXS_STRING . $FPKJXX_DDXX_STRING . $FPKJXX_BDCZL_STRING);
    }

    /**
     * 下载发票拼凑数据
     *
     * @param array $FPKJXX_FPTXX
     *
     * @return string
     * Author: DQ
     */
    public function buildFpxxxzNew($FPKJXX_FPTXX = []) {
        $FPKJXX_FPTXX_STRING = "";
        foreach ($FPKJXX_FPTXX as $key => $val) {
            $FPKJXX_FPTXX_STRING .= $this->_keyToXml($key, $val);
        }

        return sprintf('<REQUEST_FPXXXZ_NEW class="REQUEST_FPXXXZ_NEW">%s</REQUEST_FPXXXZ_NEW>', $FPKJXX_FPTXX_STRING);
    }

    /**
     * 邮箱发送 拼凑数据
     *
     * @return string
     * Author: DQ
     */
    public function buildEmailPhonefpts($TSFSXX = [], $FPXX = []) {
        $TSFSXX_STRING = "";
        foreach ($TSFSXX as $key => $val) {
            $TSFSXX_STRING .= $this->_keyToNode($key, $val);
        }
        $TSFSXX_STRING = sprintf('<TSFSXX class="TSFSXX"><COMMON_NODES class="COMMON_NODE;" size="%d">%s</COMMON_NODES></TSFSXX>', count($TSFSXX), $TSFSXX_STRING);

        $FPXX_STRING = "";
        foreach ($FPXX as $key => $val) {
            $tmp = '';
            foreach ($val as $itemKey => $itemVal) {
                $tmp .= $this->_keyToNode($itemKey, $itemVal);
            }
            $FPXX_STRING .= sprintf('<FPXX><COMMON_NODES class="COMMON_NODE;" size="%d">%s</COMMON_NODES></FPXX>', count($val), $tmp);

        }
        $FPXX_STRING = sprintf('<FPXXS class="FPXX;" size="%d">%s</FPXXS>', count($FPXX), $FPXX_STRING);

        return sprintf('<REQUEST_EMAILPHONEFPTS class="REQUEST_EMAILPHONEFPTS">%s</REQUEST_EMAILPHONEFPTS>', $TSFSXX_STRING . $FPXX_STRING);
    }


    /**
     * 邮件发送结果查询 拼凑数据
     *
     * @return string
     * Author: DQ
     */
    public function buildEmailPhoneResult($RESULT = []) {
        $RESULT_STRING = "";
        foreach ($RESULT as $key => $val) {
            $RESULT_STRING .= $this->_keyToXml($key, $val);
        }

        return sprintf('<REQUEST_EMAILPHONEFPTS_RESULT class="REQUEST_EMAILPHONEFPTS_RESULT">%s</REQUEST_EMAILPHONEFPTS_RESULT>', $RESULT_STRING);
    }

    protected function _keyToXml($key, $val) {
        return sprintf("<%s>%s</%s>", $key, $val, $key);
    }

    protected function _keyToNode($key, $val) {
        return sprintf('<COMMON_NODE><NAME>%s</NAME><VALUE>%s</VALUE></COMMON_NODE>', $key, $val);
    }

}