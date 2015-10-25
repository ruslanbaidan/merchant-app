<?php
/**
 * Tests for the currency converter service.
 */

use \App\Models\CurrencyConverter;

class CurrencyConverterTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Currency converter object.
	 *
	 * @var CurrencyConverter
	 */
	protected $currencyConverter;

	/**
	 * Performs the setup actions.
	 */
	protected function setUp()
	{
		$this->currencyConverter = new CurrencyConverter();
	}

	/**
	 * Tests the valid conversion.
	 */
	public function testConvertUsdToEur()
	{
		$amount = '10.55';
		$convertedAmount = $this->currencyConverter->convert('USD', 'EUR', $amount);

		$this->assertGreaterThan($convertedAmount, $amount, 'EUR rate more then USD.');
	}

	/**
	 * Tests the valid conversion.
	 */
	public function testConvertUsdToUsd()
	{
		$amount = '10.55';
		$convertedAmount = $this->currencyConverter->convert('USD', 'USD', $amount);

		$this->assertEquals($amount, $convertedAmount);
	}

	/**
	 * Tests the convert with unsupported currencies.
	 */
	public function testConvertUnsupportedCurrency()
	{
		$amount = '10.55';
		$convertedAmount = $this->currencyConverter->convert('SEC', 'USD', $amount);

		$this->assertFalse($convertedAmount);

		$convertedAmount = $this->currencyConverter->convert('SEC', 'NOK', $amount);

		$this->assertFalse($convertedAmount);

		$convertedAmount = $this->currencyConverter->convert('USD', 'NOK', $amount);

		$this->assertFalse($convertedAmount);
	}

	/**
	 * Tests using wrong amount values.
	 */
	public function testConvertWrongAmount()
	{
		$amount = '';
		$convertedAmount = $this->currencyConverter->convert('USD', 'GBP', $amount);

		$this->assertFalse($convertedAmount);

		$amount = 'qwe123';
		$convertedAmount = $this->currencyConverter->convert('USD', 'GBP', $amount);

		$this->assertFalse($convertedAmount);
	}

}