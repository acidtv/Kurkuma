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

	public function filters()
	{
		//$filters = parent::filters();

		//if ( ! is_array($filters))
		//{
			//$filters = array();
		//}

		$filters = array(
			'content' => array(array(array($this, 'sanitize_content')))
		);

		return $filters;
	}

	public function get_by_user(Model_User $user, Model_Feed $feed = null, $limit = 100)
	{
		$sql = "select a.*, f.name as feed_name, f.id as feed_id,uar.article_id as _read from articles a";
		$sql .= " inner join feeds f on a.feed_id = f.id";
		$sql .= " inner join users_feeds uf on a.feed_id = uf.feed_id and uf.user_id = :user";
		$sql .= " left outer join users_articles_read uar on uar.article_id = a.id and uar.user_id = :user";


		if ($feed)
		{
			$feed = $feed->pk();
			$sql .= " where a.feed_id = :feed";
		}

		$sql .= " order by pub_date desc";

		if ($limit)
		{
			$sql .= " limit :limit";
		}

		$articles = DB::query(Database::SELECT, $sql)
			->param(':user', $user->pk())
			->param(':feed', $feed)
			->param(':limit', $limit)
			->execute()
			->as_array();

		//$articles = ORM::factory('Article')
			//->select(array('uar.article_id', '_read'))
			//->join(array('users_feeds', 'uf'), 'inner')
				//->on('article.feed_id', '=', 'uf.feed_id')
				//->and_where('uf.user_id', '=', $user->pk())
			//->join(array('users_articles_read', 'uar'), 'left outer')
				//->on('uar.article_id', '=', 'article.id')
				//->on('uar.user_id', '=', DB::expr($user->pk()))
			//->with('feed');

		//if ($feed)
		//{
			//$articles->where('article.feed_id', '=', $feed->pk());
		//}

		//$articles = $articles
			//->order_by('pub_date', 'desc')
			//->limit(100)
			//->find_all()
			//->as_array();

		//$articles = array_map(function ($item) {
			//return $item->as_array();
		//}, $articles);

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

	public function sanitize_content($content)
	{
		$dom = new DOMDocument();

		try
		{
			$dom->loadHTML($content);
		}
		catch (Exception $e)
		{
			// Ok, I tried to do this the nice way...
			return 'could not parse document';
		}

		$stack = array($dom);
		$i = 0;

		while ($parent = array_shift($stack))
		{
			foreach ($parent->childNodes as $node)
			{
				if ($node instanceof DOMElement)
				{
					$src = trim($node->getAttribute('src'));

					if ($src)
					{
						$node->setAttribute('src', '');
						$node->setAttribute('data-src', $src);
					}
				}

				if ($node->hasChildNodes())
				{
					// recursion is for noobs ;)
					$stack[] = $node;
				}
			}
		}

		return $dom->saveHTML();
	}
}
