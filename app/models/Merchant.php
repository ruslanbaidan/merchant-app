<?php

/**
 * Merchant model class.
 *
 * Manipulates with merchant related data.
 *
 * @package MerchantApp\App\Models
 * @author  Ruslan Baydan <baydanr@gmail.com>
 */

namespace App\Models;

use Library\Model\BaseDbModel;

class Merchant extends BaseDbModel
{
	/**
	 * The table name.
	 *
	 * @var string
	 */
	protected static $tableName = 'merchants';

	/**
	 * The model fields list.
	 *
	 * @var array
	 */
	protected static $tableFields = [
		'id'   => 'id',
		'name' => 'name',
	];

	/**
	 * The model relations parameters.
	 *
	 * @var array
	 */
	protected $relationsMap = [
		'transactions' => [
			'relationTable' => 'transactions',
			'relationModel' => 'App\Models\TransactionTable',
			'relationKey'   => 'id',
			'foreignKey'    => 'merchant_id',
		],
	];

	/**
	 * The entry ID.
	 *
	 * @var integer
	 */
	protected $id;

	/**
	 * Merchant name.
	 *
	 * @var string
	 */
	protected $name;

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
	 * @return Merchant
	 */
	public function setId($id)
	{
		$this->id = (int) $id;

		return $this;
	}

	/**
	 * Returns the name field value.
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Sets the name field value.
	 *
	 * @param string $name Name.
	 *
	 * @return Merchant
	 */
	public function setName($name)
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * Adds a related transaction.
	 *
	 * @param TransactionTable $transaction Transaction.
	 *
	 * @return void
	 */
	public function addTransaction(TransactionTable $transaction)
	{
		$transaction->setMerchant($this);
	}

	/**
	 * Sets the transactions list and removes previously related.
	 *
	 * @param array $transactions Transaction list.
	 *
	 * @return void
	 */
	public function setTransactions(array $transactions = [])
	{
		if (!empty($this->relations['transactions'])) {
			foreach ($this->relations['transactions'] as $transactions) {
				$transactions->delete();
			}

			$this->relations['transactions'] = [];
		}

		foreach ($transactions as $transaction) {
			if (!$transactions instanceof TransactionTable) {
				continue;
			}

			$this->addTransaction($transaction);
        }
	}

	/**
	 * Returns the related transactions list.
	 *
	 * @return array
	 */
	public function getTransactions()
	{
		return $this->fetchRelations('transactions', ['field' => 'date', 'direction' => 'DESC']);
	}

}