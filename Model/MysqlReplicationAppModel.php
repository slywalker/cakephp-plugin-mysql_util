<?php
App::uses('Model', 'Model');

class MysqlReplicationAppModel extends Model {

	public function __construct($id = false, $table = null, $ds = null) {

		parent::__construct($id, $table, $ds);
	}
}

