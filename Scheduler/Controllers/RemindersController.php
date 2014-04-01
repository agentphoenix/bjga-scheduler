<?php namespace Scheduler\Controllers;

use App,
	Hash,
	Lang,
	View,
	Input,
	Password,
	Redirect,
	Controller;

class RemindersController extends Controller {

	/**
	 * Display the password reminder view.
	 *
	 * @return Response
	 */
	public function getRemind()
	{
		return View::make('pages.password.remind');
	}

	/**
	 * Handle a POST request to remind a user of their password.
	 *
	 * @return Response
	 */
	public function postRemind()
	{
		$response = Password::remind(Input::only('email'), function($msg)
		{
			$msg->subject("Your Password Reset Link");
		});

		switch ($response)
		{
			case Password::INVALID_USER:
				return Redirect::back()
					->with('message', Lang::get($response))
					->with('messageStatus', 'danger');
			break;

			case Password::REMINDER_SENT:
				return Redirect::back()
					->with('message', Lang::get($response))
					->with('messageStatus', 'success');
			break;
		}
	}

	/**
	 * Display the password reset view for the given token.
	 *
	 * @param  string  $token
	 * @return Response
	 */
	public function getReset($token = null)
	{
		if (is_null($token)) App::abort(404);

		return View::make('pages.password.reset')->with('token', $token);
	}

	/**
	 * Handle a POST request to reset a user's password.
	 *
	 * @return Response
	 */
	public function postReset()
	{
		$credentials = Input::only(
			'email', 'password', 'password_confirmation', 'token'
		);

		$response = Password::reset($credentials, function($user, $password)
		{
			//$user->password = Hash::make($password);
			$user->password = $password;

			$user->save();
		});

		switch ($response)
		{
			case Password::INVALID_PASSWORD:
			case Password::INVALID_TOKEN:
			case Password::INVALID_USER:
				return Redirect::back()
					->with('message', Lang::get($response))
					->with('messageStatus', 'danger');
			break;

			case Password::PASSWORD_RESET:
				return Redirect::route('home')
					->with('message', "Password has been reset!")
					->with('messageStatus', 'success');
			break;
		}
	}

}