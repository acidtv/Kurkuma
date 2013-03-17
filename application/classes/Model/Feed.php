<?

class Model_Feed extends ORM {
	
	protected $_belongs_to = array(
		'user' => array(),
	);

	protected $_has_many = array(
		'articles' => array(),
	);

}
