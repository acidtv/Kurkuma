<?

class Feeds {

	private $callback = null;

	public function register_callback($callback)
	{
		if ( ! is_callable($callback))
			throw new Exception('Callback is not callable');

		$this->callback = $callback;
	}

	private function write($message)
	{
		if ( ! is_callable($this->callback))
			return $this;

		$f = $this->callback;
		$f($message);

		return $this;
	}
	
	public function update_all()
	{
		// loop feeds and update
		$feed = ORM::factory('Feed');
		$feeds = $feed->find_all();
		$client = $this->get_client();

		foreach ($feeds as $feed)
		{
			$this->write('Updating ' . $feed->name);
			$client->set_feed_url($feed->url);
			$client->init();
			$this->update_articles($feed, $client);
		}
	}

	private function update_articles($feed, $client)
	{
		//echo $client->get_title() . "\n";
		$articles = $client->get_items();

		if ( ! $articles)
			return;

		foreach ($articles as &$article)
		{
			$object = ORM::factory('Article');
			$values = array(
				'feed_id' => 	$feed->pk(),
				'title' => 		$article->get_title(),
				'url' => 		$article->get_link(),
				'content' => 	$article->get_content(),
				'guid' => 		$article->get_id(),
				'pub_date' => 	$article->get_date('Y-m-d H:i:s'),
				'author' => 	($article->get_author() ? $article->get_author()->get_name() : ''),
			);
			$object->values($values);

			try
			{
				$object->save();
			}
			catch (Database_Exception $e)
			{
				if ($e->getCode() != 1062)
					throw $e;

				// this is a duplicate article, ignore and continue
				continue;
			}

			$article = $object;
		}

		return $articles;
	}

	public function add_feed($url, $user)
	{
		$client = $this->get_client($url);

		$feed = ORM::factory('Feed');
		$feed->user = $user;
		$feed->url = $url;
		$feed->name = $client->get_title();
		$feed->save();

		$this->update_articles($feed, $client);

		return $feed;
	}

	/**
	 * Return a new feed client
	 */
	private function get_client($url = null)
	{
		$client = new SimplePie();
		$client->enable_cache(false);

		if ($url == null)
			return $client;

		$client->set_feed_url($url);

		if ( ! $client->init())
			throw new Exception('Could not parse feed: ' . $url);

		return $client;
	}
}
