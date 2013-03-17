<?php defined('SYSPATH') OR die('No direct access allowed.');

class Model_User extends Model_Auth_User {

	protected $_has_many = array(
		'articles' => array(
			'model' => 'Article',
			'through' => 'users_articles_read'
		)
	);

}
