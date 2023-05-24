<?php

namespace EInvoice\tests;

use PHPUnit\Framework\TestCase;

class BasicTest extends TestCase {

    // 配置
    protected $_config = null;

    // 基础数据
    protected $_data = null;

    public function __construct($name = null, array $data = [], $dataName = '') {
        parent::__construct($name, $data, $dataName);
        $this->_config = include 'tests/config.php';
        $this->_data   = include 'tests/data.php';
    }

    public function testConfig() {
        $this->assertNotEmpty($this->_config, '配置文件无法读取');
    }

    public function testData() {
        $this->assertNotEmpty($this->_data, '数据文件无法读取');
    }

}