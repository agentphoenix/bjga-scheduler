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

	public function update($id, array $data)
	{
		// Get the comment
		$comment = $this->getById($id);

		if ($comment)
		{
			$comment->fill($data)->save();

			return $comment;
		}

		return false;
	}

}
