<?php namespace Scheduler\Controllers;

use URL,
	Auth,
	Date,
	View,
	Event,
	Flash,
	Input,
	Session,
	Redirect,
	LocationValidator,
	LocationRepositoryInterface;

class LocationsController extends BaseController {

	protected $locations;
	protected $validator;

	public function __construct(LocationRepositoryInterface $locations,
			LocationValidator $validator)
	{
		parent::__construct();

		$this->locations = $locations;
		$this->validator = $validator;

		$this->beforeFilter(function()
		{
			if (Auth::user() === null)
			{
				// Push the intended URL into the session
				Session::put('url.intended', URL::full());

				return Redirect::route('home')
					->with('message', "You must be logged in to continue.")
					->with('messageStatus', 'danger');
			}
		});
	}

	public function index()
	{
		return View::make('pages.admin.locations.index')
			->withLocations($this->locations->all());
	}

	public function create()
	{
		if ($this->currentUser->isStaff() and $this->currentUser->access() > 1)
		{
			return View::make('pages.admin.locations.create');
		}
		
		return $this->unauthorized("You do not have permission to create locations!");
	}

	public function store()
	{
		if ($this->currentUser->isStaff() and $this->currentUser->access() > 1)
		{
			// Validate the form
			$this->validator->validate(Input::all());

			// Create the location
			$location = $this->locations->create(Input::all());

			// Fire the location created event
			Event::fire('location.created', array($location, Input::all()));

			// Set the flash message
			Flash::success("Location has been successfully created.");

			return Redirect::route('admin.locations.index');
		}

		return $this->unauthorized("You do not have permission to create locations!");
	}

	public function edit($id)
	{
		if ($this->currentUser->isStaff() and $this->currentUser->access() > 1)
		{
			return View::make('pages.admin.locations.edit')
				->withLocation($this->locations->find($id));
		}
		
		return $this->unauthorized("You do not have permission to edit locations!");
	}

	public function update($id)
	{
		if ($this->currentUser->isStaff() and $this->currentUser->access() > 1)
		{
			// Validate the form
			$this->validator->validate(Input::all());
			
			// Update the location
			$location = $this->locations->update($id, Input::all());

			// Fire the event
			Event::fire('location.updated', [$location, Input::all()]);

			// Set the flash message
			Flash::success("Location has been successfully updated.");

			return Redirect::route('admin.locations.index');
		}

		return $this->unauthorized("You do not have permission to edit locations!");
	}

	public function delete($id)
	{
		return partial('common/modal_content', array(
			'modalHeader'	=> "Delete Location",
			'modalBody'		=> View::make('pages.admin.locations.delete')
				->withLocation($this->locations->find($id)),
			'modalFooter'	=> false,
		));
	}

	public function destroy($id)
	{
		if ($this->currentUser->isStaff() and $this->currentUser->access() >= 3)
		{
			// Delete the location
			$location = $this->locations->delete($id);

			// Fire the event
			Event::fire('location.deleted', [$location]);

			// Set the flash message
			Flash::success("Location has been successfully deleted.");

			return Redirect::route('admin.locations.index');
		}

		return $this->unauthorized("You do not have permission to remove locations!");
	}

	public function getLocationChange()
	{
		return partial('common/modal_content', array(
			'modalHeader'	=> "Change Location for XX/XX/XXXX",
			'modalBody'		=> false,
			'modalFooter'	=> false,
		));
	}

	public function postLocationChange()
	{
		# code...
	}

}