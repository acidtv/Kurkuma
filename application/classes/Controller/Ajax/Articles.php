<?

class Controller_Ajax_Articles extends Kohana_Controller_Rest {

	/**
	 * Get latest articles
	 */
	public function action_index()
	{
		$user = ORM::factory('User', 1);
		$articles = ORM::factory('Article')->get_by_user($user);

		$return = array(
			'result' => 'ok',
			'data' => $articles,
		);

		$this->response->body(json_encode($return));
	}

}
