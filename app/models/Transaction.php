<?php

/**
 * Source of transactions, can read data.csv directly for simplicity sake,
 * should behave like a database (read only)
 *
 */

namespace App\Models;

use Library\Model\BaseDbModel;

class Transaction extends BaseDbModel
{
	/**
	 * The table name.
	 *
	 * @var string
	 */
	protected static $tableName = 'transactions';

	/**
	 * The model fields list.
	 *
	 * @var array
	 */
	protected static $tableFields = [
		'id'          => 'id',
		'merchant_id' => 'merchantId',
		'date'        => 'date',
		'amount'      => 'amount',
		'currency'    => 'currency',
	];

	/**
	 * The model relations parameters.
	 *
	 * @var array
	 */
	protected $parentRelationMap = [
		'transactions' => [
			'relationTable' => 'merchants',
			'relationModel' => '\App\Models\Merchant',
			'relationKey'   => 'merchant_id',
			'foreignKey'    => 'id',
		],
	];

	/**
	 * The entry ID.
	 *
	 * @var integer
	 */
	protected $id;

	/**
	 * The related merchant ID.
	 *
	 * @var integer
	 */
	protected $merchantId;

	/**
	 * Transaction date.
	 *
	 * @var string
	 */
	protected $date;

	/**
	 * Transaction amount.
	 *
	 * @var string|double
	 */
	protected $amount;

	/**
	 * Currency
	 *
	 * @var string
	 */
	protected $currency;

	/**
	 * Related merchant object.
	 *
	 * @var Merchant
	 */
	private $_merchant;

	/**
	 * Returns the ID.
	 *
	 * @return integer
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Sets the ID.
	 *
	 * @param integer $id ID.
	 *
	 * @return Transaction
	 */
	public function setId($id)
	{
		$this->id = (int) $id;

		return $this;
	}

	/**
	 * Returns the related merchant ID.
	 *
	 * @return integer
	 */
	public function getMerchantId()
	{
		return $this->merchantId;
	}

	/**
	 * Sets the merchant ID.
	 *
	 * @param integer $merchantId Merchant ID.
	 *
	 * @return Transaction
	 */
	public function setMerchantId($merchantId)
	{
		$this->merchantId = $merchantId;

		return $this;
	}

	/**
	 * Returns the date field value.
	 *
	 * @return string
	 */
	public function getDate()
	{
		return $this->date;
	}

	/**
	 * Sets the date field value.
	 *
	 * @param string $date Date.
	 *
	 * @return Transaction
	 */
	public function setDate($date)
	{
		$this->date = $date;

		return $this;
	}

	/**
	 * Returns amount.
	 *
	 * @return string
	 */
	public function getAmount()
	{
		return bcadd($this->amount, '0.00', 2);
	}

	/**
	 * Sets amount.
	 *
	 * @param string|double $amount Amount.
	 *
	 * @return Transaction
	 */
	public function setAmount($amount)
	{
		$this->amount = $amount;

		return $this;
	}

	/**
	 * Returns currency.
	 *
	 * @return string
	 */
	public function getCurrency()
	{
		return $this->currency;
	}

	/**
	 * Sets currency.
	 *
	 * @param string $currency Currency.
	 *
	 * @return Transaction
	 */
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
	 * @return Transaction
	 */
	public function setMerchant(Merchant $merchant)
	{
		$this->_merchant = $merchant;
		$this->merchantId = $merchant->getId();
		$merchant->addRelation('transactions', $this);

		return $this;
	}

}