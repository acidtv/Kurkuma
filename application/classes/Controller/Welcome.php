<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Welcome extends Controller_Template {

	public function action_index()
	{
		$view = View::factory('welcome');
		$this->template->content = $view;
	}

} // End Welcome
