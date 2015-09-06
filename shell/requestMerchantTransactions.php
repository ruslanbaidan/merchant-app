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

$merchant = new \App\Models\Merchant();
if ($merchant->findPk($merchantId)) {
	require APPLICATION_PATH . '/scripts/report.php';
}