<?php namespace Scheduler\Controllers;

use Auth,
	Book,
	View,
	Input,
	Redirect,
	StaffAppointmentModel,
	ServiceRepositoryInterface;

class BookingController extends BaseController {

	public function __construct(ServiceRepositoryInterface $service)
	{
		parent::__construct();

		$this->service = $service;
	}

	public function lesson()
	{
		return View::make('pages.booking.lesson')
			->withServices(array('0' => "Please choose one") + $this->service->getValues('lesson', true));
	}
	public function storeLesson()
	{
		Book::lesson(Input::all());

		return Redirect::route('home')
			->with('message', 'Your appointment has been booked!')
			->with('messageStatus', 'success');
	}

	public function program()
	{
		// Get the services for this category
		$services = $this->service->allPrograms(90, true);

		$allServices = array('' => "Please choose one") + $this->service->forDropdown($services, 'id', 'name');

		return View::make('pages.booking.program')
			->withServices($allServices);
	}
	public function storeProgram()
	{
		if ( ! $this->currentUser->isAttending(Input::get('service_id')))
		{
			Book::program(Input::all());

			return Redirect::route('home')
				->with('message', "You've successfully enrolled in the program.")
				->with('messageStatus', 'success');
		}
		else
		{
			return Redirect::back()
				->with('message', "You're already enrolled in this program!")
				->with('messageStatus', 'warning');
		}
	}

	public function withdraw()
	{
		// Get the appointment
		$appointment = StaffAppointmentModel::find(Input::get('appointment'));

		if ($appointment)
		{
			// Get the user
			$user = Auth::user();

			if ($user->isAttending($appointment->service->id))
			{
				// Figure out if we're cancelling everything
				$cancelAll = (Input::get('cancel_all', false) == '1') ? true : false;

				// Cancel the appointment
				Book::cancel('student', Input::get('appointment'), Input::get('reason'), $cancelAll);

				return Redirect::route('home')
					->with('message', "Appointment was successfully cancelled. The instructor has been notified of the cancellation.")
					->with('messageStatus', 'success');
			}
			else
			{
				return Redirect::route('home')
					->with('message', "You are not an attendee of this appointment and cannot withdraw from it!")
					->with('messageStatus', 'danger');
			}
		}
		else
		{
			return Redirect::route('home')
				->with('message', "No appointment found!")
				->with('messageStatus', 'warning');
		}
	}

	public function cancel()
	{
		if ($this->currentUser->isStaff())
		{
			// Figure out if we're cancelling everything
			$cancelAll = (Input::get('cancel_all', false) == '1') ? true : false;

			// Cancel the appointment
			Book::cancel('instructor', Input::get('appointment'), Input::get('reason'), $cancelAll);

			return Redirect::route('home')
				->with('message', "Appointment was successfully cancelled. All attendees have been notified of the cancellation.")
				->with('messageStatus', 'success');
		}
	}

	public function enroll()
	{
		// Get the service
		$service = $this->service->find(Input::get('service'));

		// Figure out how we should book this
		if ($service->isProgram())
		{
			Book::program(array(
				'user'			=> $this->currentUser->id,
				'service_id'	=> $service->id,
			));

			return Redirect::route('home')
				->with('message', "You've been successfully enrolled in {$service->name}.")
				->with('messageStatus', 'success');
		}
		else
		{
			return Redirect::back()
				->with('message', "You've attempted to enroll a lesson service. This feature is only available for enrolling in programs. Please use the booking page to book a lesson service.")
				->with('messageStatus', 'danger');
		}
	}

}