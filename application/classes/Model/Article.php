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

	public function get_by_user(Model_User $user, Model_Feed $feed = null, $limit = 100)
	{
		$articles = ORM::factory('Article')
			->select(array('uar.article_id', '_read'))
			->join(array('users_feeds', 'uf'), 'inner')
				->on('article.feed_id', '=', 'uf.feed_id')
				->and_where('uf.user_id', '=', $user->pk())
			->join(array('users_articles_read', 'uar'), 'left outer')
				->on('uar.article_id', '=', 'article.id')
			->with('feed');

		if ($feed)
		{
			$articles->where('article.feed_id', '=', $feed->pk());
		}

		$articles = $articles
			->order_by('pub_date', 'desc')
			->limit(100)
			->find_all()
			->as_array();

		$articles = array_map(function ($item) {
			return $item->as_array();
		}, $articles);

		return $articles;
	}

	public function get_unread_by_user(Model_User $user, Model_Feed $feed = null)
	{
		$sql = "select a.id from articles a";
		$sql .= " left outer join users_articles_read uar on a.id = uar.article_id and uar.user_id = :user";
		$sql .= " where uar.article_id is null";

		if ($feed)
		{
			$sql .= " and a.feed_id = :feed";
		}

		$query = DB::query(Database::SELECT, $sql)
			->param(':user', $user->pk());

		if ($feed)
		{
			$query->param(':feed', $feed->pk());
		}
		$result = $query->execute()->as_array('id');

		return $result;
	}
}
