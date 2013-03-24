<?

class Controller_Signup extends Controller_Template {

	public function action_index()
	{
		$view = View::factory('signup');
		$this->signup($view);
		$this->template->content = $view;
	}

	public function signup($view)
	{
		$view->errors = null;

		if ( ! $this->request->post())
			return;

		try
		{
			$user = ORM::factory('User')->create_user($_POST, array(
				'username',
				'password',
				'email',
			));
		}
		catch (ORM_Validation_Exception $e)
		{
			$errors = $e->errors('validation');

			// what the hell?
			$errors = array_merge($errors, (isset($errors['_external']) ? $errors['_external'] : array()));
			unset($errors['_external']);

			$view->errors = $errors;
			return;
		}

		$role = ORM::factory('Role')->where('name', '=', 'login')->find();
		$user->add('roles', $role);
		Auth::instance()->login($user, $pass);


		$this->redirect('/reader');
	}
}
