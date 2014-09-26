<?php

namespace Demo\Model;

use Duality\System\Structure\Property;
use Duality\System\Structure\Entity;

class User extends Entity
{
	public function __construct()
	{
		parent::__construct(new Property('id'));
		$this->setName('users');
		$this->addPropertiesFromArray(array('email'));
	}
}