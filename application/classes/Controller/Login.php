<?

class Controller_Login extends Controller_Template {

	public function action_index()
	{
		if ($this->request->post())
		{
			$this->process_login();
		}

		$view = View::factory('login');
		$this->template->content = $view;
	}

	public function process_login()
	{
		$user = $this->request->post('user');
		$pass = $this->request->post('pass');

		if ( ! Auth::instance()->login($user, $pass))
		{
			Notification::instance()->add('Login failed');
			return;
		}

		$this->redir('/reader');
	}
}
