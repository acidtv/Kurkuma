<?

class Task_Feeds_Update extends Minion_Task {
	
	protected $_options = array(
		'feed' => null,
		'processes' => 5,
	);

	public function _execute(array $params)
	{
		if ($params['feed'])
		{
			$this->update_feed($params['feed']);
			return;
		}

		Minion_CLI::write(' * Updating feeds using ' . $params['processes'] . ' processes');
		$this->spawn_childs($params['processes']);
	}

	/**
	 * Update a single feed
	 */
	private function update_feed($feed)
	{
		$feed = intval($feed);

		if ( ! $feed)
			throw new Exception('Invalid feed id');

		$feed = ORM::factory('Feed', $feed);

		Minion_CLI::write('Updating ' . $feed->name);

		$feeds = new Feeds();
		$feeds->update_single($feed);
	}

	/**
	 * Spawn a number of processes to update
	 * feeds in parallel
	 */
	private function spawn_childs($processes)
	{
		$processes = intval($processes);
		if ( ! $processes)
		{
			$processes = 6;
		}
		$running = array();
		
		// TODO only feeds with users
		$feed = new Model_Feed();
		$feeds = $feed->find_all();

		foreach ($feeds as $feed)
		{
			// wait until we're ready to spawn a new process
			$this->check_ready($running, $processes);

			$cmd = 'php ' . DOCROOT . 'index.php --task=feeds:update --feed=' . $feed->pk();

			// start new child and save pid
			$pipes = array();
			$running[] = proc_open($cmd, array(), $pipes);
		}
	}

	private function check_ready(&$running, $processes)
	{
		// not running the allowed amount of processes yet
		// so we can run some more
		if (count($running) < $processes)
			return true;

		// wait until one of the running processes has stopped
		// then we can continue
		while (true)
		{
			foreach ($running as $key => $procid)
			{
				$status = proc_get_status($procid);

				if ( ! $status['running'])
				{
					// properly close process
					proc_close($running[$key]);

					// remove process from running procs array
					unset($running[$key]);

					return true;
				}
			}

			// wait a bit, then check again
			usleep(50000); // 50milliseconds
		}
	}
}
