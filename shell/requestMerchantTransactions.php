#!/usr/bin/php
<?php
/**
 * Processes shell requests for merchant related transactions.
 *
 * @package Test\Shell
 * @author  Ruslan Baydan <baydanr@gmail.com>
 */

include 'shellApp.php';

if (empty($argv[1])) {
	echo 'Please specify the merchant ID parameter.' . "\n";
	exit(0);
}

$merchantId = (int) $argv[1];

if ($merchant = (new \App\Models\Merchant())->findPk($merchantId)) {
	require APPLICATION_PATH . '/scripts/report.php';
} else {
	echo 'The merchant ID ' . $merchantId . ' is not exists.' . "\n";
}