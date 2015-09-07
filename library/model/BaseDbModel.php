<?php
/**
 * Database model basic class.
 *
 * @package Test\Library\Model
 * @author  Ruslan Baydan <baydanr@gmail.com>
 */

namespace Library\Model;

abstract class BaseDbModel
{
	/**
	 * Needs to be specified in parent classes.
	 *
	 * @var string
	 */
	protected static $tableName;

	/**
	 * The model fields list.
	 *
	 * @var array
	 */
	protected static $tableFields = [];

	/**
	 * The table primary key name.
	 *
	 * Should be overridden in case of difference.
	 *
	 * @var array
	 */
	protected $tablePkName = 'id';

	/**
	 * The relationships in the table.
	 *
	 * @var array
	 */
	protected $relations = [];

	/**
	 * Relations map.
	 *
	 * @var array
	 */
	protected $relationsMap = [];

	/**
	 * Stores the flag whether the new record or not.
	 *
	 * @var boolean
	 */
	private $_isNew = true;

	/**
	 * Saves the object data in the database.
	 *
	 * @return void
	 */
	public function save()
	{
		if ($this->isNew()) {
			$this->insert();
		} else {
			$this->update();
		}

		foreach ($this->relations as $relation) {
			if (is_array($relation)) {
				foreach ($relation as $particularRelation) {
					$particularRelation->save();
				}
			} else if (!empty($relation)) {
				$relation->save();
			}
		}
	}

	/**
	 * Deletes the object data from the database.
	 *
	 * @return void
	 */
	public function delete()
	{
		if ($this->isNew()) {
			return;
		}

		$sql = 'DELETE FROM ' . static::$tableName . ' WHERE ' . $this->tablePkName . ' = :key';

		$stmt = Database::getConnection()->prepare($sql);
		$stmt->bindParam('key', $this->{$this->tablePkName});

		$stmt->execute();
	}

	/**
	 * Fetches and sets the model data by primary key.
	 *
	 * Returns a particular model object if exists or false.
	 *
	 * @param mixed $key Primary key.
	 *
	 * @return boolean|mixed
	 */
	public function findPk($key)
	{
		$sql = 'SELECT ' . implode(', ', array_keys(static::$tableFields))
			. ' FROM ' . static::$tableName
			. ' WHERE ' . $this->tablePkName . ' = :key'
			. ' LIMIT 1';

		$stmt = Database::getConnection()->prepare($sql);
		$stmt->bindParam('key', $key);

		$result = $stmt->execute();

		$data = $result->fetchArray(SQLITE3_ASSOC);
		if (!empty($data)) {
			foreach (static::$tableFields as $fieldDbName => $fieldName) {
				$this->$fieldName = $data[$fieldDbName];
			}

			$this->_isNew = false;

			return $this;
		}

		return false;
	}

	/**
	 * Returns list of related objects.
	 *
	 * @param string  $relationName Relation name.
	 * @param array   $sortParams   Sort parameters.
	 * @param boolean $forceLoad    Whether we need load events from the DB if cache is empty.
	 *
	 * @return array
	 */
	public function fetchRelations($relationName, $sortParams = [], $forceLoad = false)
	{
		if (empty($this->relationsMap[$relationName])) {
			return [];
		}

		if (!empty($this->relations[$relationName]) && !$forceLoad) {
			return $this->relations[$relationName];
		}

		$this->relations[$relationName] = [];

		$relationModelName = $this->relationsMap[$relationName]['relationModel'];
		$fields = $relationModelName::getFieldsList();
		$sql = 'SELECT ' . implode(', ', array_keys($fields))
			. ' FROM ' . $this->relationsMap[$relationName]['relationTable']
			. ' WHERE ' . $this->relationsMap[$relationName]['foreignKey'] . ' = :key';

		if (!empty($sortParams['field'])) {
			$direction = !empty($sortParams['direction']) ? $sortParams['direction'] : 'ASC';
			$sql .= ' ORDER BY ' . $sortParams['field'] . ' ' . $direction;
		}

		$stmt = Database::getConnection()->prepare($sql);
		$stmt->bindParam('key', $this->{$this->relationsMap[$relationName]['relationKey']});

		$result = $stmt->execute();

		while ($data = $result->fetchArray(SQLITE3_ASSOC)) {

			$relationObject = new $relationModelName();
			foreach ($fields as $fieldDbName => $fieldName) {
				$relationObject->$fieldName = $data[$fieldDbName];
			}

			$this->relations[$relationName][] = $relationObject;

			$relationObject->setIsNew(false);
		}

		return $this->relations[$relationName];
	}

	/**
	 * Returns list of the fields from particular model.
	 *
	 * @return array
	 */
	public static function getFieldsList()
	{
		return static::$tableFields;
	}

	/**
	 * Checks whether the object is new or not.
	 *
	 * @return boolean
	 */
	public function isNew()
	{
		return $this->_isNew;
	}

	/**
	 * Sets the isNew flag.
	 *
	 * @param boolean
	 *
	 * @return void
	 */
	public function setIsNew($isNew)
	{
		$this->_isNew = $isNew;
	}

	/**
	 * Appends a relation to the current object.
	 *
	 * @param string $relationName   Relation name.
	 * @param mixed  $relationObject Relation object.
	 *
	 * @return void
	 */
	public function addRelation($relationName, $relationObject)
	{
		if (!empty($this->relationsMap[$relationName])
			&& get_class($relationObject) == $this->relationsMap[$relationName]['relationModel']) {
			$this->relations[$relationName][] = $relationObject;
		}
	}

	/**
	 * Inserts the object data in the database.
	 *
	 * @return void
	 */
	protected function insert()
	{
		$insertFields = static::$tableFields;
		if (empty($this->{$this->tablePkName})) {
			$insertFields = array_slice($insertFields, 1);
		}

		$sql = 'INSERT INTO ' . static::$tableName . ' (' . implode(', ', array_keys($insertFields)) . ')'
			. ' VALUES (:' . str_replace(' ', ', ', implode(' :', $insertFields)) . ')';

		$dbConnection = Database::getConnection();
		$stmt = $dbConnection->prepare($sql);

		foreach ($insertFields as $fieldName) {
			$stmt->bindParam($fieldName, $this->$fieldName);
		}

		$stmt->execute();

		$this->{$this->tablePkName} = $dbConnection->lastInsertRowID();

		$this->setIsNew(false);
	}

	/**
	 * Updates data in the database.
	 *
	 * @return void
	 */
	protected function update()
	{
		// TODO: It doesn't need for now.
	}

}