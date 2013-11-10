<?php namespace Scheduler\Controllers;

use Log;
use Auth;
use View;
use Config;
use Request;
use stdClass;
use Controller;

class Base extends Controller {

	protected $_view;
	protected $_data;
	protected $currentUser;
	protected $layout = 'layouts.master';
	protected $request;

	public function __construct()
	{
		$this->currentUser	= Auth::user();
		$this->_data		= new stdClass;
		$this->request		= Request::instance();
	}

	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

	protected function finalizeLayout()
	{
		// Set the content view (if it's been set)
		if ( ! empty($this->_view))
		{
			$this->layout->content = View::make("pages.{$this->_view}")
				->with('_icons', Config::get('icons'))
				->with('_currentUser', $this->currentUser)
				->with((array) $this->_data);
		}
	}

	protected function processResponse($router, $method, $response)
	{
		$this->finalizeLayout();

		return parent::processResponse($router, $method, $response);
	}

	protected function unauthorized()
	{
		Log::error("{$this->currentUser->name} attempted to access {$this->request->fullUrl()}");
	}

}