<?php

namespace EInvoice\tests;

use EInvoice\Core\Core;

class TestSendCheck extends BasicTest {

    /**
     * 发票发送检查
     *
     *
     * Author=> DQ
     */
    public function testSendCheck() {
        try {
            $mainLib = new Core($this->_config);

            $RESULT = [
                'FPQQLSH' => 'P10000011597730600000',
                'NSRSBH'  => $this->_config['data']['NSRSBH'],
                'FP_DM'   => '031001900411',
                'FP_HM'   => '90132096',
                'TSFS'    => Core::TSFS_EMAIL,
                'TSDZ'    => '237661791@qq.com'
            ];

            $return = $mainLib->sendCheck($RESULT);

            $this->assertNotEmpty($return, '发票发送检查 错误');
        } catch (\Exception $e) {
            $this->assertEmpty($e->getMessage(), "");
        }
    }

}