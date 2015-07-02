<?php namespace Scheduler\Controllers;

use URL,
	Auth,
	Hash,
	Mail,
	Input,
	Session,
	UserValidator,
	UserRepositoryInterface;

class UserController extends BaseController {

	protected $userRepo;

	public function __construct(UserRepositoryInterface $user)
	{
		parent::__construct();

		$this->userRepo = $user;

		$this->beforeFilter(function()
		{
			if (Auth::user() === null)
			{
				// Push the intended URL into the session
				Session::put('url.intended', URL::full());

				return redirect()->route('home')
					->with('message', "You must be logged in to continue.")
					->with('messageStatus', 'danger');
			}
		});
	}

	public function index()
	{
		if ($this->currentUser->isStaff())
		{
			$users = $this->userRepo->all();

			return view('pages.admin.users.index', compact('users'));
		}
		
		return $this->unauthorized("You do not have permission to manage users.");
	}

	public function show($id)
	{
		if ($this->currentUser->isStaff())
		{
			$user = $this->userRepo->find($id);

			if ($user)
			{
				return view('pages.admin.users.show', compact('user'));
			}

			return $this->errorNotFound("User not found.");
		}

		return $this->unauthorized("You do not have permission to view user details.");
	}

	public function create()
	{
		if ($this->currentUser->isStaff() and $this->currentUser->access() > 1)
		{
			return view('pages.admin.users.create');
		}
		
		return $this->unauthorized("You do not have permission to create users.");
	}

	public function store()
	{
		if ($this->currentUser->isStaff() and $this->currentUser->access() > 1)
		{
			$validator = new UserValidator;

			if ( ! $validator->passes())
			{
				return redirect()->back()
					->withInput()
					->withErrors($validator->getErrors())
					->with('message', 'User could not be created because of errors. Please correct and try again.')
					->with('messageStatus', 'danger');
			}

			// Create the user
			$user = $this->userRepo->create(Input::all());

			// Fire the user created event
			event('user.created', [$user, Input::all()]);

			return redirect()->route('admin.user.index')
				->with('message', 'User was successfully created.')
				->with('messageStatus', 'success');
		}
		
		return $this->unauthorized("You do not have permission to create users.");
	}

	public function edit($id)
	{
		// Get the user
		$user = $this->userRepo->find($id);

		if (($this->currentUser->isStaff() and $this->currentUser->access() > 1) 
				or ($this->currentUser->id == $user->id))
		{
			return view('pages.admin.users.edit', compact('user'));
		}
		
		return $this->unauthorized("You do not have permission to edit this user.");
	}

	public function update($id)
	{
		// Get the user
		$user = $this->userRepo->find($id);

		if ($this->currentUser->isStaff() and $this->currentUser->access() > 1
			or $user->id == $this->currentUser->id)
		{
			$validator = new UserValidator;

			if ( ! $validator->passes())
			{
				return redirect()->back()
					->withInput()
					->withErrors($validator->getErrors())
					->with('message', 'User could not be updated because of errors. Please correct and try again.')
					->with('messageStatus', 'danger');
			}

			if (Input::has('formAction'))
			{
				if (Hash::check(Input::get('password_old'), $user->password))
				{
					$this->userRepo->update($id, ['password' => Input::get('password')]);

					return redirect()->route('admin.user.edit', [$user->id])
						->with('message', 'Your password was successfully changed.')
						->with('messageStatus', 'success');
				}
				
				return redirect()->route('admin.user.edit', [$user->id])
					->with('message', "Your password was wrong. Please try again.")
					->with('messageStatus', 'danger');
			}
			else
			{
				$this->userRepo->update($id, Input::all());
			}

			// Fire the user updated event
			event('user.updated', [$user, Input::all()]);

			return redirect()->route('admin.user.edit', [$user->id])
				->with('message', 'User was successfully updated.')
				->with('messageStatus', 'success');
		}
		
		return $this->unauthorized("You do not have permission to edit this user.");
	}

	public function destroy($id)
	{
		if ($this->currentUser->isStaff() and $this->currentUser->access() > 2)
		{
			// Delete the user
			$user = $this->userRepo->delete($id);

			// Fire the user deleted event
			event('user.deleted', [$user]);

			return redirect()->route('admin.user.index')
				->with('message', "User was successfully deleted.")
				->with('messageStatus', 'success');
		}
		
		return $this->unauthorized("You do not have permission to remove users.");
	}

}
