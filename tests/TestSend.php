<?php

namespace EInvoice\tests;

use EInvoice\Core\Core;

class TestSend extends BasicTest {

    /**
     * 发票发送
     *
     *
     * Author=> DQ
     */
    public function testSend() {
        try {
            $mainLib = new Core($this->_config);

            $TSFSXX = [
                'TSFS'  => 0,
                'SJ'    => '',
                'EMAIL' => '237661791@qq.com',

            ];
            $FPXX   = [
                [
                    'FPQQLSH' => 'P10000011597730600000',
                    'NSRSBH'  => $this->_config['data']['NSRSBH'],
                    'FP_DM'   => '031001900411',
                    'FP_HM'   => '90132096',
                ]
            ];

            $return = $mainLib->send($TSFSXX, $FPXX);

            $this->assertNotEmpty($return, '发票发送 错误');
        } catch (\Exception $e) {
            $this->assertEmpty($e->getMessage(), "");
        }
    }

}