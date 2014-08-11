<?php namespace Scheduler\Data\Repositories\Eloquent;

use Str,
	Date,
	CreditModel,
	CreditRepositoryInterface;

class CreditRepository implements CreditRepositoryInterface {

	protected $resultsPerPage = 25;

	public function all()
	{
		return CreditModel::with('user')->get();
	}

	public function allPaginated()
	{
		return CreditModel::with('user')->paginate($this->resultsPerPage);
	}

	public function create(array $data)
	{
		return CreditModel::create($data);
	}

	public function delete($id)
	{
		// Get the item
		$item = $this->find($id);

		if ($item)
		{
			// Delete the credit
			$item->delete();

			return $item;
		}

		return false;
	}

	public function find($id)
	{
		return CreditModel::find($id);
	}

	public function findByCode($code)
	{
		return CreditModel::where('code', $code)->first();
	}

	public function findByDate($field, Date $date)
	{
		return CreditModel::where($field, $date)->get();
	}

	public function findByEmail($email)
	{
		return CreditModel::where('email', $email)->get();
	}

	public function removeClaimed()
	{
		// Get all the items
		$items = CreditModel::whereRaw('value = claimed')->get();

		if ($items->count() > 0)
		{
			foreach ($items as $item)
			{
				$item->delete();
			}
		}
	}

	public function removeExpired(Date $date, $exact = false)
	{
		// Figure out the operator sign
		$sign = ($exact) ? '=' : '<=';

		// Get the items
		$items = CreditModel::where('expires', $sign, $date)->get();

		if ($items->count() > 0)
		{
			foreach ($items as $item)
			{
				$item->delete();
			}
		}
	}

	public function search($term)
	{
		return CreditModel::join('users', 'users_credits.user_id', '=', 'users.id')
			->where(function($query) use ($term)
			{
				$query->where('users.name', 'like', "%{$term}%")
					->orWhere('users.email', 'like', "%{$term}%");
			})->orWhere(function($query) use ($term)
			{
				$query->where('users_credits.code', 'like', "%{$term}%")
					->orWhere('users_credits.email', 'like', "%{$term}%");
			})->paginate($this->resultsPerPage);
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