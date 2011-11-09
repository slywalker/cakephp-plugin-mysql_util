<?php
class InnodbTest extends CakeTestCase {

	public $autoFixtures = false;

	public $fixtures = array('core.article');

	public $Dbo = null;

	public function setUp() {
		$this->Dbo = ConnectionManager::getDataSource('test');
		if (!($this->Dbo instanceof Innodb)) {
			$this->markTestSkipped('The MySQL extension is not available.');
		}
		$this->_debug = Configure::read('debug');
		Configure::write('debug', 1);
		$this->model = ClassRegistry::init('MysqlTestModel');
	}

	public function tearDown() {
		unset($this->model);
		ClassRegistry::flush();
		Configure::write('debug', $this->_debug);
	}

	public function testSomething() {}
}