<?php
/**
 * Database connection class.
 *
 * @package Test\Library\Model
 * @author  Ruslan Baydan <baydanr@gmail.com>
 */

namespace Library\Model;

use \Library\TestApplication;

class Database
{
	/**
	 * DB connection.
	 *
	 * @var \SQLite3
	 */
	private static $_connection;

	/**
	 * Returns the DB connection.
	 *
	 * @return \SQLite3
	 */
	public static function getConnection()
	{
		if (self::$_connection === null) {
			$config = TestApplication::app()->getConfig();
			$dbConnectionString = APPLICATION_PATH . '/../' . $config->db->path . '/' . $config->db->name;

			self::$_connection = new \SQLite3($dbConnectionString);
		}

		return self::$_connection;
	}

}