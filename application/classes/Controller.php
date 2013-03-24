<?

class Controller extends Kohana_Controller {
		/**
		 * Controls access for the whole controller, if not set to FALSE we will only allow user roles specified
		 * Can be set to a string or an array, for example 'login' or array('login', 'admin')
		 * Note that in second(array) example, user must have both 'login' AND 'admin' roles set in database
		 */
		public $auth_required = FALSE;
		 
	 	/**
		 * Controls access for separate actions
		 * 'adminpanel' => 'admin' will only allow users with the role admin to access action_adminpanel
		 * 'moderatorpanel' => array('login', 'moderator') will only allow users with the roles login and moderator to access action_moderatorpanel
		 */
		public $secure_actions = FALSE;

		public function before()
		{
			// Run anything that need to run before this.
			parent::before();

			// Check user auth and role
			$action = $this->request->action();
									
			if (($this->auth_required !== FALSE && Auth::instance()->logged_in($this->auth_required) === FALSE)
				|| (is_array($this->secure_actions) && array_key_exists($action, $this->secure_actions) && 
				Auth::instance()->logged_in($this->secure_actions[$action]) === FALSE))
			{
				if (Auth::instance()->logged_in())
				{
					throw new HTTP_Exception_403('Access denied: not enough permissions');
				}
				else
				{
					$this->redirect('login');
				}
			}
		}
}
