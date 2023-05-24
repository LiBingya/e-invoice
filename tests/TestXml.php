<?php

namespace EInvoice\tests;

use EInvoice\Tools\ThreeDesUtil;
use EInvoice\Tools\Xml;

class TestXml extends BasicTest {

    /**
     * xml 构建
     *
     *
     * Author=> DQ
     */
    public function testBuildFpkjxx() {
        $xml = new Xml();

        $FPKJXX_FPTXX = [
            'FPQQLSH' => 'asdfasfd'
        ];
        $FPKJXX_XMXXS = [
            [
                'XMMC' => '中文',
                'XMDW' => '单位',
            ],
            [
                'XMMC' => 'subject',
                'XMDW' => 'work',
            ],
        ];
        $FPKJXX_DDXX  = [
            'DDH' => '订单号'
        ];
        $stringXml = $xml->buildFpkjxx($FPKJXX_FPTXX, $FPKJXX_XMXXS, $FPKJXX_DDXX);
        $this->assertEquals($stringXml, '<FPKJXX_FPTXX class="FPKJXX_FPTXX"><FPQQLSH>asdfasfd<FPQQLSH></FPKJXX_FPTXX><FPKJXX_XMXXS class="FPKJXX_XMXX;" size="2"><FPKJXX_XMXX><XMMC>中文<XMMC><XMDW>单位<XMDW></FPKJXX_XMXX><FPKJXX_XMXX><XMMC>subject<XMMC><XMDW>work<XMDW></FPKJXX_XMXX></FPKJXX_XMXXS><FPKJXX_FPTXX class="FPKJXX_FPTXX"><DDH>订单号<DDH></FPKJXX_FPTXX>', 'xml 生成错误');
    }

}