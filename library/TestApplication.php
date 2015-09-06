<?php
/**
 * The test application basic class.
 *
 * @package Test\Library
 * @author  Ruslan Baydan <baydanr@gmail.com>
 */

namespace Library;

class TestApplication
{
	/**
	 * Application object.
	 *
	 * @var TestApplication
	 */
	private static $_application;

	/**
	 * Environment value.
	 *
	 * @var string
	 */
	private $_environment;

	/**
	 * Config object.
	 *
	 * @var TestConfig.
	 */
	private $_config;

	/**
	 * The main application constructor.
	 *
	 * @param string $environment Environment.
	 * @param string $configName  Config name.
	 *
	 * @throws Exception
	 */
	private function __construct($environment, $configName)
	{
		$this->_environment = $environment;

		$this->_loadConfig($configName);
	}

	/**
	 * Cloning the object is not allowed.
	 */
	private function __clone() { }

	/**
	 * Creates the application object.
	 *
	 * @param string $environment Environment.
	 * @param string $configName  Config name.
	 *
	 * @throws Exception
	 *
	 * @return TestApplication
	 */
	public static function run($environment, $configName)
	{
		if (self::$_application === null) {
			self::$_application = new self($environment, $configName);
		}

		return self::$_application;
	}

	/**
	 * Returns the application object.
	 *
	 * @return TestApplication
	 */
	public static function app()
	{
		return self::$_application;
	}

	/**
	 * Returns the config.
	 *
	 * @return Config\TestConfig
	 */
	public function getConfig()
	{
		return $this->_config;
	}

	/**
	 * Loads the config.
	 *
	 * @param $configName
	 *
	 * @throws Exception
	 *
	 * @return void
	 */
	private function _loadConfig($configName)
	{
		$configPath = APPLICATION_PATH . '/config/' . APPLICATION_ENV . '.' . $configName;

		if (!file_exists($configPath)) {
			throw new Exception('Please create a config file "' . $configPath . '".');
		}

		$this->_config = new Config\TestConfig(parse_ini_file($configPath));
	}

}
