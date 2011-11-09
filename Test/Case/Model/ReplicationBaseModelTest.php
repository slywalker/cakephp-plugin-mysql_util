<?php
App::uses('ReplicationBaseModel', 'MysqlUtil.Model');

class AppModel extends ReplicationBaseModel {

	public $isMasterAtBeforeValidate = false;

	public $isMasterAtBeforeSave = false;

	public $isMasterAtBeforeDelete = false;

	public function beforeValidate($options) {
		$this->isMasterAtBeforeValidate = ($this->useDbConfig === 'master');
		return true;
	}

	public function beforeSave($options) {
		$this->isMasterAtBeforeSave = ($this->useDbConfig === 'master');
		return true;
	}

	public function beforeDelete($cascade) {
		$this->isMasterAtBeforeDelete = ($this->useDbConfig === 'master');
		return true;
	}
}

class Article extends AppModel {

	public $hasMany = array('Comment');

	public $validate = array(
		'title' => 'isUnique',
	);
}

class Comment extends AppModel {

	public $belongsTo = array('Comment');
}

/**
 * ModelDeleteTest
 *
 * @package       Cake.Test.Case.Model
 */
class ReplicationBaseModelTest extends CakeTestCase {

	public $fixtures = array('core.article', 'core.comment');

	public function setUp() {
		$this->debug = Configure::read('debug');
	}

	public function tearDown() {
		Configure::write('debug', $this->debug);
		ClassRegistry::flush();
	}

	public function testUseMasterDbAndUseDafaultDb() {
		$Article = ClassRegistry::init('Article');
		$this->assertFalse(($Article->useDbConfig === 'master'));
		$Article->useMasterDb();
		$this->assertTrue(($Article->useDbConfig === 'master'));
		$Article->useDafaultDb();
		$this->assertFalse(($Article->useDbConfig === 'master'));
	}

	public function testSaveField() {
		$Article = ClassRegistry::init('Article');
		$this->assertFalse($Article->isMasterAtBeforeSave);
		$Article->saveField('title', 'Lorem ipsum dolor sit amet');
		$this->assertTrue($Article->isMasterAtBeforeSave);
	}

	public function testSave() {
		$Article = ClassRegistry::init('Article');
		$this->assertFalse($Article->isMasterAtBeforeValidate);
		$this->assertFalse($Article->isMasterAtBeforeSave);
		$Article->save(array(
			'title' => 'Lorem ipsum dolor sit amet',
			'body' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
		));
		$this->assertTrue($Article->isMasterAtBeforeValidate);
		$this->assertTrue($Article->isMasterAtBeforeSave);
	}

	public function testSaveAll() {
		$Article = ClassRegistry::init('Article');
		$this->assertFalse($Article->isMasterAtBeforeValidate);
		$this->assertFalse($Article->isMasterAtBeforeSave);
		$this->assertFalse($Article->Comment->isMasterAtBeforeValidate);
		$this->assertFalse($Article->Comment->isMasterAtBeforeSave);
		$data = array(
			'Article' => array(
				'title' => 'Lorem ipsum dolor sit amet',
				'body' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
			),
			'Comment' => array(
				array(
					'comment' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
				)
			),
		);
		$Article->saveAll($data);
		$this->assertTrue($Article->isMasterAtBeforeValidate);
		$this->assertTrue($Article->isMasterAtBeforeSave);
		$this->assertTrue($Article->Comment->isMasterAtBeforeValidate);
		$this->assertTrue($Article->Comment->isMasterAtBeforeSave);
	}

	public function testSaveMany() {
		$Article = ClassRegistry::init('Article');
		$this->assertFalse($Article->isMasterAtBeforeValidate);
		$this->assertFalse($Article->isMasterAtBeforeSave);
		$data = array(
			array(
				'title' => 'Lorem ipsum dolor sit amet',
				'body' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
			),
			array(
				'title' => 'Lorem ipsum dolor sit amet 2',
				'body' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
			)
		);
		$Article->save($data);
		$this->assertTrue($Article->isMasterAtBeforeValidate);
		$this->assertTrue($Article->isMasterAtBeforeSave);
	}

	public function testDelete() {
		$Article = ClassRegistry::init('Article');
		$this->assertFalse($Article->isMasterAtBeforeDelete);
		$Article->delete(1);
		$this->assertTrue($Article->isMasterAtBeforeDelete);
	}

	public function testDeleteAll() {
		$Article = ClassRegistry::init('Article');
		$this->assertFalse($Article->isMasterAtBeforeDelete);
		$Article->deleteAll(array('id NOT' => null), true, true);
		$this->assertTrue($Article->isMasterAtBeforeDelete);
	}
}