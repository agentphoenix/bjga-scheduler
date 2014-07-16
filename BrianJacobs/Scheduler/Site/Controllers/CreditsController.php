<?php namespace Scheduler\Controllers;

use View,
	Event,
	Flash,
	Input,
	Session,
	Redirect,
	CreditValidator,
	UserRepositoryInterface,
	CreditRepositoryInterface;

class CreditsController extends BaseController {

	protected $users;
	protected $credits;
	protected $validator;

	public function __construct(CreditRepositoryInterface $credits,
			UserRepositoryInterface $users, CreditValidator $validator)
	{
		parent::__construct();

		$this->users = $users;
		$this->credits = $credits;
		$this->validator = $validator;

		$this->beforeFilter(function()
		{
			if (\Auth::user() === null)
			{
				// Push the intended URL into the session
				\Session::put('url.intended', \URL::full());

				// Set the flash message
				Flash::error("You must be logged in to continue.");

				return Redirect::route('home');
			}
		});
	}

	public function index()
	{
		return View::make('pages.admin.credits.index')
			->withCredits($this->credits->allPaginated());
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
		// Validate the form
		$this->validator->validate(Input::all());
		
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
		return View::make('pages.admin.credits.edit')
			->withCredit($this->credits->find($id))
			->withUsers($this->users->allForDropdown())
			->withTypes(['time' => 'Time Credit', 'money' => 'Monetary Credit']);
	}

	public function update($id)
	{
		// Validate the form
		$this->validator->validate(Input::all());
		
		// Update the credit
		$credit = $this->credits->update($id, Input::except('valueMoney', 'valueTime'));

		// Fire the event
		Event::fire('credit.updated', [$credit, Input::all()]);

		// Set the flash message
		Flash::success("Credit has been successfully updated.");

		return Redirect::route('admin.credits.index');
	}

	public function delete($id)
	{
		return partial('common/modal_content', array(
			'modalHeader'	=> "Delete User Credit",
			'modalBody'		=> View::make('pages.admin.credits.delete')->withCredit($this->credits->find($id)),
			'modalFooter'	=> false,
		));
	}

	public function destroy($id)
	{
		// Delete the credit
		$credit = $this->credits->delete($id);

		// Fire the event
		Event::fire('credit.deleted', [$credit]);

		// Set the flash message
		Flash::success("Credit has been successfully deleted.");

		return Redirect::route('admin.credits.index');
	}

	public function doSearch()
	{
		return View::make('pages.admin.credits.index')
			->withCredits($this->credits->search(Input::get('search')));
	}

}