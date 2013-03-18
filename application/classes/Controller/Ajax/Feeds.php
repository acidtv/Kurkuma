<?

class Controller_Ajax_Feeds extends Kohana_Controller_Rest {

	/**
	 * Get all feeds
	 */
	public function action_index()
	{
		$user = ORM::factory('User', 1);
		$feeds = ORM::factory('Feed')->get_with_unread_count($user);

		$return = array(
			'result' => 'ok',
			'data' => $feeds,
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
