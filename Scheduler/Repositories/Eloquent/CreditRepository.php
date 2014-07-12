<?php namespace Scheduler\Repositories\Eloquent;

use CreditModel,
	CreditRepositoryInterface;

class CreditRepository implements CreditRepositoryInterface {

	public function all()
	{
		return CreditModel::all();
	}

	public function create(array $data)
	{
		return CreditModel::create($data);
	}

	public function delete($id)
	{
		return CreditModel::destroy($id);
	}

	public function find($id)
	{
		return CreditModel::find($id);
	}

	public function findByCode($code)
	{
		return CreditModel::where('code', $code)->first();
	}

	public function update($id, array $data)
	{
		$item = $this->find($id);
		$item->fill($data);
		$item->save();

		return $item;
	}
	
}