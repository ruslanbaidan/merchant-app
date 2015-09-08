<?php

/**
 * Base database model interface.
 *
 * @package Test\Library\Interface
 * @author  Ruslan Baydan <baydanr@gmail.com>
 */

namespace Library\Model;

interface DbModelInterface
{

	public function delete();

	public function save();

	public function findPk($key);
}