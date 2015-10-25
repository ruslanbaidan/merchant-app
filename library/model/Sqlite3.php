<?php
/**
 * Sqlite3 database connection class.
 *
 * @package MerchantApp\Library\Model
 * @author  Ruslan Baydan <baydanr@gmail.com>
 */

namespace Library\Model;

use Library\Model\Interfaces\DBConnectionInterface;

class Sqlite3 implements DBConnectionInterface
{
	/**
	 * Sqlite3 connection.
	 *
	 * @var \SQLite3
	 */
	private $_connection;

	/**
	 * The class constructor.
	 *
	 * @param stdClass $params The database connection params.
	 */
	public function __construct($params)
	{
		$dbConnectionString = APPLICATION_PATH . '/../' . $params->path . '/' . $params->name;

		$this->_connection = new \SQLite3($dbConnectionString);
	}

	/**
	 * Returns SQLite3 connection.
	 *
	 * @return \SQLite3
	 */
	public function getConnection()
	{
		return $this->_connection;
	}

}