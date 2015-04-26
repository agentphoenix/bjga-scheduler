<?php namespace Plans\Data\Repositories;

use Goal as Model,
	UserModel as User,
	GoalRepositoryInterface;
use Scheduler\Data\Repositories\BaseRepository;

class GoalRepository extends BaseRepository implements GoalRepositoryInterface {

	protected $model;

	public function __construct(Model $model)
	{
		$this->model = $model;
	}

	public function create(array $data)
	{
		return $this->model->create($data);
	}

	public function getUserGoalTimeline(User $user, $goalId)
	{
		$goal = $user->plan->goals->filter(function($g) use ($goalId)
		{
			return $g->id == $goalId;
		})->first();

		$timeline = [];

		if ($goal)
		{
			$goal = $goal->load('conversations', 'conversations.user', 'stats', 'plan', 'plan.user');

			// Goal conversations
			if ($goal->conversations->count() > 0)
			{
				foreach ($goal->conversations as $comment)
				{
					$timestamp = $comment->created_at->format('U');

					$timeline[$timestamp] = $comment;
				}
			}

			// Goal stats
			if ($goal->stats->count() > 0)
			{
				foreach ($goal->stats as $stat)
				{
					$timestamp = $stat->created_at->format('U');

					$timeline[$timestamp] = $stat;
				}
			}

			krsort($timeline);
		}

		return $timeline;
	}

}
