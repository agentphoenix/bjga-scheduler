<?php namespace Scheduler\Data\Repositories\Eloquent;

use LocationModel,
	LocationRepositoryInterface;

class LocationRepository implements LocationRepositoryInterface {

	public function all()
	{
		return LocationModel::get();
	}

	public function create(array $data)
	{
		return LocationModel::create($data);
	}

	public function delete($id)
	{
		// Get the item
		$item = $this->find($id);

		if ($item)
		{
			// Delete the location
			$item->delete();

			return $item;
		}

		return false;
	}

	public function find($id)
	{
		return LocationModel::find($id);
	}

	public function update($id, array $data)
	{
		// Get the item
		$item = $this->find($id);

		if ($item)
		{
			// Fill and save the item
			$item->fill($data)->save();

			return $item;
		}

		return false;
	}
	
}