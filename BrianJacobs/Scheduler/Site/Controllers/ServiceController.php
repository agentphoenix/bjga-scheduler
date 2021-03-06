<?php namespace Scheduler\Controllers;

use View,
	Event,
	Input,
	Redirect,
	ServiceValidator,
	StaffRepositoryInterface,
	ServiceRepositoryInterface,
	LocationRepositoryInterface;

class ServiceController extends BaseController {

	protected $staff;
	protected $service;
	protected $locations;

	public function __construct(ServiceRepositoryInterface $service,
			StaffRepositoryInterface $staff, LocationRepositoryInterface $locations)
	{
		parent::__construct();

		$this->staff = $staff;
		$this->service = $service;
		$this->locations = $locations;

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
		if ($this->currentUser->isStaff())
		{
			$staffId = ($this->currentUser->access() < 3) 
				? $this->currentUser->staff->id 
				: false;

			return View::make('pages.admin.services.index')
				->withServices($this->service->allByCategory(false, $staffId))
				->withCategories(array('lesson', 'program'));
		}
		else
		{
			return $this->unauthorized("You do not have permission to manage services!");
		}
	}

	public function store()
	{
		if ($this->currentUser->isStaff())
		{
			$validator = new ServiceValidator;

			if ( ! $validator->passes())
			{
				return Redirect::back()
					->withInput(Input::except(array(
						'service_dates',
						'service_times_start',
						'service_times_end'
					)))
					->withErrors($validator->getErrors())
					->with('message', 'Service could not be created because of errors. Please correct and try again.')
					->with('messageStatus', 'danger');
			}

			// Create the new service
			$service = $this->service->create(Input::all());

			// Fire the service created event
			Event::fire('service.created', array($service, Input::all()));

			return Redirect::route('admin.service.index')
				->with('message', 'Service was successfully created.')
				->with('messageStatus', 'success');
		}
		else
		{
			return $this->unauthorized("You do not have permission to create services!");
		}
	}

	public function edit($id)
	{
		if ($this->currentUser->isStaff())
		{
			// Get the service
			$service = $this->service->find($id);

			// Set up the staff array
			$staff[''] = 'Please choose an instructor';
			$staff += $this->staff->allForDropdown();

			// Set up the services array
			$services[] = 'Please choose a service';
			$services += $this->service->allForDropdownByCategory();

			if ($service->isLesson())
			{
				return View::make('pages.admin.services.editLessonService')
					->withService($service)
					->withStaff($staff);
			}

			if ($service->isProgram())
			{
				// Set up the locations array
				$locations = array_merge(
					['Please choose a location'],
					$this->locations->listAll('id', 'name')
				);

				return View::make('pages.admin.services.editProgramService')
					->withService($service)
					->withStaff($staff)
					->withSchedule($service->serviceOccurrences)
					->withLocations($locations);
			}
		}
		else
		{
			return $this->unauthorized("You do not have permission to update services!");
		}
	}

	public function update($id)
	{
		if ($this->currentUser->isStaff())
		{
			$validator = new ServiceValidator;

			if ( ! $validator->passes())
			{
				return Redirect::back()
					->withInput(Input::except(array(
						'service_dates',
						'service_times_start',
						'service_times_end'
					)))
					->withErrors($validator->getErrors())
					->with('message', 'Service could not be updated because of errors. Please correct and try again.')
					->with('messageStatus', 'danger');
			}

			// Update the service
			$service = $this->service->update($id, Input::all());

			// Fire the service updated event
			Event::fire('service.updated', array($service, Input::all()));

			return Redirect::route('admin.service.edit', array($id))
				->with('message', 'Service was successfully updated.')
				->with('messageStatus', 'success');
		}
		else
		{
			return $this->unauthorized("You do not have permission to edit services!");
		}
	}

	public function destroy($id)
	{
		if ($this->currentUser->isStaff() and $this->currentUser->access() > 2)
		{
			// Delete the service
			$service = $this->service->delete($id);

			// Fire the service deleted event
			Event::fire('service.deleted', array($service));

			return Redirect::route('admin.service.index')
				->with('message', "Service was successfully deleted.")
				->with('messageStatus', 'success');
		}
		else
		{
			return $this->unauthorized("You do not have permission to delete services!");
		}
	}

	public function createLessonService()
	{
		if ($this->currentUser->isStaff())
		{
			// Set up the staff array
			$staff[''] = 'Please choose an instructor';
			$staff += $this->staff->allForDropdown();

			// Set up the services array
			$services[] = 'Please choose a service';
			$services += $this->service->allForDropdownByCategory();

			return View::make('pages.admin.services.createLessonService')
				->withStaff($staff)
				->withServices($services);
		}
		else
		{
			return $this->unauthorized("You do not have permission to create services!");
		}
	}

	public function createProgramService()
	{
		if ($this->currentUser->isStaff())
		{
			// Set up the staff array
			$staff[''] = 'Please choose an instructor';
			$staff += $this->staff->allForDropdown();

			// Set up the services array
			$services[] = 'Please choose a service';
			$services += $this->service->allForDropdownByCategory();

			// Set up the locations array
			$locations = array_merge(
				['Please choose a location'],
				$this->locations->listAll('id', 'name')
			);

			return View::make('pages.admin.services.createProgramService')
				->withStaff($staff)
				->withServices($services)
				->withLocations($locations);
		}
		else
		{
			return $this->unauthorized("You do not have permission to create services!");
		}
	}

}