<?php
/**
 * Base database connection interface.
 *
 * @package MerchantApp\Library\Model\Interfaces
 * @author  Ruslan Baydan <baydanr@gmail.com>
 */

namespace Library\Model\Interfaces;

interface DBConnectionInterface
{

	public function getConnection();
}