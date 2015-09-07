#!/usr/bin/php
<?php
/**
 * This file is used to setup the SQLite database.
 *
 * @package Test\Shell
 * @author  Ruslan Baydan <baydanr@gmail.com>
 */

include 'shellApp.php';

use \App\Models\TransactionTable;
use \Library\Model\Database;
use \Library\TestApplication;
use \App\Models\Merchant;
use \App\Models\CurrencyConverter;

$config = TestApplication::app()->getConfig();
$db = Database::getConnection();

/*
 * Create the database tables.
 */
$schemaPath = APPLICATION_PATH . '/../data/' . $config->db->schema;
if (!file_exists($schemaPath)) {
	throw new Exception('The schema file "' . $config->db->schema . '" does not exist.');
}
$db->exec(file_get_contents($schemaPath));

/*
 * Parse data from CSV and insert into the database.
 */
if (!file_exists($config->testData->filepath)) {
	throw new Exception('Test data file "' . $config->testData->filepath . '" does not exist.');
}

$testCsvData = array_slice(array_map('str_getcsv', file($config->testData->filepath)), 1);

$currencyConverter = new CurrencyConverter();

foreach ($testCsvData as $testCsvDataRow) {
	if (empty($testCsvDataRow[0])) {
		continue;
	}

	$testDataRow = explode(';', $testCsvDataRow[0]);
	if (empty($testDataRow[0]) || empty($testDataRow[1]) || empty($testDataRow[2])) {
		continue;
	}

	preg_match('/(\p{Sc})([\d\.,]+)/u', str_replace('"', '', $testDataRow[2]), $matches);

	$merchant = new Merchant();
	if (!$merchant->findPk($testDataRow[0])) {
		$merchant
			->setId($testDataRow[0])
			->setName('Test Name ' . $testDataRow[0])
			->save();
	}

	$currency = CurrencyConverter::DEFAULT_SYSTEM_CURRENCY;
	if (!isset(CurrencyConverter::$currencySymbolMap[$matches[1]])
		|| !($amount = $currencyConverter->convert(CurrencyConverter::$currencySymbolMap[$matches[1]], $currency, $matches[2]))
	) {
		// TODO: Notify about unsupported / unconverted currency.
		$currency = $matches[1];
		$amount = $matches[2];
	}

	$merchant->addTransaction(
		(new TransactionTable())
			->setDate(str_replace('"', '', $testDataRow[1]))
			->setAmount($amount)
			->setCurrency($currency)
	);

	$merchant->save();
}
