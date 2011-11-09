<?php
App::uses('Model', 'Model');

class BaseModel extends Model {

	public $defaultDbConfig = false;

	public $masterDbConfig = false;

	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);

		$dbConfig = ConnectionManager::getDataSource($this->useDbConfig);
		if (!empty($dbConfig->config['master'])) {
			$this->defaultDbConfig = $this->useDbConfig;
			$this->masterDbConfig = $dbConfig->config['master'];
		}
	}

	public function useMasterDb() {
		if ($this->masterDbConfig && $this->masterDbConfig !== $this->useDbConfig) {
			if (Configure::read('debug')) {
				$this->query('-- SET ' . strtoupper($this->masterDbConfig));
			}
			$this->setDataSource($this->masterDbConfig);
		}
	}

	public function useDafaultDb() {
		if ($this->defaultDbConfig && $this->defaultDbConfig !== $this->useDbConfig) {
			if (Configure::read('debug')) {
				$this->query('-- SET ' . strtoupper($this->defaultDbConfig));
			}
			$this->setDataSource($this->defaultDbConfig);
		}
	}

	public function saveField($name, $value, $validate = false) {
		$this->useMasterDb();
		return parent::saveField($name, $value, $validate);
	}

	public function save($data = null, $validate = true, $fieldList = array()) {
		$this->useMasterDb();
		return parent::save($data, $validate, $fieldList);
	}

	public function saveAll($data = null, $options = array()) {
		$this->useMasterDb();
		return parent::saveAll($data, $options);
	}

	public function saveMany($data = null, $options = array()) {
		$this->useMasterDb();
		return parent::saveMany($data, $options);
	}

	public function saveAssociated($data = null, $options = array()) {
		$this->useMasterDb();
		return parent::saveAssociated($data, $options);
	}

	public function updateCounterCache($keys = array(), $created = false) {
		$this->useMasterDb();
		return parent::updateCounterCache($keys, $created);
	}

	public function updateAll($fields, $conditions = true) {
		$this->useMasterDb();
		return parent::updateAll($fields, $conditions);
	}

	public function delete($id = null, $cascade = true) {
		$this->useMasterDb();
		return parent::delete($id, $cascade);
	}

	public function deleteAll($conditions, $cascade = true, $callbacks = false) {
		$this->useMasterDb();
		return parent::deleteAll($conditions, $cascade, $callbacks);
	}
}
