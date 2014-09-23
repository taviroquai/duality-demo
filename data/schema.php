<?php

return array(
	'create' => array(
		'users' => array(
			'id'	=> 'auto',
			'email' => 'varchar(80)',
			'password'	=> 'varchar(180)'
		)
	),
	'update' => array(),
	'seed'	=> array(
		array('table' => 'users', 'drop' => true, 'values' => array(
			'email' => 'admin@domain.com',
			'password' => 'admin::hash'
			)
		),
		array('table' => 'users', 'values' => array(
			'email' => 'other@domain.com',
			'password' => 'other::hash'
			)
		)
	)
);