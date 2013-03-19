<?

class Model_User extends Model_Auth_User {
	
	protected $_has_many = array(
		'articles' => array(
			'model' => 'Article',
			'through' => 'users_articles_read'
		),
		'feeds' 	  => array('model' => 'Feed', 'through' => 'users_feeds'),

		// inherited from model auth
		'user_tokens' => array('model' => 'User_Token'),
		'roles'       => array('model' => 'Role', 'through' => 'roles_users'),
	);
}
