<?

class Model_Feed extends ORM {
	
	protected $_belongs_to = array(
		'user' => array(),
	);

	protected $_has_many = array(
		'articles' => array(),
	);

	public function get_with_unread_count($user)
	{
		$sql = "select f.*, count(distinct a.id) _unread from feeds f";
		$sql .= " left outer join articles a on f.id = a.feed_id";
		$sql .= " left outer join users_articles_read uar on a.id = uar.article_id and uar.user_id = :user";
		$sql .= " where f.user_id = :user";
		$sql .= " and uar.user_id is null";
		$sql .= " group by f.id";
		$feeds = DB::query(Database::SELECT, $sql)
			->param(':user', $user->pk())
			->execute()
			->as_array();

		return $feeds;
	}

}
