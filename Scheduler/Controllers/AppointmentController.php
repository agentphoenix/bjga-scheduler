<?php namespace Scheduler\Controllers;

use View,
	Event,
	Input,
	Redirect,
	ServiceValidator,
	ServiceRepositoryInterface;

class AppointmentController extends BaseController {

	protected $service;

	public function __construct(ServiceRepositoryInterface $service)
	{
		parent::__construct();

		$this->service = $service;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	public function show($id)
	{
		// Get the service
		$service = $this->service->find($id);

		// Get the attendees
		$attendees = $this->service->getAttendees($id);

		return partial('common/modal_content', array(
			'modalHeader'	=> "Attendees",
			'modalBody'		=> View::make('pages.admin.appointments.show')
								->withService($service)
								->withAttendees($attendees),
			'modalFooter'	=> false,
		));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}