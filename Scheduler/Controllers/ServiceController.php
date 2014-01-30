<?php namespace Scheduler\Controllers;

use View,
	Event,
	Input,
	Redirect,
	ServiceValidator,
	StaffRepositoryInterface,
	ServiceRepositoryInterface;

class ServiceController extends BaseController {

	protected $staff;
	protected $service;

	public function __construct(ServiceRepositoryInterface $service,
			StaffRepositoryInterface $staff)
	{
		parent::__construct();

		$this->staff = $staff;
		$this->service = $service;
	}

	public function index()
	{
		if ($this->currentUser->isStaff() and $this->currentUser->access() > 1)
		{
			return View::make('pages.admin.services.index')
				->withServices($this->service->allByCategory())
				->withCategories(array('lesson', 'program'));
		}
		else
		{
			$this->unauthorized("You do not have permission to manage services!");
		}
	}

	public function store()
	{
		if ($this->currentUser->isStaff() and $this->currentUser->access() > 1)
		{
			$validator = new ServiceValidator;

			if ( ! $validator->passes())
			{
				return Redirect::back()
					->withInput()
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
			$this->unauthorized("You do not have permission to create services!");
		}
	}

	public function edit($id)
	{
		if ($this->currentUser->isStaff() and $this->currentUser->access() > 1)
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
				return View::make('pages.admin.services.editProgramService')
					->withService($service)
					->withStaff($staff)
					->withSchedule($service->serviceOccurrences);
			}
		}
		else
		{
			$this->unauthorized("You do not have permission to update services!");
		}
	}

	public function update($id)
	{
		if ($this->currentUser->isStaff() and $this->currentUser->access() > 1)
		{
			$validator = new ServiceValidator;

			if ( ! $validator->passes())
			{
				return Redirect::back()
					->withInput()
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
			$this->unauthorized("You do not have permission to edit services!");
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
			$this->unauthorized("You do not have permission to delete services!");
		}
	}

	public function createLessonService()
	{
		if ($this->currentUser->isStaff() and $this->currentUser->access() > 1)
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
			$this->unauthorized("You do not have permission to create services!");
		}
	}

	public function createProgramService()
	{
		if ($this->currentUser->isStaff() and $this->currentUser->access() > 1)
		{
			// Set up the staff array
			$staff[''] = 'Please choose an instructor';
			$staff += $this->staff->allForDropdown();

			// Set up the services array
			$services[] = 'Please choose a service';
			$services += $this->service->allForDropdownByCategory();

			return View::make('pages.admin.services.createProgramService')
				->withStaff($staff)
				->withServices($services);
		}
		else
		{
			$this->unauthorized("You do not have permission to create services!");
		}
	}

}