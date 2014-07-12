<?php namespace Scheduler\Controllers;

use View,
	Event,
	Input,
	Redirect,
	CreditValidator,
	UserRepositoryInterface,
	CreditRepositoryInterface;

class CreditsController extends BaseController {

	protected $users;
	protected $credits;

	public function __construct(CreditRepositoryInterface $credits,
			UserRepositoryInterface $users)
	{
		parent::__construct();

		$this->users = $users;
		$this->credits = $credits;

		$this->beforeFilter(function()
		{
			if (\Auth::user() === null)
			{
				// Push the intended URL into the session
				\Session::put('url.intended', \URL::full());

				return Redirect::route('home')
					->with('message', "You must be logged in to continue.")
					->with('messageStatus', 'danger');
			}
		});
	}

	public function index()
	{
		return View::make('pages.admin.credits.index')
			->withCredits($this->credits->all());
	}

	public function create()
	{
		return View::make('pages.admin.credits.create')
			->withCode(\Str::creditCode(12))
			->withUsers($this->users->allForDropdown())
			->withTypes(['time' => 'Time Credit', 'money' => 'Monetary Credit']);
	}

	public function store()
	{
		$this->credits->create(Input::except('valueMoney', 'valueTime'));
	}

	public function edit($id)
	{
		//
	}

	public function update($id)
	{
		//
	}

	public function destroy($id)
	{
		//
	}

}