<?

class Controller_Ajax_Feeds extends Controller_Ajax {

	/**
	 * Get all feeds
	 */
	public function action_index()
	{
		$user = Auth::instance()->get_user();
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
		$user = Auth::instance()->get_user();

		// add feed and update articles
		$feed = $feeds->add_feed(
			$this->request->post('url'),
			$user
		);

		$return = array(
			'result' => 'ok',
			'data' => $feed->as_array(),
		);

		$this->response->headers('Content-Type', 'application/json');
		$this->response->body(json_encode($return));
	}

	/**
	 * Remove a user/feed connection
	 */
	public function action_delete()
	{
		$user = Auth::instance()->get_user();
		$feed = ORM::factory('Feed', $this->request->param('id'));

		$user->remove('feeds', $feed);

		$return = array(
			'result' => 'ok',
		);

		$this->response->headers('Content-Type', 'application/json');
		$this->response->body(json_encode($return));
	}
}
