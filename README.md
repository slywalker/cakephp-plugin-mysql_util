MySQL Util Plugin for CakePHP2
====================================

Using Replication
-----------------

If action use writing model method, use master.

Using UUID in Innodb
--------------------

CakePHP ignore dammy primary. [using dammy primary for mysql writhing performance](http://bret.appspot.com/entry/how-friendfeed-uses-mysql)

How to use
--------------

database.php

	<?php
	class DATABASE_CONFIG {

	/**
	 * Slave DB Config as defult
	 *
	 * driver => The name of a supported driver; valid options are as follows:
	 *		MysqlUtil.Database/Innodb 		- MySQL Innodb,
	 *
	 * master =>
	 * the name that master's config
	 *
	 * dammy =>
	 * the dammy primary field name when it uses uuid primary in innodb
	 *
	 * @var array
	 */
		public $default = array(
			'datasource' => 'MysqlUtil.Database/Innodb',
			'persistent' => false,
			'host' => 'localhost',
			'login' => 'user',
			'password' => 'password',
			'database' => 'database_name',
			'prefix' => '',
			'encoding' => 'utf8',
			'master' => 'master',
			'dammy' => 'added_id',
		);

	/**
	 * Master DB Config
	 *
	 * master =>
	 * the name that master's config
	 *
	 * @var array
	 */
		public $master = array(
			'datasource' => 'MysqlUtil.Database/Innodb',
			'persistent' => false,
			'host' => 'localhost',
			'login' => 'user',
			'password' => 'password',
			'database' => 'database_name',
			'prefix' => '',
			'encoding' => 'utf8',
			'dammy' => 'added_id',
		);
	}

AppModel.php

	<?php
	App::uses('ReplicationBaseModel', 'MysqlUtil.Model');

	class AppModel extends ReplicationBaseModel {

	}

Use SQL_NO_CACHE
(in SomeModel)

	$this->getDataSource()->setNoCache(true);

Revert

	$this->getDataSource()->setNoCache(false);