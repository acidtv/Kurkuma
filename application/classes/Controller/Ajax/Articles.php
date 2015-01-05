<?

class Controller_Ajax_Articles extends Controller_Ajax {

	/**
	 * Get latest articles
	 */
	public function action_index()
	{
		$user = Auth::instance()->get_user();
		$feed = null;
		$faves = (bool)$this->request->query('faves');

		if ($this->request->query('feed'))
		{
			$feed = ORM::factory('Feed', $this->request->query('feed'));
		}

		$articles = ORM::factory('Article')->get_by_user($user, $feed, $faves);

		$return = array(
			'result' => 'ok',
			'data' => $articles,
		);

		$this->response->body(json_encode($return));
	}

}
