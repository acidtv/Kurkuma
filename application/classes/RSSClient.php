<?

class RSSClient {

	public $max_redirs = 5;

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
		$request = $this->get_request();
		$response = $request->execute();
		$this->status = $response->status();

		// follow redirects
		$redirs = 0;
		$previous_status = null;
		while (in_array($response->status(), array(301, 302)) && $redirs <= $this->max_redirs)
		{
			$previous_status = $response->status();
			$this->url = $response->headers('Location');

			$request = $this->get_request();
			$response = $request->execute();
			$this->status = $response->status();

			$redirs++;
		}

		if ($response->status() == 200)
		{
			// success!
			$client = $this->get_client($response->body());
			$this->_client = $client;
			$this->items = $client->get_items();

			if ($response->headers('Date'))
			{
				$this->modified = $response->headers('Date');
			}

			if ($response->headers('ETag'))
			{
				$this->etag = $response->headers('ETag');
			}
		}

		return array(
			'status' => $response->status(),
			'url' => $this->url,
			'previous_status' => $previous_status
		);
	}

	public function get_items()
	{
		return $this->items;
	}

	private function get_request()
	{
		$request = Request::factory($this->url);
		$request->headers('user-agent', 'Kurkuma RSS Reader v1');

		if ($this->modified)
		{
			$request->headers('If-Modified-Since', $this->modified);
		}

		if ($this->etag)
		{
			$request->headers('If-None-Match', $this->etag);
		}

		return $request;
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
		if ( ! $this->_client)
			return;

		return $this->_client->get_title();
	}
}
