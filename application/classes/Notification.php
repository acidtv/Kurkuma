<?php

/**
 * The notifications class stores notifications
 * until the next pageload where they are displayed
 */
class Notification
{
	/**
	 * Name of the session variable where the notifications are stored
	 */
	public $name = '';

	/**
	 * The types of possible notifications
	 */
	public $types = array('info', 'warn', 'err');

	protected $session = null;

	public static function instance()
	{
		return new Notification();
	}

	public function __construct()
	{
		$this->name = 'notifications';
		$this->session = Session::instance();

		if ( ! is_array($this->session->get($this->name)))
		{
			$this->session->set($this->name, array());
		}
	}

	/**
	 * Add a notification to the stack
	 *
	 * @param unknown $sText string The text to display in the notification
	 * @param unknown $sType string The notification type, see $aNotificationTypes
	 */
	public function add($text, $type = 'info')
	{
		$notification = array(
			'type' => $type,
			'text' => $text
		);

		// check for duplicate notifications
		foreach ($this->session->get($this->name) as $item)
		{
			if (Arr::get($item, 'type') == $notification['type'] && Arr::get($item, 'text') == $notification['text'])
			{
				return;
			}
		}

		$notifications = $this->session->get($this->name);
		$notifications[] = $notification;

		$this->session->set($this->name, $notifications);
	}

	/**
	 * Returns an array with pending notifications
	 *
	 * @param unknown $bClear boolean If this param is true the notifications stack will be emptied after calling this function
	 */
	public function get($clear = true)
	{
		$ret = $this->session->get($this->name);
		if ($clear) 
		{
			$this->clear();
		}

		return $aRet;
	}

	/**
	 * Clears the pending notifications, this typically happens after each pageload
	 */
	public function clear()
	{
		$this->session->set($this->session, array());
	}

}
