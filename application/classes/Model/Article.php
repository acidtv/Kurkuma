<?

class Model_Article extends ORM {

	protected $_belongs_to = array(
		'feed' => array(),
	);

	protected $_has_many = array(
		'users' => array(
			'model' => 'User',
			'through' => 'users_articles_read'
		)
	);

	private function format_date($date)
	{
		$timestamp = strtotime($date);
		return date('j M, H:i', $timestamp);
	}

	public function as_array()
	{
		$array = parent::as_array();
		$array['_pub_date'] = $this->format_date($array['pub_date']);
		return $array;
	}
}
