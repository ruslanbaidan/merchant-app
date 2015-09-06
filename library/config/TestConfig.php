<?php
/**
 * The test application basic class.
 *
 * @package Test\Library
 * @author  Ruslan Baydan <baydanr@gmail.com>
 */

namespace Library\Config;

class TestConfig
{
	/**
	 * Configuration options.
	 *
	 * @var array
	 */
	private $_options = [];

	public function __construct(array $config)
	{
		$this->_options = [];

		foreach ($config as $key => $value) {
			if (is_array($value)) {
				$this->_options[$key] = new self($value);
			} else {
				$this->_options[$key] = $value;
			}
		}
	}

	/**
	 * Provides access to the config variables.
	 *
	 * @param string $name Option name
	 *
	 * @return null|mixed
	 */
	public function __get($name)
	{
		if (isset($this->_options[$name])) {

			return $this->_options[$name];
		}

		return;
	}

}