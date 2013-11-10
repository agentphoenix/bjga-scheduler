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
		{
			return $viewObj->with($data);
		}

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
		// Set the variables
		$id		= (array_key_exists('id', $data)) ? $data['id'] : false;
		$header	= (array_key_exists('header', $data)) ? $data['header'] : false;
		$body	= (array_key_exists('body', $data)) ? $data['body'] : false;
		$footer	= (array_key_exists('footer', $data)) ? $data['footer'] : false;

		return View::make('partials.common.modal')
			->with('modalId', $id)
			->with('modalHeader', $header)
			->with('modalBody', $body)
			->with('modalFooter', $footer);
	}
}