<?php
/**
 * CLI main application file.
 *
 * @package MerchantApp\Shell
 * @author  Ruslan Baydan <baydanr@gmail.com>
 */

// Set application path.
define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../app'));

// Composer autoload.
require realpath(APPLICATION_PATH . '/../vendor/autoload.php');

// Define application environment.
define('APPLICATION_ENV', getenv('APPLICATION_ENV') ? : 'live');

// Run the application.
\Library\MerchantApplication::run(APPLICATION_ENV, 'app.ini');
