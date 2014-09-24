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

	/**
	 * Update a single feed
	 */
	public function update_single(Model_Feed $feed)
	{
		if ( ! $feed->loaded())
			throw new Exception('Cannot update articles for new feed');

		$client = $this->get_client($feed);

		if ( ! $client)
		{
			$this->write($feed->name . ' no new data ');
			return;
		}

		$this->write($feed->name . ' got ' . $client->status());
		$this->update_articles($feed, $client);
	}

	/**
	 * A lower leven update function that uses existing
	 * RSS client object
	 */
	private function update_articles(Model_Feed $feed, $client)
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
				'pub_date' => 	$article->get_gmdate('Y-m-d H:i:s') ?: date('Y-m-d H:i:s'),
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

	/**
	 * Add a new feed and update articles
	 */
	public function add_feed($url, $user)
	{
		$feed = ORM::factory('Feed')->where('url', '=', $url)->find();

		if ($feed->loaded())
		{
			$this->_add_feed($user, $feed);
			return $feed;
		}

		$feed = ORM::factory('Feed');
		$feed->url = $url;
		$client = $this->get_client($feed);

		if ( ! $client)
		{
			throw new Exception('RSSClient did not return any data for new feed');
		}

		$feed->name = $client->get_title();

		try
		{
			$feed->save();
		}
		catch (Database_Exception $e)
		{
			if ($e->getCode() != 1062)
				throw $e;

			// this feed already exists
		}

		$this->_add_feed($user, $feed);
		$this->update_articles($feed, $client);

		return $feed;
	}

	private function _add_feed($user, $feed)
	{
		try
		{
			$user->add('feeds', $feed);
		}
		catch (Database_Exception $e)
		{
			// user is already subscribed to this feed, ignore
			if ($e->getCode() != 1062)
				throw $e;
		}
	}

	/**
	 * Return a new RSS client.
	 * This also update the feed url if there was a permanent redirect.
	 */
	private function get_client($feed)
	{
		$client = RSSClient::factory($feed->url);

		if ($feed->loaded())
		{
			$client->modified($feed->server_modified);
			$client->etag($feed->server_etag);
		}

		try
		{
			$result = $client->init();
		}
		catch (Exception $e)
		{
			$this->write('Could not get feed contents: ' . $e->getMessage());
			return false;
		}

		// permanent redirect, update url
		if ($result['previous_status'] == 301)
		{
			$feed->url = $result['url'];
		}

		// not modified
		if ($result['status'] == 304)
			return false;
		// success
		elseif ($result['status'] == 200)
		{
			$feed->server_modified = $client->modified();
			$feed->server_etag = $client->etag();
		}
		else
		{
			throw new Exception('Got invalid status: ' . $result['status']);
		}

		$feed->save();

		return $client;
	}
}
