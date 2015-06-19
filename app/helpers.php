<?php

/**
 * Generate a partial.
 *
 * @param	string	The view name
 * @param	mixed	The data to pass to the partial
 * @return	string
 */
if ( ! function_exists('partial'))
{
	function partial($view, $data = false)
	{
		$viewObj = view("partials.{$view}");

		// Make sure we have data before attaching it
		if ($data !== false)
			return $viewObj->with($data)->render();

		return $viewObj->render();
	}
}

/**
 * Generate a modal.
 *
 * @param	array	The data to pass to the modal
 * @return	string
 */
if ( ! function_exists('modal'))
{
	function modal(array $data = array())
	{
		return view('partials.common.modal')
			->with('modalId', (array_key_exists('id', $data)) ? $data['id'] : false)
			->with('modalHeader', (array_key_exists('header', $data)) ? $data['header'] : false)
			->with('modalBody', (array_key_exists('body', $data)) ? $data['body'] : false)
			->with('modalFooter', (array_key_exists('footer', $data)) ? $data['footer'] : false)
			->render();
	}
}

if ( ! function_exists('alert'))
{
	function alert($level, $message)
	{
		return view('partials.common.alert')
			->withClass($level)
			->withContent($message)
			->render();
	}
}

if ( ! function_exists('view'))
{
	function view($view = null, $data = [], $mergeData = [])
	{
		$factory = app('view');

		if (func_num_args() === 0) {
			return $factory;
		}

		return $factory->make($view, $data, $mergeData);
	}
}

if ( ! function_exists('redirect'))
{
	function redirect($to = null, $status = 302, $headers = [], $secure = null)
	{
		if (is_null($to))
		{
			return app('redirect');
		}

		return app('redirect')->to($to, $status, $headers, $secure);
	}
}

if ( ! function_exists('event'))
{
	function event($event, $payload = [], $halt = false)
	{
		return app('events')->fire($event, $payload, $halt);
	}
}
