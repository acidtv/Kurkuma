<?

class Controller_Ajax_Read extends Kohana_Controller_Rest {

	public function action_create()
	{
		$article_id = $this->request->post('article');
		$feed_id = $this->request->post('feed');

		$user = ORM::factory('User', 1);

		if ( ! ($article_id || $feed_id !== null))
			throw new HTTP_Exception_400('Either an article or feed param is required to mark as read');

		if ($article_id)
		{
			$articles = $article_id;
		}
		else
		{
			$feed = null;
			if ($feed_id > 0)
			{
				$feed = ORM::factory('Feed', $feed_id);
			}

			$articles = ORM::factory('Article')->get_unread_by_user($user, $feed);
		}

		try
		{
			$user->add('articles', $articles);
		}
		catch (Database_Exception $e)
		{
			// ignore duplicate key errors
			if ($e->getCode() != 1062)
				throw $e;
		}

		$return = array('result' => 'ok');
		$this->response->body(json_encode($return));
	}
}
