<?

class RSSClient {

	private $url = null;

	private $modified = null;

	private $etag = null;

	private $items = array();

	private $_client = null;

	private $status = null;

	public static function factory($url)
	{
		return new RSSClient($url);
	}

	public function __construct($url)
	{
		$this->url = $url;
	}

	public function modified($modified = null)
	{
		if ($modified == null)
		{
			// get
			return $this->modified;
		}

		// set
		$this->modified = $modified;
		return $this;
	}
	
	public function etag($etag = null)
	{
		if ($etag == null)
		{
			// get
			return $this->etag;
		}

		// set
		$this->etag = $etag;
		return $this;
	}

	public function status()
	{
		return $this->status;
	}

	public function init()
	{
		$request = Request::factory($this->url);

		if ($this->modified)
		{
			$request->headers('If-Modified-Since', $this->modified);
		}

		if ($this->etag)
		{
			$request->headers('If-None-Match', $this->etag);
		}

		$response = $request->execute();
		$this->status = $response->status();

		if ($response->headers('Date'))
		{
			$this->modified = $response->headers('Date');
		}

		if ($response->headers('ETag'))
		{
			$this->etag = $response->headers('ETag');
		}

		if ($response->status() == 304)
		{
			// not modified
			return;
		}
		elseif ($response->status() != 200)
		{
			throw new Exception('Invalid response: ' . $response->status());
		}

		$client = $this->get_client($response->body());
		$this->_client = $client;

		$this->items = $client->get_items();
	}

	public function get_items()
	{
		return $this->items;
	}

	private function get_client($content)
	{
		$client = new SimplePie();
		$client->enable_cache(false);

		if ( ! $content)
			return $client;

		$client->set_raw_data($content);

		if ( ! $client->init())
			throw new Exception('Could not parse feed: ' . $url);

		return $client;
	}

	public function get_title()
	{
		return $this->_client->get_title();
	}
}
