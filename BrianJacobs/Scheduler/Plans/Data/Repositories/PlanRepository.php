<?php namespace Plans\Data\Repositories;

use Plan as Model,
	UserModel as User,
	PlanRepositoryInterface;
use Scheduler\Data\Repositories\BaseRepository;

class PlanRepository extends BaseRepository implements PlanRepositoryInterface {

	protected $model;

	public function __construct(Model $model)
	{
		$this->model = $model;
	}

	public function create(array $data)
	{
		return $this->model->create($data);
	}

	public function getUserPlanTimeline(User $user)
	{
		$plan = $user->plan->load('goals', 'conversations', 'conversations.user', 'goals.conversations', 'goals.conversations.user', 'goals.conversations.goal', 'goals.stats', 'goals.stats.goal');

		$timeline = [];

		// Loop through the goals
		foreach ($plan->goals as $goal)
		{
			// Use "updated_at" for goals so that when a goal is marked
			// as complete it jumps up in the list
			$timestamp = $goal->updated_at->format('U');

			// Store the goal
			$timeline[$timestamp] = $goal;

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
		}

		if ($plan->conversations->count() > 0)
		{
			foreach ($plan->conversations as $conversation)
			{
				$timestamp = $conversation->created_at->format('U');

				$timeline[$timestamp] = $conversation;
			}
		}

		krsort($timeline);

		return $timeline;
	}

}
