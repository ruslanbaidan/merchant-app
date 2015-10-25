<?php
/**
 * Simple report to display the merchant related transaction.
 */

echo 'Transactions for merchant ID: ' . $merchant->getId() . "\n";

foreach ($merchant->getTransactions() as $transaction) {
	echo 'Date: ' . $transaction->getDate() . ',',
		' Amount: ' . $transaction->getAmount(),
		' ' . $transaction->getCurrency() . "\n";
}