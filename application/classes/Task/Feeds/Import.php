<?

class Task_Feeds_Import extends Minion_Task {
	
	protected $_options = array(
		'file' => null,
		'user' => null,
	);

	public function _execute(array $params)
	{
		$xml = file_get_contents($params['file']);
		$user = ORM::factory('User', $params['user']);
		$feeds = new Feeds();
		$feeds->register_callback(function ($msg) { Minion_CLI::write($msg);});
	
		if ( ! $user->loaded())
			throw new Exception('User does not exist');

		$dom = new DOMDocument();
		$dom->loadXML($xml);

		$stack = array($dom);
		$i = 0;

		while ($parent = array_shift($stack))
		{
			foreach ($parent->childNodes as $node)
			{
				if ($node instanceof DOMElement 
					&& $node->tagName == 'outline'
					&& $node->hasAttribute('xmlUrl'))
				{
					Minion_CLI::write($node->getAttribute('xmlUrl'));
					try
					{
						$feeds->add_feed($node->getAttribute('xmlUrl'), $user);
					}
					catch (Exception $e)
					{
						Minion_CLI::write('Could not add feed: ' . $e->getMessage());
					}
				}

				if ($node->hasChildNodes())
				{
					// recursion is for noobs ;)
					$stack[] = $node;
				}
			}
		}

		//return $dom->saveHTML();
	}
}
