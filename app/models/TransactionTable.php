<?php

/**
 * Source of transactions, can read data.csv directly for simplicity sake,
 * should behave like a database (read only)
 *
 */

namespace App\Models;

use Library\Model\BaseDbModel;

class TransactionTable extends BaseDbModel
{
	public static $currencySymbolMap = [
		'€' => 'EUR',
		'£' => 'GBP',
		'$' => 'USD',
	];

	protected static $tableName = 'transactions';

	protected static $tableFields = [
		'id'          => 'id',
		'merchant_id' => 'merchantId',
		'date'        => 'date',
		'amount'      => 'amount',
		'currency'    => 'currency',
	];

	protected $parentRelationMap = [
		'transactions' => [
			'relationTable' => 'merchants',
			'relationModel' => '\App\Models\Merchant',
			'relationKey'   => 'merchant_id',
			'foreignKey'    => 'id',
		],
	];

	protected $id;

	protected $merchantId;

	protected $date;

	protected $amount;

	protected $currency;

	/**
	 * Related merchant object.
	 *
	 * @var Merchant
	 */
	private $_merchant;

	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;

		return $this;
	}

	public function getMerchantId()
	{
		return $this->merchantId;
	}

	public function setMerchantId($merchantId)
	{
		$this->merchantId = $merchantId;

		return $this;
	}

	public function getDate()
	{
		return $this->date;
	}

	public function setDate($date)
	{
		$this->date = $date;

		return $this;
	}

	public function getAmount()
	{
		return bcadd($this->amount, '0.00', 2);
	}

	public function setAmount($amount)
	{
		$this->amount = $amount;

		return $this;
	}

	public function getCurrency()
	{
		return $this->currency;
	}

	public function setCurrency($currency)
	{
		$this->currency = $currency;

		return $this;
	}

	/**
	 * Sets a related merchant.
	 *
	 * @param Merchant $merchant Merchant object.
	 *
	 * @return TransactionTable
	 */
	public function setMerchant(Merchant $merchant)
	{
		$this->_merchant = $merchant;
		$this->merchantId = $merchant->getId();

		return $this;
	}

}