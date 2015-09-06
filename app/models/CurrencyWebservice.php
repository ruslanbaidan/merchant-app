<?php

/**
 * Dummy web service returning random exchange rates.
 *
 * @package Test\App\Models
 * @author  Ruslan Baydan <baydanr@gmail.com>
 */
class CurrencyWebservice
{
	const CURRENCY_CODE_GBP = 'GBP';
	const CURRENCY_CODE_USD = 'USD';
	const CURRENCY_CODE_EUR = 'EUR';

	protected $_supportedCurrencyCodes = [
		self::CURRENCY_CODE_GBP,
		self::CURRENCY_CODE_USD,
		self::CURRENCY_CODE_EUR,
	];

	/**
	 * Returns random value here for basic currencies like GBP USD EUR (simulates real API).
	 *
	 * @param string $currencyFrom Currency exchange from.
	 * @param string $currencyTo   Currency exchange to.
	 *
	 * @return float|false
	 */
	public function getExchangeRate($currencyFrom, $currencyTo)
	{
		if (!in_array($currencyFrom, $this->_supportedCurrencyCodes)
			|| !in_array($currencyTo, $this->_supportedCurrencyCodes)) {
			return false;
		}

		if ($currencyFrom == $currencyTo) {
			return '1.00';
		}

		if ($currencyFrom == self::CURRENCY_CODE_EUR && $currencyTo == self::CURRENCY_CODE_USD) {
			return rand(109, 120) / 100;
		} else if ($currencyFrom == self::CURRENCY_CODE_USD && $currencyTo == self::CURRENCY_CODE_EUR) {
			return rand(85, 95) / 100;
		} else if ($currencyFrom == self::CURRENCY_CODE_GBP && $currencyTo == self::CURRENCY_CODE_USD) {
			return rand(155, 165) / 100;
		} else if ($currencyFrom == self::CURRENCY_CODE_USD && $currencyTo == self::CURRENCY_CODE_GBP) {
			return rand(67, 74) / 100;
		} else if ($currencyFrom == self::CURRENCY_CODE_GBP && $currencyTo == self::CURRENCY_CODE_EUR) {
			return rand(145, 152) / 100;
		} else if ($currencyFrom == self::CURRENCY_CODE_EUR && $currencyTo == self::CURRENCY_CODE_GBP) {
			return rand(77, 82) / 100;
		}
	}

}
