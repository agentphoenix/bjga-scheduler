<?php namespace Scheduler\Controllers;

use Log,
	Auth,
	Request,
	Controller;

abstract class BaseController extends Controller {

	protected $currentUser;
	protected $layout = 'layouts.master';
	protected $request;

	public function __construct()
	{
		$this->currentUser	= Auth::user();
		$this->request		= Request::instance();
	}

	protected function unauthorized()
	{
		Log::error("{$this->currentUser->name} attempted to access {$this->request->fullUrl()}");
	}

}