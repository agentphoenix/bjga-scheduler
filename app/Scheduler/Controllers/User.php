<?php namespace Scheduler\Controllers;

use Hash;
use Mail;
use Input;
use Redirect;
use UserValidator;
use UserRepositoryInterface;

class User extends Base {

	public function __construct(UserRepositoryInterface $user)
	{
		parent::__construct();

		$this->user = $user;
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
			$this->_view = 'admin.users.index';

			$this->_data->users = $this->user->all();
		}
		else
		{
			$this->unauthorized();

			$this->_view = 'admin.error';

			$this->_data->error = "You do not have permission to manage users!";
		}
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		if ($this->currentUser->isStaff() and $this->currentUser->access() > 1)
		{
			$this->_view = 'admin.users.create';
		}
		else
		{
			$this->unauthorized();

			$this->_view = 'admin.error';

			$this->_data->error = "You do not have permission to create users!";
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
			$validator = new UserValidator;

			if ( ! $validator->passes())
			{
				return Redirect::back()
					->withInput()
					->withErrors($validator->getErrors())
					->with('message', 'User could not be created because of errors. Please correct and try again.')
					->with('messageStatus', 'danger');
			}

			$user = $this->user->create(Input::all());

			$emailData = array(
				'name'		=> $user->name,
				'email'		=> $user->email,
				'password'	=> Input::get('password'),
				'site'		=> $this->request->root(),
			);

			// Send an email to the user who was just created
			Mail::send('emails.users.created', $emailData, function($msg) use($user)
			{
				$msg->to($user->email)->subject("Welcome to Brian Jacobs Golf!");
			});

			return Redirect::route('admin.user.index')
				->with('message', 'User was successfully created.')
				->with('messageStatus', 'success');
		}
		else
		{
			$this->unauthorized();

			$this->_view = 'admin.error';

			$this->_data->error = "You do not have permission to create users!";
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
		// Get the user
		$user = $this->user->find($id);

		if (($this->currentUser->isStaff() and $this->currentUser->access() > 1) 
				or ($this->currentUser->id == $user->id))
		{
			$this->_view = 'admin.users.edit';

			$this->_data->user = $user;
		}
		else
		{
			$this->unauthorized();

			$this->_view = 'admin.error';

			$this->_data->error = "You do not have permission to edit this user!";
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

			return Redirect::route('admin.user.edit', array($user->id))
				->with('message', 'User was successfully updated.')
				->with('messageStatus', 'success');
		}
		else
		{
			$this->unauthorized();

			$this->_view = 'admin.error';

			$this->_data->error = "You do not have permission to edit this user!";
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
		if ($this->currentUser->isStaff() and $this->currentUser->access() > 1)
		{
			$this->user->delete($id);

			return Redirect::route('admin.user.index')
				->with('message', "User was successfully deleted.")
				->with('messageStatus', 'success');
		}
		else
		{
			$this->unauthorized();

			$this->_view = 'admin.error';

			$this->_data->error = "You do not have permission to delete users!";
		}
	}

}