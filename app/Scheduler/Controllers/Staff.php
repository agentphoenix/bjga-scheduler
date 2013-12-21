<?php namespace Scheduler\Controllers;

use Date;
use Input;
use Redirect;
use StaffValidator;
use UserRepositoryInterface;
use StaffRepositoryInterface;

class Staff extends Base {

	public function __construct(StaffRepositoryInterface $staff,
			UserRepositoryInterface $user)
	{
		parent::__construct();

		$this->staff	= $staff;
		$this->user		= $user;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		if ($this->currentUser->isStaff() and $this->currentUser->access() > 1)
		{
			$this->_view = 'admin.staff.index';

			$this->_data->staff = $this->staff->all();
		}
		else
		{
			$this->unauthorized();

			$this->_view = 'admin.error';

			$this->_data->error = "You do not have permission to manage staff!";
		}
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		if ($this->currentUser->isStaff() and $this->currentUser->access() > 1)
		{
			$this->_view = 'admin.staff.create';

			$this->_data->users = $this->user->all()->filter(function($u)
			{
				return ( ! $u->isStaff());
			})->toSimpleArray('id', 'name');
		}
		else
		{
			$this->unauthorized();

			$this->_view = 'admin.error';

			$this->_data->error = "You do not have permission to create staff members!";
		}
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
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

			$staff = $this->staff->create(Input::all());

			return Redirect::route('admin.staff.index')
				->with('message', 'Staff member was successfully added.')
				->with('messageStatus', 'success');
		}
		else
		{
			$this->unauthorized();

			$this->_view = 'admin.error';

			$this->_data->error = "You do not have permission to create staff members!";
		}
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		// Get the staff member
		$staff = $this->staff->find($id);

		if ($this->currentUser->isStaff() and $this->currentUser->access() > 1
				or ($this->currentUser->isStaff() and $this->currentUser->access() == 1 and 
					$this->currentUser->staff->id == $staff->id))
		{
			$this->_view = 'admin.staff.edit';

			$this->_data->staff = $staff;

			$this->_data->schedule = $staff->schedule;

			$this->_data->exceptionsUpcoming = $staff->exceptions->filter(function($e)
			{
				$now = Date::now();
				$date = Date::createFromFormat('Y-m-d', $e->date);

				return $date->gte($now);
			});

			$this->_data->exceptionsHistory = $staff->exceptions->filter(function($e)
			{
				$now = Date::now();
				$date = Date::createFromFormat('Y-m-d', $e->date);

				return $date->lt($now);
			});

			$this->_data->days = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
		}
		else
		{
			$this->unauthorized();

			$this->_view = 'admin.error';

			$this->_data->error = "You do not have permission to edit staff members!";
		}
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
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

			if (Input::has('formAction'))
			{
				if (Input::get('formAction') == 'exceptions')
				{
					$this->staff->createException($staff->id, Input::all());
				}
			}
			else
			{
				$this->staff->update($id, Input::all());
			}

			return Redirect::route('admin.staff.edit', array($staff->id))
				->with('message', 'Staff member was successfully updated.')
				->with('messageStatus', 'success');
		}
		else
		{
			$this->unauthorized();

			$this->_view = 'admin.error';

			$this->_data->error = "You do not have permission to edit this staff member!";
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		if ($this->currentUser->isStaff() and $this->currentUser->access() > 1)
		{
			$this->staff->delete($id);

			return Redirect::route('admin.staff.index')
				->with('message', "Staff member was successfully removed.")
				->with('messageStatus', 'success');
		}
		else
		{
			$this->unauthorized();

			$this->_view = 'admin.error';

			$this->_data->error = "You do not have permission to remove staff members!";
		}
	}

	public function destroyException($id)
	{
		if ($this->currentUser->isStaff() and $this->currentUser->access() > 1)
		{
			$exception = $this->staff->findException($id);

			$this->staff->deleteException($id);

			return Redirect::route('admin.staff.edit', array($exception->staff_id))
				->with('message', "Schedule exception was successfully removed.")
				->with('messageStatus', 'success');
		}
		else
		{
			$this->unauthorized();

			$this->_view = 'admin.error';

			$this->_data->error = "You do not have permission to remove schedule exceptions!";
		}
	}

}