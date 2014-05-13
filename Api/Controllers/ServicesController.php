<?php namespace Scheduler\Api\Controllers;

use ServiceModel;

class ServicesController extends ApiController {

	public function index()
	{
		return ServiceModel::first();
		return ServiceModel::all();
	}

	public function showByCategory($category)
	{
		$services = ServiceModel::getCategory($category)
			->orderBy('order', 'asc')
			->get();

		if ($services->count() == 0)
		{
			return $this->errorNotFound('No services found');
		}

		return $services;
	}

	public function showByName($name)
	{
		$services = ServiceModel::where('slug', 'like', "%{$name}%")
			->orderBy('order', 'asc')
			->get();

		if ($services->count() == 0)
		{
			return $this->errorNotFound('No services found');
		}

		return $services;
	}

}