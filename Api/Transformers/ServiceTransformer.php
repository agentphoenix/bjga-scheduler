<?php namespace Scheduler\Api\Transformers;

use Config;
use ServiceModel;
use League\Fractal\TransformerAbstract;

class ServiceTransformer extends TransformerAbstract {

	protected $availableEmbeds = array(
		'schedule'
	);

	public function transform(ServiceModel $service)
	{
		// Get the appointments
		$appts = $service->appointments->sortBy(function($s)
		{
			return $s->start;
		});

		return array(
			'name'			=> $service->name,
			'slug'			=> $service->slug,
			'description'	=> $service->description,
			'category'		=> ucfirst($service->category),
			'duration'		=> (int) $service->duration,
			'price'			=> strip_tags($service->present()->price),
			'user_limit'	=> (int) $service->user_limit,
			'staffName'		=> $service->present()->staffName,
			'staffEmail'	=> $service->present()->staffEmail,
			'occurrences'	=> (int) $service->occurrences,
			'loyalty'		=> (bool) $service->loyalty,
			'active'		=> (bool) $service->status,
			'start'			=> ($service->isProgram()) ? $appts->first()->start->format(Config::get('bjga.dates.dateNoDay').', '.Config::get('bjga.dates.time')) : false,
			'end'			=> ($service->isProgram()) ? $appts->last()->end->format(Config::get('bjga.dates.dateNoDay').', '.Config::get('bjga.dates.time')) : false,
		);
	}

	public function embedSchedule()
	{
		$schedule = $service->staffAppointments;

		return $this->collection($schedule, new AppointmentTransformer);
	}

}