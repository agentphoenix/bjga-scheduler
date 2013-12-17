<?php namespace Scheduler\Controllers;

use Event;
use Input;
use Redirect;
use ServiceValidator;
use StaffRepositoryInterface;
use ServiceRepositoryInterface;
use CategoryRepositoryInterface;

class Service extends Base {

	public function __construct(ServiceRepositoryInterface $service,
			CategoryRepositoryInterface $category,
			StaffRepositoryInterface $staff)
	{
		parent::__construct();

		$this->staff = $staff;
		$this->service = $service;
		$this->category = $category;
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
			$this->_view = 'admin.services.index';

			$this->_data->services = $this->service->allByCategory();
			$this->_data->categories = $this->category->all();
		}
		else
		{
			$this->unauthorized();

			$this->_view = 'admin.error';

			$this->_data->error = "You do not have permission to manage services!";
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

			$this->_view = 'admin.error';

			$this->_data->error = "You do not have permission to create services!";
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
		if ($this->currentUser->isStaff() and $this->currentUser->access() > 1)
		{
			$service = $this->_data->service = $this->service->find($id);

			$this->_data->categories[''] = 'Please choose a category';
			$this->_data->categories += $this->category->all()->toSimpleArray('id', 'name');

			$this->_data->staff[''] = 'Please choose a staff member';
			$this->_data->staff += $this->staff->allForDropdown();

			$this->_data->services[] = 'Please choose a service';
			$this->_data->services += $this->service->allForDropdownByCategory();

			$this->_data->schedule = $service->serviceOccurrences;

			$additionalServices = explode(';', $service->additional_services);

			if (is_array($additionalServices))
			{
				$additionalServicesArr = array();

				foreach ($additionalServices as $a)
				{
					$pieces = explode(',', $a);

					if (is_array($pieces) and count($pieces) == 2)
					{
						list($id, $count) = $pieces;
						
						$additionalServicesArr[] = array(
							'service'		=> $id,
							'occurrences'	=> $count
						);
					}
				}

				$this->_data->additionalServices = $additionalServicesArr;
			}
			else
			{
				$additionalServices = explode(',', $service->additional_services);

				if (is_array($additionalServices) and count($additionalServices) == 2)
				{
					list($id, $count) = $additionalServices;

					$additionalServicesArr['service'] = $id;
					$additionalServicesArr['occurrences'] = $count;
				}

				$this->_data->additionalServices = (is_array($additionalServices))
					? $additionalServices
					: array();
			}

			if ($service->isOneToOne())
				$this->_view = 'admin.services.editOneToOne';
			
			if ($service->isOneToMany())
				$this->_view = 'admin.services.editOneToMany';
			
			if ($service->isManyToMany())
				$this->_view = 'admin.services.editManyToMany';
		}
		else
		{
			$this->unauthorized();

			$this->_view = 'admin.error';

			$this->_data->error = "You do not have permission to update services!";
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

			$this->_view = 'admin.error';

			$this->_data->error = "You do not have permission to edit services!";
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

			$this->_view = 'admin.error';

			$this->_data->error = "You do not have permission to delete services!";
		}
	}

	public function createOneToOne()
	{
		if ($this->currentUser->isStaff() and $this->currentUser->access() > 1)
		{
			$this->_data->categories[''] = 'Please choose a category';
			$this->_data->categories += $this->category->all()->toSimpleArray('id', 'name');

			$this->_data->staff[''] = 'Please choose a staff member';
			$this->_data->staff += $this->staff->allForDropdown();

			$this->_data->services[] = 'Please choose a service';
			$this->_data->services += $this->service->allForDropdownByCategory();

			$this->_view = 'admin.services.createOneToOne';
		}
		else
		{
			$this->unauthorized();

			$this->_view = 'admin.error';

			$this->_data->error = "You do not have permission to create services!";
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