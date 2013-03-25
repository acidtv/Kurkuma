<?

abstract class Controller_Ajax extends Kohana_Controller_Rest {

	public function before()
	{
		if ( ! Auth::instance()->logged_in('login'))
			throw new HTTP_Exception_403('Access denied, login required');
	}
}
