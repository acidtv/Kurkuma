<?

class Controller_Ajax_Read extends Kohana_Controller_Rest {

	public function action_create()
	{
		$article = ORM::factory('Article', $this->request->post('article'));
		$user = ORM::factory('User', 1);

		try
		{
			$article->add('users', $user);
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
