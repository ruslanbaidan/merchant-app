<?php

/**
 * Merchant model class.
 *
 * Manipulates with merchant related data.
 *
 * @package Test\App\Models
 * @author  Ruslan Baydan <baydanr@gmail.com>
 */

namespace App\Models;

use Library\Model\BaseDbModel;

class Merchant extends BaseDbModel
{
	protected static $tableName = 'merchants';

	protected static $tableFields = [
		'id'   => 'id',
		'name' => 'name',
	];

	protected $relationsMap = [
		'transactions' => [
			'relationTable' => 'transactions',
			'relationModel' => '\App\Models\TransactionTable',
			'relationKey'   => 'id',
			'foreignKey'    => 'merchant_id',
		],
	];

	protected $id;

	protected $name;

	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;

		return $this;
	}

	public function getName()
	{
		return $this->name;
	}

	public function setName($name)
	{
		$this->name = $name;

		return $this;
	}

	public function addTransaction(TransactionTable $transaction)
	{
		$transaction->setMerchant($this);
		$this->relations['transactions'][] = $transaction;
	}

	public function setTransactions(array $transactions = [])
	{
		foreach($this->transactions as $transactions) {
			$transactions->delete();
		}
		$this->relations['transactions'] = [];

		foreach ($transactions as $transaction) {
			$transaction->setMerchant($this);
			$this->relations['transactions'][] = $transaction;
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