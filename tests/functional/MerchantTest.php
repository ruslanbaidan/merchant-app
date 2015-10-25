<?php
/**
 * Tests for the merchant model.
 */

use \Library\MerchantApplication;
use \Library\Model\Database;
use \App\Models\Merchant;
use \App\Models\Transaction;

class MerchantTest extends PHPUnit_Framework_TestCase
{
	/**
	 * The database connection.
	 *
	 * @var SQLite3
	 */
	protected static $dbConnection;

	public static function setUpBeforeClass()
	{
		// Create the test DB.
		self::$dbConnection = Database::getConnection();

		$schemaPath = APPLICATION_PATH . '/../data/' . MerchantApplication::app()->getConfig()->db->schema;
		self::$dbConnection->exec(file_get_contents($schemaPath));
	}

	public static function tearDownAfterClass()
	{
		// Remove the test DB.
		$config = MerchantApplication::app()->getConfig();
		$dbFileName = APPLICATION_PATH . '/../' . $config->db->path . '/' . $config->db->name;
		if (file_exists($dbFileName)) {
			unlink($dbFileName);
		}
	}

	public function tearDown()
	{
		$sql = 'DELETE FROM merchants';
		self::$dbConnection->query($sql);

		$sql = 'DELETE FROM transactions';
		self::$dbConnection->query($sql);
	}

	private function _createTestMerchant()
	{
		$merchant = new Merchant();
		$merchant->setId(1)->setName('Test Merchant')->save();

		return $merchant;
	}

	public function _getNewTransaction()
	{
		return (new Transaction())
			->setDate('2015-09-07')
			->setCurrency('USD')
			->setAmount('10.55');
	}

	public function testCreateMerchant()
	{
		$merchant = $this->_createTestMerchant();

		$this->assertInstanceOf('\App\Models\Merchant', $merchant);

		$currentId = $merchant->getId();
		$currentName = $merchant->getName();

		$foundMerchant = $merchant->findPk($currentId);
		$this->assertInstanceOf('\App\Models\Merchant', $foundMerchant, 'Check in the DB.');

		$this->assertEquals($currentId, $merchant->getId(), 'Check if the merchant has the same ID after search.');
		$this->assertEquals($currentName, $merchant->getName(), 'Check if the merchant has the same Name after search.');
	}

	public function testAppendTransactionsToMerchant()
	{
		$merchant = $this->_createTestMerchant();

		$transaction = $this->_getNewTransaction();

		$merchant->addTransaction($transaction);

		$this->assertCount(1, $merchant->getTransactions());

		$this->assertEquals($transaction->getMerchantId(), $merchant->getId());

		$transaction = $this->_getNewTransaction();
		$merchant->addTransaction($transaction);

		$this->assertCount(2, $merchant->getTransactions());

		$this->assertEquals($transaction->getMerchantId(), $merchant->getId());
	}

	public function testSetMerchantForTransaction()
	{
		$merchant = $this->_createTestMerchant();

		$transaction = $this->_getNewTransaction();

		$transaction->setMerchant($merchant);

		$this->assertEquals($transaction->getMerchantId(), $merchant->getId());

		$this->assertCount(1, $merchant->getTransactions());

		$this->assertEquals($transaction->getMerchantId(), $merchant->getId());
	}

	public function testCheckSavingTransactionsInTheDatabase()
	{
		$merchant = $this->_createTestMerchant();

		$transaction = $this->_getNewTransaction();

		$transaction->setMerchant($merchant);

		$transaction = $this->_getNewTransaction()
			->setCurrency('EUR')
			->setDate('2015-09-05')
			->setAmount(5.55);

		$merchant->addTransaction($transaction);

		$merchant->save();

		foreach ($merchant->getTransactions() as $transaction) {
			$foundTransaction = (new Transaction())->findPk($transaction->getId());
			$this->assertInstanceOf('\App\Models\Transaction', $foundTransaction);
			$this->assertEquals($transaction->getId(), $foundTransaction->getId());
			$this->assertEquals($transaction->getCurrency(), $foundTransaction->getCurrency());
			$this->assertEquals($transaction->getDate(), $foundTransaction->getDate());
			$this->assertEquals($transaction->getAmount(), $foundTransaction->getAmount());
		}
	}

	public function testAppendWrongListOfTransactions()
	{
		$merchant = $this->_createTestMerchant();

		$merchant->setTransactions([new stdClass(), new Merchant()]);

		$this->assertCount(0, $merchant->getTransactions());
	}

	public function testAppendWrongTransactionType()
	{
		$merchant = $this->_createTestMerchant();

		try {
			$merchant->addTransaction(new stdClass());
		}
		catch (Exception $expected) {
			return;
		}

		$this->fail('The expected exception has not been occurred.');
	}

}