<?php namespace Scheduler\Controllers;

use Book,
	Date,
	View,
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

	public function block()
	{
		if ($this->currentUser->isStaff())
		{
			return View::make('pages.admin.staff.blocks')
				->with('blocks', $this->staff->getBlocks($this->currentUser));
		}
		else
		{
			$this->unauthorized();

			return View::make('pages.admin.error')
				->withError("Only staff members can block their schedules!");
		}
	}

	public function storeBlock()
	{
		if ($this->currentUser->isStaff())
		{
			$date = Date::createFromFormat('Y-m-d', Input::get('date'));

			if (Input::get('all_day'))
			{
				$schedule = $this->currentUser->staff->schedule->filter(function($s) use ($date)
				{
					return (int) $s->day === (int) $date->dayOfWeek;
				})->first();

				// Break the availability apart
				list($aStart, $aEnd) = explode('-', $schedule->availability);

				// Break the start and end times apart
				list($aStartHour, $aStartMinute) = explode(':', $aStart);
				list($aEndHour, $aEndMinute) = explode(':', $aEnd);
			}
			else
			{
				// Break the start and end times apart
				list($aStartHour, $aStartMinute) = explode(':', Input::get('start'));
				list($aEndHour, $aEndMinute) = explode(':', Input::get('end'));

				$start = Input::get('start');
				$end = Input::get('end');
			}

			$start = $date->copy()->hour($aStartHour)->minute($aStartMinute)->second(0);
			$end = $date->copy()->hour($aEndHour)->minute($aEndMinute)->second(0);

			Book::block(array(
				'staff'	=> Input::get('staff_id'),
				'start'	=> $date->copy()->hour($aStartHour)->minute($aStartMinute)->second(0),
				'end'	=> $date->copy()->hour($aEndHour)->minute($aEndMinute)->second(0),
			));

			return Redirect::route('admin')
				->with('message', "Schedule block was successfully entered.")
				->with('messageStatus', 'success');
		}
		else
		{
			$this->unauthorized();

			return View::make('pages.admin.error')
				->withError("Only staff members can block their schedules!");
		}
	}

	public function deleteBlock($id)
	{
		if ($this->currentUser->isStaff())
		{
			return partial('common/modal_content', array(
				'modalHeader'	=> "Remove Schedule Block",
				'modalBody'		=> View::make('pages.ajax.deleteScheduleBlock')->withId($id),
				'modalFooter'	=> false,
			));
		}
	}

	public function destroyBlock($id)
	{
		// Clear the block
		$this->staff->deleteBlock($id);

		// Fire the lesson booking event
		Event::fire('book.block.created', array($this->currentUser));

		return Redirect::route('admin.staff.block')
			->with('message', "Schedule block was successfully removed.")
			->with('messageStatus', 'success');
	}

	public function createBlock()
	{
		return partial('common/modal_content', array(
			'modalHeader'	=> "Create Schedule Block",
			'modalBody'		=> View::make('pages.ajax.createScheduleBlock')
								->withUser($this->currentUser),
			'modalFooter'	=> false,
		));
	}

	public function schedule($id)
	{
		if ($this->currentUser->isStaff())
		{
			return View::make('pages.admin.staff.schedule')
				->withStaff($this->staff->find($id))
				->withSchedule($this->staff->getSchedule($id, 7));
		}
		else
		{
			$this->unauthorized();

			return View::make('pages.admin.error')
				->withError("Only staff members can view schedules!");
		}
	}

}