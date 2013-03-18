<?

class Controller_Ajax_Articles extends Kohana_Controller_Rest {

	/**
	 * Get latest articles
	 */
	public function action_index()
	{
		$articles = ORM::factory('Article')
			->select(array('uar.article_id', '_read'))
			->join(array('users_articles_read', 'uar'), 'left outer')
				->on('uar.article_id', '=', 'article.id')
			->with('feed');

		if ($this->request->query('id'))
		{
			$articles->where('feed_id', '=', $this->request->query('id'));
		}

		$articles = $articles
			->order_by('pub_date', 'desc')
			->limit(100)
			->find_all()
			->as_array();

		$articles = array_map(function ($item) {
			return $item->as_array();
		}, $articles);

		$return = array(
			'result' => 'ok',
			'data' => $articles,
		);

		$this->response->body(json_encode($return));
	}

}
