<?php namespace Scheduler\Api\Controllers;

use ServiceModel;
use Scheduler\Api\Transformers\ServiceTransformer;

class ServicesController extends ApiController {

	public function index()
	{
		$services = ServiceModel::all();

		return $this->respondWithCollection($services, new ServiceTransformer);
	}

	public function showByCategory($category)
	{
		$services = ServiceModel::getCategory($category)
			->orderBy('order', 'asc')
			->get();

		if ( ! $services)
		{
			return $this->errorNotFound('No services found');
		}

		if ($services->count() == 1)
		{
			return $this->respondWithItem($services->first(), new ServiceTransformer);
		}

		return $this->respondWithCollection($services, new ServiceTransformer);
	}

	public function showByName($name)
	{
		$services = ServiceModel::where('slug', 'like', "%{$name}%")
			->orderBy('order', 'asc')
			->get();

		if ( ! $services)
		{
			return $this->errorNotFound('No service found');
		}

		if ($services->count() == 1)
		{
			return $this->respondWithItem($services->first(), new ServiceTransformer);
		}

		return $this->respondWithCollection($services, new ServiceTransformer);
	}

}