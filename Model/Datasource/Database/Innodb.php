<?php
App::uses('Mysql', 'Model/Datasource/Database');

class Innodb extends Mysql {

	protected $_noCache = false;

	public function setNoCache($isNoCache = true) {
		return $this->_noCache = $isNoCache;
	}

	function execute($sql, $options = array(), $params = array()) {
		if ($this->_noCache && stripos($sql, 'SELECT') === 0) {
			$sql = substr_replace($sql, ' SQL_NO_CACHE', 6, 0);
		}
		return parent::execute($sql, $options, $params);
	}

	public function describe($model) {
		$fields = parent::describe($model);

		if (!empty($this->config['dammy'])) {
			foreach ($fields as $name => $value) {
				if ($name === $this->config['dammy']) {
					unset($fields[$name]);
				}
			}
		}
		$this->_cacheDescription($this->fullTableName($model, false), $fields);
		return $fields;
	}

	public function index($model) {
		$index = parent::index($model);

		if (!empty($this->config['dammy'])) {
			foreach ($index as $key => $value) {
				if ($value['column'] === $this->config['dammy']) {
					unset($index[$key]);
				}
			}
		}
		return $index;
	}
}