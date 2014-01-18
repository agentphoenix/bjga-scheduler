<?php namespace Scheduler\Controllers;

use View,
	Input,
	Redirect,
	StaffValidator,
	UserRepositoryInterface,
	StaffRepositoryInterface;

class StaffController extends BaseController {

	protected $user;
	protected $staff;

	public function __construct(StaffRepositoryInterface $staff,
			UserRepositoryInterface $user)
	{
		parent::__construct();

		$this->staff	= $staff;
		$this->user		= $user;
	}

	public function index()
	{
		if ($this->currentUser->isStaff() and $this->currentUser->access() > 1)
		{
			return View::make('pages.admin.staff.index')
				->withStaff($this->staff->all());
		}
		else
		{
			$this->unauthorized();

			return View::make('pages.admin.error')
				->withError("You do not have permission to manage staff!");
		}
	}

	public function create()
	{
		if ($this->currentUser->isStaff() and $this->currentUser->access() > 1)
		{
			return View::make('pages.admin.staff.create')
				->withUsers($this->user->getNonStaff());
		}
		else
		{
			$this->unauthorized();

			return View::make('pages.admin.error')
				->withError("You do not have permission to create staff members!");
		}
	}

	public function store()
	{
		if ($this->currentUser->isStaff() and $this->currentUser->access() > 1)
		{
			$validator = new StaffValidator;

			if ( ! $validator->passes())
			{
				return Redirect::back()
					->withInput()
					->withErrors($validator->getErrors())
					->with('message', 'Staff member could not be created because of errors. Please correct and try again.')
					->with('messageStatus', 'danger');
			}

			// Create the staff member
			$staff = $this->staff->create(Input::all());

			// Fire the staff created event
			Event::fire('staff.created', array($staff));

			return Redirect::route('admin.staff.index')
				->with('message', 'Staff member was successfully added.')
				->with('messageStatus', 'success');
		}
		else
		{
			$this->unauthorized();

			return View::make('pages.admin.error')
				->withError("You do not have permission to create staff members!");
		}
	}

	public function edit($id)
	{
		// Get the staff member
		$staff = $this->staff->find($id);

		if ($this->currentUser->isStaff() and $this->currentUser->access() > 1
				or ($this->currentUser->isStaff() and $this->currentUser->access() == 1 and 
					$this->currentUser->staff->id == $staff->id))
		{
			return View::make('pages.admin.staff.edit')
				->withStaff($staff)
				->withSchedule($staff->schedule)
				->withDays(array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'));
		}
		else
		{
			$this->unauthorized();

			return View::make('pages.admin.error')
				->withError("You do not have permission to edit staff members!");
		}
	}

	public function update($id)
	{
		// Get the staff member
		$staff = $this->staff->find($id);

		if (($staff->user->isStaff() and $staff->user->access() > 1) 
				or ($staff->user->isStaff() and $staff->user->access == 1 and $staff->user == $this->currentUser)
				or ( ! $staff->user->isStaff() and $staff->user == $this->currentUser))
		{
			$validator = new StaffValidator;

			if ( ! $validator->passes())
			{
				return Redirect::back()
					->withInput()
					->withErrors($validator->getErrors())
					->with('message', 'Staff member could not be updated because of errors. Please correct and try again.')
					->with('messageStatus', 'danger');
			}

			$item = $this->staff->update($id, Input::all());

			Event::fire('staff.updated', array($item));

			return Redirect::route('admin.staff.edit', array($staff->id))
				->with('message', 'Staff member was successfully updated.')
				->with('messageStatus', 'success');
		}
		else
		{
			$this->unauthorized();

			return View::make('pages.admin.error')
				->withError("You do not have permission to edit this staff member!");
		}
	}

	public function destroy($id)
	{
		if ($this->currentUser->isStaff() and $this->currentUser->access() > 1)
		{
			$staff = $this->staff->delete($id);

			Event::fire('staff.deleted', array($staff));

			return Redirect::route('admin.staff.index')
				->with('message', "Staff member was successfully removed.")
				->with('messageStatus', 'success');
		}
		else
		{
			$this->unauthorized();

			return View::make('pages.admin.error')
				->withError("You do not have permission to remove staff members!");
		}
	}

}