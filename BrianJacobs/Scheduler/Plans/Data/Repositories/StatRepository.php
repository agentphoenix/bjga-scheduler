<?php namespace Plans\Data\Repositories;

use Stat as Model,
	StatRepositoryInterface;
use Scheduler\Data\Repositories\BaseRepository;

class StatRepository extends BaseRepository implements StatRepositoryInterface {

	protected $model;

	public function __construct(Model $model)
	{
		$this->model = $model;
	}

	public function create(array $data)
	{
		return $this->model->create($data);
	}

	public function delete($id)
	{
		// Get the comment
		$comment = $this->getById($id);

		if ($comment)
		{
			$comment->delete();

			return $comment;
		}

		return false;
	}

}
