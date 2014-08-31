<?php namespace Scheduler\Api\Transformers;

use Config, ServiceModel;
use League\Fractal\TransformerAbstract;

class ServiceTransformer extends TransformerAbstract {

	public function transform(ServiceModel $service)
	{
		// Get the appointments
		$appts = $service->appointments->sortBy(function($s)
		{
			return $s->start;
		});

		return [
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
			'start'			=> ($service->isProgram() and $appts->count() > 0) ? $appts->first()->start->format(Config::get('bjga.dates.dateNoDay').', '.Config::get('bjga.dates.time')) : false,
			'end'			=> ($service->isProgram() and $appts->count() > 0) ? $appts->last()->end->format(Config::get('bjga.dates.dateNoDay').', '.Config::get('bjga.dates.time')) : false,
		];
	}

}