<?

class Controller_Ajax_Favourites extends Controller_Ajax {

	/**
	 * Toggle fave
	 */
	public function action_create()
	{
		$user = Auth::instance()->get_user();

		if ( ! $this->request->post('article'))
		{
			throw new HTTP_Exception_400('Missing article param in post body');
		}

		$faves = ORM::factory('Favourites');
		$faves->user_id = $user->id;
		$faves->article_id = $this->request->post('article');

		try
		{
			$faves->save();
		}
		catch (Database_Exception $e)
		{
			$this->remove($user->id, $this->request->post('article'));
		}

		$return = array(
			'result' => 'ok',
		);

		$this->response->body(json_encode($return));
	}

	/**
	 * Remove a fave
	 */
	private function remove($user, $article)
	{
		ORM::factory('Favourites')
			->where('article_id', '=', $article)
			->and_where('user_id', '=', $user)
			->find()
			->delete();
	}
}

