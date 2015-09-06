<?php
/**
 * CLI main application file.
 *
 * @package Test\Shell
 * @author  Ruslan Baydan <baydanr@gmail.com>
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set application path.
define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../app'));

// Composer autoload.
require realpath(APPLICATION_PATH . '/../vendor/autoload.php');

// Define application environment.
define('APPLICATION_ENV', 'test');

// Run the application.
\Library\TestApplication::run(APPLICATION_ENV, 'app.ini');
