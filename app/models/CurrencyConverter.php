<?php

/**
 * Converts amount to specified currency. Uses CurrencyWebservice.
 *
 * @package Test\App\Models
 * @author  Ruslan Baydan <baydanr@gmail.com>
 */

namespace App\Models;

class CurrencyConverter
{
	/** Default system currency. */
	const DEFAULT_SYSTEM_CURRENCY = 'USD';

	/**
	 * Supported currencies and symbols map.
	 *
	 * @var array
	 */
	public static $currencySymbolMap = [
		'€' => 'EUR',
		'£' => 'GBP',
		'$' => 'USD',
	];

	/**
	 * Converts the currency.
	 *
	 * @param string $currencyFrom Currency convert from.
	 * @param string $currencyTo   Currency convert to.
	 * @param string $amount       Amount.
	 *
	 * @return string|false
	 */
	public function convert($currencyFrom, $currencyTo, $amount)
	{
		if (!floatval($amount)
			|| !($rate = (new \CurrencyWebservice())->getExchangeRate($currencyFrom, $currencyTo))) {

			return false;
		}

		return bcmul($amount, $rate, 2);
	}

}