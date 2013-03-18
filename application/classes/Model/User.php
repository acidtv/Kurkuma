<?

class Model_User extends Model_Auth_User {
	
	protected $_has_many = array(
		'feeds' 	  => array('model' => 'Feed', 'through' => 'users_feeds'),
		'user_tokens' => array('model' => 'User_Token'),
		'roles'       => array('model' => 'Role', 'through' => 'roles_users'),
	);
}
