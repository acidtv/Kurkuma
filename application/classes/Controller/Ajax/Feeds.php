<?

class Controller_Ajax_Feeds extends Kohana_Controller_Rest {

	/**
	 * Get all feeds
	 */
	public function action_index()
	{
		$feeds = ORM::factory('Feed')->find_all();	

		$feeds_array = array();
		foreach ($feeds as $feed)
		{
			$feeds_array[] = $feed->as_array();
		}

		$return = array(
			'result' => 'ok',
			'data' => $feeds_array,
		);

		$this->response->body(json_encode($return));
	}

	/**
	 * Add a new feed
	 */
	public function action_create()
	{
		$feeds = new Feeds();
		$user = ORM::factory('User', 1);

		// add feed and update articles
		$feed = $feeds->add_feed(
			$this->request->post('url'), 
			$user
		);

		// get articles
		$articles = $feed->articles
			->with('feed')
			->find_all()
			->as_array();

		// convert article objects to array
		$articles = array_map(function ($item) {
			return $item->as_array();
		}, $articles);

		$return = array(
			'result' => 'ok',
			'data' => array('feed' => $feed->as_array(), 'articles' => $articles),
		);

		$this->response->headers('Content-Type', 'application/json');
		$this->response->body(json_encode($return));
	}
}
