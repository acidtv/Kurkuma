<?

class Model_Feed extends ORM {

	protected $_belongs_to = array(
	);

	protected $_has_many = array(
		'articles' => array(),
		'users' => array('model' => 'User', 'through' => 'users_feeds'),
	);

	public function get_with_unread_count($user)
	{
		// my sql-fu failed me, 2 queries :(
		$feeds = $user->feeds->find_all()->as_array();

		$sql = "select uf.feed_id, 0 as unread from users_feeds uf";
		//$sql = "select uf.feed_id, count(distinct a.id) unread from users_feeds uf";
		$sql .= " inner join feeds f on uf.feed_id = f.id";
		//$sql .= " left outer join articles a on f.id = a.feed_id";
		//$sql .= " left outer join users_articles_read uar on a.id = uar.article_id and uar.user_id = uf.user_id";
		$sql .= " where uf.user_id = :user";
		//$sql .= " and uar.user_id is null";
		$sql .= " group by f.id";
		$unreads = DB::query(Database::SELECT, $sql)
			->param(':user', $user->pk())
			->execute()
			->as_array('feed_id', 'unread');

		foreach ($feeds as &$feed)
		{
			$feed = $feed->as_array();
			$feed['_unread'] = Arr::get($unreads, $feed['id']);
		}

		return $feeds;
	}

}
