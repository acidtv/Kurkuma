<?

class Task_Feeds_Update extends Minion_Task {
	
	public function _execute(array $params)
	{
		$feeds = new Feeds();
		$feeds->register_callback(function($msg) { Minion_CLI::write($msg); });
		$feeds->update_all();
	}
}
