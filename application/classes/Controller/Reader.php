<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Reader extends Controller_Template {

	public $template = 'template_reader';

	public function action_index()
	{
		$view = View::factory('feeds');
		$this->template->content = $view;
	}

} // End Welcome
