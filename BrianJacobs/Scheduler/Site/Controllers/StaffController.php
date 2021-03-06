<?php namespace Scheduler\Controllers;

use Book,
	Date,
	View,
	Event,
	Input,
	Redirect,
	StaffValidator,
	UserRepositoryInterface,
	StaffRepositoryInterface,
	LocationRepositoryInterface;

class StaffController extends BaseController {

	protected $user;
	protected $staff;
	protected $location;

	public function __construct(StaffRepositoryInterface $staff,
			UserRepositoryInterface $user,
			LocationRepositoryInterface $location)
	{
		parent::__construct();

		$this->staff = $staff;
		$this->user = $user;
		$this->location = $location;

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
		if ($this->currentUser->isStaff() and $this->currentUser->access() > 1)
		{
			return View::make('pages.admin.staff.index')
				->withStaff($this->staff->all());
		}
		
		return $this->unauthorized("You do not have permission to manage staff!");
	}

	public function create()
	{
		if ($this->currentUser->isStaff() and $this->currentUser->access() > 1)
		{
			return View::make('pages.admin.staff.create')
				->withUsers($this->user->getNonStaff());
		}
		
		return $this->unauthorized("You do not have permission to create staff members!");
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

		return $this->unauthorized("You do not have permission to create staff members!");
	}

	public function edit($id)
	{
		// Get the staff member
		$staff = $this->staff->find($id);

		if ($this->currentUser->access() > 1 or ($this->currentUser->access() == 1 and $staff->user->id == $this->currentUser->id))
		{
			return View::make('pages.admin.staff.edit')
				->withStaff($staff);
		}
		
		return $this->unauthorized("You do not have permission to edit staff members!");
	}

	public function update($id)
	{
		// Get the staff member
		$staff = $this->staff->find($id);

		if ($this->currentUser->access() > 1 or ($this->currentUser->access() == 1 and $staff->user->id == $this->currentUser->id))
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
		
		return $this->unauthorized("You do not have permission to edit this staff member!");
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
			return $this->unauthorized("You do not have permission to remove staff members!");
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

				if (empty($schedule->availability))
				{
					return Redirect::route('admin.staff.schedule', [Input::get('staff_id')])
						->with('message', "No availability to block.")
						->with('messageStatus', 'warning');
				}

				// Break the availability apart
				list($aStart, $aEnd) = explode('-', $schedule->availability);

				// Break the start and end times apart
				list($aStartHour, $aStartMinute) = explode(':', $aStart);
				list($aEndHour, $aEndMinute) = explode(':', $aEnd);
			}
			else
			{
				$start = str_replace(' AM', '', Input::get('start'));
				$start = str_replace(' PM', '', $start);

				$end = str_replace(' AM', '', Input::get('end'));
				$end = str_replace(' PM', '', $end);

				// Break the start and end times apart
				list($aStartHour, $aStartMinute) = explode(':', $start);
				list($aEndHour, $aEndMinute) = explode(':', $end);
			}

			Book::block(array(
				'staff'	=> Input::get('staff_id'),
				'start'	=> $date->copy()->hour($aStartHour)->minute($aStartMinute)->second(0),
				'end'	=> $date->copy()->hour($aEndHour)->minute($aEndMinute)->second(0),
				'notes'	=> Input::get('notes'),
			));

			return Redirect::route('admin.staff.schedule', array(Input::get('staff_id')))
				->with('message', "Schedule block was successfully entered.")
				->with('messageStatus', 'success');
		}
		else
		{
			return $this->unauthorized("Only staff members can block their schedules!");
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
		Event::fire('book.block.created', array($this->currentUser, false));

		return Redirect::route('admin.staff.schedule', array($this->currentUser->staff->id))
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
			// Get the user record
			$staff = $this->staff->find($id);

			return View::make('pages.admin.staff.schedule')
				->withStaff($staff)
				->withBlocks($this->staff->getBlocks($staff->user))
				->withSchedule($staff->schedule)
				->withDays(['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']);
		}
		
		return $this->unauthorized("Only staff members can view schedules!");
	}

	public function editSchedule($staffId, $day)
	{
		if ($this->currentUser->isStaff())
		{
			$days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

			// Get the staff member
			$staff = $this->staff->find($staffId);

			// Get the schedule for the day
			$schedule = $staff->getScheduleForDay($day);

			$start = false;
			$end = false;

			if ( ! empty($schedule->availability))
			{
				list($start, $end) = explode('-', $schedule->availability);

				$start = Date::createFromFormat('G:i', $start)->format('g:i A');
				$end = Date::createFromFormat('G:i', $end)->format('g:i A');
				$noAvailability = false;
			}
			else
			{
				$noAvailability = true;
			}

			$locations = $this->location->listAll('id', 'name');

			return partial('common/modal_content', array(
				'modalHeader'	=> "Edit {$days[$day]} Schedule",
				'modalBody'		=> View::make('pages.admin.staff.ajax.editSchedule')
									->withStaff($staff)
									->withDay($days[$day])
									->withDaynum($day)
									->withStart($start)
									->withEnd($end)
									->withLocations($locations)
									->with('noAvailability', $noAvailability)
									->with('staffLocation', $schedule->location_id),
				'modalFooter'	=> false,
			));
		}
	}

	public function updateSchedule($id)
	{
		if ($this->currentUser->isStaff())
		{
			$availability = false;

			if (Input::get('no_times') != "1")
			{
				// Get the start
				$start = Date::createFromFormat('H:i', Input::get('start'))->format('G:i');

				// Get the end
				$end = Date::createFromFormat('H:i', Input::get('end'))->format('G:i');

				// Build the availability string
				$availability = "{$start}-{$end}";
			}

			$item = $this->staff->updateSchedule($id, Input::get('dayNum'), [
				'availability'	=> $availability,
				'location_id'	=> Input::get('location')
			]);

			if (Input::get('oldLocation') != Input::get('location'))
			{
				$this->staff->updateAppointmentLocations($id, Input::get('dayNum'));
			}

			return Redirect::route('admin.staff.schedule', [$id])
				->with('message', "Schedule was successfully updated.")
				->with('messageStatus', 'success');
		}
	}

	public function ajaxGetStaff($staffId)
	{
		// Get the staff member
		$staff = $this->staff->find($staffId);

		if ($staff)
		{
			$staff = $staff->load('user', 'schedule', 'schedule.location', 'services');

			$data['staff'] = [
				'id' => (int) $staff->id,
				'title' => $staff->title,
				'instructor' => (bool) $staff->instruction,
			];

			$data['user'] = [
				'id' => (int) $staff->user->id,
				'name' => $staff->user->name,
				'email' => $staff->user->email,
			];

			$days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

			foreach ($staff->schedule as $day)
			{
				$start = false;
				$end = false;
				$available = false;

				if ( ! empty($day->availability))
				{
					list($start, $end) = explode('-', $day->availability);

					$start = Date::createFromFormat('H:i', $start)->format('g:i A');
					$end = Date::createFromFormat('H:i', $end)->format('g:i A');
					$available = "{$start} - {$end}";
				}

				$data['schedule'][$days[$day->day]] = [
					'availability' => $available,
					'availabilityStart' => $start,
					'availabilityEnd' => $end,
					'locationId' => $day->location_id,
					'location' => $day->location->name,
				];
			}

			$data['services'] = [];

			return json_encode($data);
		}

		return json_encode([]);
	}

}