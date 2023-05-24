<?php

namespace EInvoice\tests;

use EInvoice\Core\Core;

class TestDownload extends BasicTest {

    /**
     * 发票下载
     *
     *
     * Author=> DQ
     */
    public function testDownload() {
        try {
            $mainLib = new Core($this->_config);

            $REQUEST_FPXXXZ_NEW = [
                'DDH'      => 1597730600,
                'FPQQLSH'  => 'P10000011597730600000',
                'DSPTBM'   => $this->_config['data']['DSPTBM'],
                'NSRSBH'   => $this->_config['data']['NSRSBH'],
                'PDF_XZFS' => 3,

            ];

            $return = $mainLib->download($REQUEST_FPXXXZ_NEW);

            $this->assertNotEmpty($return, '发票下载 错误');
        } catch (\Exception $e) {
            $this->assertEmpty($e->getMessage(), "");
        }
    }

}