<?php namespace Scheduler\Controllers;

use View,
	Event,
	Flash,
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
		// Validate
		
		// Create the credit
		$credit = $this->credits->create(Input::except('valueMoney', 'valueTime'));

		// Fire the event
		Event::fire('credit.created', [$credit, Input::all()]);

		// Set the flash message
		Flash::success("Credit has been successfully created.");

		return Redirect::route('admin.credits.index');
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