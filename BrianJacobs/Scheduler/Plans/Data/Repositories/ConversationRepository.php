<?php namespace Plans\Data\Repositories;

use Conversation as Model,
	ConversationRepositoryInterface;
use Scheduler\Data\Repositories\BaseRepository;

class ConversationRepository extends BaseRepository implements ConversationRepositoryInterface {

	protected $model;

	public function __construct(Model $model)
	{
		$this->model = $model;
	}

	public function create(array $data)
	{
		return $this->model->create($data);
	}

}
