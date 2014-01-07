<?php namespace Scheduler\Controllers;

use View,
	Event,
	Input,
	Redirect,
	ServiceValidator,
	StaffRepositoryInterface,
	ServiceRepositoryInterface;

class ServiceController extends BaseController {

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
			$this->unauthorized();

			return View::make('pages.admin.error')
				->withError('You do not have permission to manage services!');
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

			$service = $this->service->create(Input::all());

			Event::fire('scheduler.service.created', $service);

			return Redirect::route('admin.service.index')
				->with('message', 'Service was successfully created.')
				->with('messageStatus', 'success');
		}
		else
		{
			$this->unauthorized();

			return View::make('pages.admin.error')
				->withError("You do not have permission to create services!");
		}
	}

	public function edit($id)
	{
		if ($this->currentUser->isStaff() and $this->currentUser->access() > 1)
		{
			$service = $this->service->find($id);

			$categories = array(
				''			=> "Please choose a category",
				'lesson'	=> "Lesson",
				'program'	=> "Program",
			);

			$staff[''] = 'Please choose a staff member';
			$staff += $this->staff->allForDropdown();

			$services[] = 'Please choose a service';
			$services += $this->service->allForDropdownByCategory();

			$schedule = $service->serviceOccurrences;

			if ($service->isOneToOne())
			{
				return View::make('pages.admin.services.editLessonService')
					->withService($service)
					->withStaff($staff);
			}
			
			if ($service->isOneToMany())
				$this->_view = 'admin.services.editOneToMany';
			
			if ($service->isManyToMany())
				$this->_view = 'admin.services.editManyToMany';
		}
		else
		{
			$this->unauthorized();

			return View::make('pages.admin.error')
				->withError("You do not have permission to update services!");
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

			$service = $this->service->update($id, Input::all());

			Event::fire('scheduler.service.updated', $service);

			return Redirect::route('admin.service.edit', array($id))
				->with('message', 'Service was successfully updated.')
				->with('messageStatus', 'success');
		}
		else
		{
			$this->unauthorized();

			return View::make('pages.admin.error')
				->withError("You do not have permission to edit services!");
		}
	}

	public function destroy($id)
	{
		if ($this->currentUser->isStaff() and $this->currentUser->access() > 2)
		{
			$service = $this->service->delete($id);

			Event::fire('scheduler.service.deleted', $service);

			return Redirect::route('admin.service.index')
				->with('message', "Service was successfully deleted.")
				->with('messageStatus', 'success');
		}
		else
		{
			$this->unauthorized();

			return View::make('pages.admin.error')
				->withError("You do not have permission to delete services!");
		}
	}

	public function createLessonService()
	{
		if ($this->currentUser->isStaff() and $this->currentUser->access() > 1)
		{
			$categories = array(
				''			=> "Please choose a category",
				'lesson'	=> "Lesson",
				'program'	=> "Program",
			);

			$staff[''] = 'Please choose a staff member';
			$staff += $this->staff->allForDropdown();

			$services[] = 'Please choose a service';
			$services += $this->service->allForDropdownByCategory();

			return View::make('pages.admin.services.createLessonService')
				->withStaff($staff)
				->withServices($services);
		}
		else
		{
			$this->unauthorized();

			return View::make('pages.admin.error')
				->withError("You do not have permission to create services!");
		}
	}

	public function createOneToMany()
	{
		if ($this->currentUser->isStaff() and $this->currentUser->access() > 1)
		{
			$this->_data->categories[''] = 'Please choose a category';
			$this->_data->categories += $this->category->all()->toSimpleArray('id', 'name');

			$this->_data->staff[''] = 'Please choose a staff member';
			$this->_data->staff += $this->staff->allForDropdown();

			$this->_data->services[] = 'Please choose a service';
			$this->_data->services += $this->service->allForDropdownByCategory();

			$this->_view = 'admin.services.createOneToMany';
		}
		else
		{
			$this->unauthorized();

			$this->_view = 'admin.error';

			$this->_data->error = "You do not have permission to create services!";
		}
	}

	public function createManyToMany()
	{
		if ($this->currentUser->isStaff() and $this->currentUser->access() > 1)
		{
			$this->_data->categories[''] = 'Please choose a category';
			$this->_data->categories += $this->category->all()->toSimpleArray('id', 'name');

			$this->_data->staff[''] = 'Please choose a staff member';
			$this->_data->staff += $this->staff->allForDropdown();

			$this->_data->services[] = 'Please choose a service';
			$this->_data->services += $this->service->allForDropdownByCategory();

			$this->_view = 'admin.services.createManyToMany';
		}
		else
		{
			$this->unauthorized();

			$this->_view = 'admin.error';

			$this->_data->error = "You do not have permission to create services!";
		}
	}

}