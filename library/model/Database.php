<?php
/**
 * Database connection class.
 *
 * @package MerchantApp\Library\Model
 * @author  Ruslan Baydan <baydanr@gmail.com>
 */

namespace Library\Model;

use \Library\MerchantApplication;

class Database
{
	const DEFAULT_ADAPTER = 'sqlite3';

	/**
	 * DB connection.
	 *
	 * @var mixed
	 */
	private static $_connection;

	/**
	 * Returns the DB connection.
	 *
	 * @return mixed
	 *
	 * @throws \Exception
	 */
	public static function getConnection()
	{
		if (self::$_connection === null) {

			$config = MerchantApplication::app()->getConfig();
			$adapter = !empty($config->db->adapter)
				? $config->db->adapter
				: self::DEFAULT_ADAPTER;

			if (!extension_loaded($adapter)) {
				throw new \Exception('The ' . $adapter . ' extension is not loaded.');
			}

			$dbClassName = '\\Library\\Model\\' . ucfirst($adapter);
			if (!class_exists($dbClassName)) {
				throw new \Exception('The DB connection class "' . $dbClassName . '" is not implemented.');
			}

			self::$_connection = (new $dbClassName($config->db))->getConnection();
		}

		return self::$_connection;
	}

}