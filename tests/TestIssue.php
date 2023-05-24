<?php

namespace EInvoice\tests;

use EInvoice\Core\Core;

class TestIssue extends BasicTest {

    /**
     * 发票开票
     *
     *
     * Author=> DQ
     */
    public function testIssue() {
        try {
            $mainLib = new Core($this->_config);

            $FPKJXX_FPTXX = [
                'FPQQLSH'    => sprintf("%s%s", $this->_config['data']['DSPTBM'], time() . '000'),
                'DSPTBM'     => $this->_config['data']['DSPTBM'],
                'NSRSBH'     => $this->_config['data']['NSRSBH'],
                'NSRMC'      => $this->_config['data']['NSRMC'],
                'DKBZ'       => 0,
                'KPXM'       => '其他住房租赁服务',
                'BMB_BBH'    => '35.0',
                'XHF_NSRSBH' => $this->_config['config']['taxpayerId'],
                'XHFMC'      => $this->_config['data']['XHFMC'],
                'XHF_DZ'     => '云锦路500号',
                'GHFMC'      => '张三',
                'KPY'        => '李四',
                'GHFQYLX'    => '03',
                'KPLX'       => '1',
                'CZDM'       => '10',
                'QD_BZ'      => '0',
                'KPHJJE'     => 0.01,
                'HJBHSJE'    => 0,
                'HJSE'       => 0,

            ];

            $FPKJXX_XMXXS = [
                [
                    'XMMC'   => '*经营租赁*房租',
                    'HSBZ'   => '1',
                    'FPHXZ'  => '0',
                    'XMDJ'   => 0.01,
                    'SPBM'   => str_pad($this->_config['data']['SPBM'], 19, '0', STR_PAD_RIGHT),
                    'YHZCBS' => $this->_config['data']['YHZCBS'],
                    'XMJE'   => 0.01,
                    'SL'     => $this->_config['data']['SL'],
                ],
            ];

            $FPKJXX_DDXX = [
                'DDH' => time()
            ];

            $return = $mainLib->issue($FPKJXX_FPTXX, $FPKJXX_XMXXS, $FPKJXX_DDXX);

            $this->assertNotEmpty($return, '发票开票 错误');
        } catch (\Exception $e) {
            $this->assertEmpty($e->getMessage(), "");
        }
    }

}