<?php
/**
 * Base database model interface.
 *
 * @package MerchantApp\Library\Model\Interfaces
 * @author  Ruslan Baydan <baydanr@gmail.com>
 */

namespace Library\Model\Interfaces;

interface DbModelInterface
{

	public function delete();

	public function save();

	public function findPk($key);
}