<?php namespace Scheduler\Controllers;

use Log,
	Auth,
	View,
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

	protected function unauthorized($message = false)
	{
		Log::error("{$this->currentUser->name} attempted to access {$this->request->fullUrl()}");

		if ($message)
			return View::make('pages.admin.error')->withError($message);
	}

}