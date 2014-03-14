<?php namespace Scheduler\Controllers;

use Hash,
	Mail,
	View,
	Event,
	Input,
	Redirect,
	MailChimp,
	UserValidator,
	UserRepositoryInterface;

class UserController extends BaseController {

	public function __construct(UserRepositoryInterface $user)
	{
		parent::__construct();

		$this->user = $user;
	}

	public function index()
	{
		if ($this->currentUser->isStaff() and $this->currentUser->access() > 1)
		{
			return View::make('pages.admin.users.index')
				->withUsers($this->user->all());
		}
		else
		{
			$this->unauthorized();

			return View::make('pages.admin.error')
				->withError("You do not have permission to manage users!");
		}
	}

	public function create()
	{
		if ($this->currentUser->isStaff() and $this->currentUser->access() > 1)
		{
			return View::make('pages.admin.users.create');
		}
		else
		{
			$this->unauthorized();

			return View::make('pages.admin.error')
				->withError("You do not have permission to create users!");
		}
	}

	public function store()
	{
		if ($this->currentUser->isStaff() and $this->currentUser->access() > 2)
		{
			$validator = new UserValidator;

			if ( ! $validator->passes())
			{
				return Redirect::back()
					->withInput()
					->withErrors($validator->getErrors())
					->with('message', 'User could not be created because of errors. Please correct and try again.')
					->with('messageStatus', 'danger');
			}

			// Create the user
			$user = $this->user->create(Input::all());

			// Fire the user created event
			Event::fire('user.created', array($user, Input::all()));

			return Redirect::route('admin.user.index')
				->with('message', 'User was successfully created.')
				->with('messageStatus', 'success');
		}
		else
		{
			$this->unauthorized();

			return View::make('pages.admin.error')
				->withError("You do not have permission to create users!");
		}
	}

	public function edit($id)
	{
		// Get the user
		$user = $this->user->find($id);

		if (($this->currentUser->isStaff() and $this->currentUser->access() > 1) 
				or ($this->currentUser->id == $user->id))
		{
			return View::make('pages.admin.users.edit')
				->withUser($user);
				//->withSubscription(MailChimp::findUser($user));
		}
		else
		{
			$this->unauthorized();

			return View::make('pages.admin.error')
				->withError("You do not have permission to edit this user!");
		}
	}

	public function update($id)
	{
		// Get the user
		$user = $this->user->find($id);

		if (($user->isStaff() and $user->access() > 1) 
				or ($user->isStaff() and $user->access == 1 and $user == $this->currentUser)
				or ( ! $user->isStaff() and $user == $this->currentUser))
		{
			$validator = new UserValidator;

			if ( ! $validator->passes())
			{
				return Redirect::back()
					->withInput()
					->withErrors($validator->getErrors())
					->with('message', 'User could not be updated because of errors. Please correct and try again.')
					->with('messageStatus', 'danger');
			}

			if (Input::has('formAction'))
			{
				if (Hash::check(Input::get('password_old'), $user->password))
				{
					$this->user->update($id, array('password' => Input::get('password')));

					return Redirect::route('admin.user.edit', array($user->id))
						->with('message', 'Your password was successfully changed.')
						->with('messageStatus', 'success');
				}
				else
				{
					return Redirect::route('admin.user.edit', array($user->id))
						->with('message', "Your password was wrong. Please try again.")
						->with('messageStatus', 'danger');
				}
			}
			else
			{
				$this->user->update($id, Input::all());
			}

			// Fire the user updated event
			Event::fire('user.updated', array($user, Input::all()));

			return Redirect::route('admin.user.edit', array($user->id))
				->with('message', 'User was successfully updated.')
				->with('messageStatus', 'success');
		}
		else
		{
			$this->unauthorized();

			return View::make('pages.admin.error')
				->withError("You do not have permission to edit this user!");
		}
	}

	public function destroy($id)
	{
		if ($this->currentUser->isStaff() and $this->currentUser->access() > 2)
		{
			// Delete the user
			$user = $this->user->delete($id);

			// Fire the user deleted event
			Event::fire('user.deleted', array($user));

			return Redirect::route('admin.user.index')
				->with('message', "User was successfully deleted.")
				->with('messageStatus', 'success');
		}
		else
		{
			$this->unauthorized();

			return View::make('pages.admin.error')
				->withError("You do not have permission to delete users!");
		}
	}

}