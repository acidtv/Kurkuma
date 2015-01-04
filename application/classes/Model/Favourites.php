
<?

class Model_Favourites extends ORM {

	protected $_table_name = 'favourites';

	protected $_belongs_to = array(
		'article' => array(),
		'user' => array(),
	);

}
