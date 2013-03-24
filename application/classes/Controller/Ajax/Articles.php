<?

class Controller_Ajax_Articles extends Kohana_Controller_Rest {

	/**
	 * Get latest articles
	 */
	public function action_index()
	{
		$user = Auth::instance()->get_user();
		$feed = null;

		if ($this->request->query('feed'))
		{
			$feed = ORM::factory('Feed', $this->request->query('feed'));
		}

		$articles = ORM::factory('Article')->get_by_user($user, $feed);

		$return = array(
			'result' => 'ok',
			'data' => $articles,
		);

		$this->response->body(json_encode($return));
	}

}
