<?php namespace Scheduler\Controllers;

use Date,
	View,
	Input,
	Redirect,
	StaffAppointmentModel,
	UserRepositoryInterface;

class ReportController extends BaseController {

	protected $user;

	public function __construct(UserRepositoryInterface $user)
	{
		$this->user = $user;

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
		return View::make('pages.admin.reports.index');
	}

	public function monthly($date = false)
	{
		// Get the date
		$date = ( ! $date) ? Date::now() : Date::createFromFormat('Y-m', $date);

		// Get today
		$today = Date::now();

		// Dropdown date
		$ddDate = $date->copy()->month(1)->year(2014);

		// Variables for storing all our info
		$dateOptions = array();
		$students = array();
		$studentsYTD = array();
		$lessonHours = 0;
		$lessonHoursYTD = 0;
		$revenue = 0;
		$revenueYTD = 0;

		// Get a list of the available months for reporting
		while($ddDate->startOfMonth() <= $today->startOfMonth())
		{
			// Store the month and year
			$dateOptions[$ddDate->format('Y-m')] = $ddDate->format('F Y');

			// Step to the next month
			$ddDate->addMonth();
		}

		// Sort from latest month to earliest
		krsort($dateOptions);

		// Get all appointments for the month
		$appointments = StaffAppointmentModel::withTrashed()
			->with(array('userAppointments' => function($query)
			{
				$query->withTrashed();
			}))
			->where('start', '>=', $date->copy()->startOfMonth())
			->where('end', '<=', $date->copy()->endOfMonth())
			->get();

		// Make sure we have appointments...
		if ($appointments->count() > 0)
		{
			foreach ($appointments as $a)
			{
				$lessonHoursCounted = false;

				if ($a->userAppointments->count() > 0)
				{
					foreach ($a->userAppointments as $u)
					{
						if ( ! $u->user->isStaff())
						{
							// Lesson Hours
							if ( ! $lessonHoursCounted and $a->service)
							{
								$lessonHours += $a->service->duration;
								$lessonHoursCounted = true;
							}

							// Students Seen
							$students[$u->user->id] = $u->user;

							// Revenue
							$revenue += $u->amount;
						}
					}
				}
			}
		}

		// Get all the appointments for the year
		$yearAppointments = StaffAppointmentModel::withTrashed()
			->with(array('userAppointments' => function($query)
			{
				$query->withTrashed();
			}))
			->where('start', '>=', $date->copy()->startOfYear())
			->where('end', '<=', $date->copy()->endOfYear())
			->get();

		if ($yearAppointments->count() > 0)
		{
			foreach ($yearAppointments as $a)
			{
				$lessonHoursCounted = false;

				if ($a->userAppointments->count() > 0)
				{
					foreach ($a->userAppointments as $u)
					{
						if ( ! $u->user->isStaff())
						{
							// Lesson Hours
							if ( ! $lessonHoursCounted and $a->service)
							{
								$lessonHoursYTD += $a->service->duration;
								$lessonHoursCounted = true;
							}

							// Students Seen
							$studentsYTD[$u->user->id] = $u->user;

							// Revenue
							$revenueYTD += $u->amount;
						}
					}
				}
			}
		}

		return View::make('pages.admin.reports.monthly')
			->withStudents(count($students))
			->withHours(round($lessonHours / 60, 1))
			->withRevenue($revenue)
			->withUnpaid($this->user->getUnpaidAmount())
			->withOptions($dateOptions)
			->withDate($date)
			->with('revenueYTD', $revenueYTD)
			->with('hoursYTD', round($lessonHoursYTD / 60, 1))
			->with('studentsYTD', count($studentsYTD));
	}

	public function updateMonthly()
	{
		return Redirect::route('admin.reports.monthly', array(Input::get('date')));
	}

	public function unpaid()
	{
		return View::make('pages.admin.reports.unpaid')
			->withAmount($this->user->getUnpaidAmount())
			->withUnpaid($this->user->getUnpaid());
	}

}