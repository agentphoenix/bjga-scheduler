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
		$viewObj = View::make("partials.{$view}");

		// Make sure we have data before attaching it
		if ($data !== false)
			return $viewObj->with($data);

		return $viewObj;
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
		return View::make('partials.common.modal')
			->with('modalId', (array_key_exists('id', $data)) ? $data['id'] : false)
			->with('modalHeader', (array_key_exists('header', $data)) ? $data['header'] : false)
			->with('modalBody', (array_key_exists('body', $data)) ? $data['body'] : false)
			->with('modalFooter', (array_key_exists('footer', $data)) ? $data['footer'] : false);
	}
}

if ( ! function_exists('alert'))
{
	function alert($level, $message)
	{
		return View::make('partials.common.alert')
			->withClass($level)
			->withContent($message);
	}
}