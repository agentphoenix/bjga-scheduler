<?php namespace Plans\Data\Repositories;

use Plan,
	Goal as Model,
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

	public function delete($id)
	{
		// Get the goal
		$goal = $this->getById($id, ['conversations', 'stats']);

		if ($goal)
		{
			$goal->conversations->each(function($g)
			{
				$g->delete();
			});

			$goal->stats->each(function($s)
			{
				$s->delete();
			});

			// Remove the goal
			$goal->delete();

			return $goal;
		}

		return false;
	}

	public function getUserGoalTimeline(Plan $plan, $goalId)
	{
		$goal = $plan->goals->filter(function($g) use ($goalId)
		{
			return (int) $g->id === (int) $goalId;
		})->first();

		$timeline = [];

		if ($goal)
		{
			$goal = $goal->load('conversations', 'conversations.user', 'stats', 'lessons', 'lessons.service', 'plan', 'plan.user');

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

			// Lessons
			if ($goal->lessons->count() > 0)
			{
				foreach ($goal->lessons as $lesson)
				{
					$timestamp = $lesson->start->format('U');

					$timeline[$timestamp] = $lesson;
				}
			}

			krsort($timeline);
		}

		return $timeline;
	}

	public function update($id, array $data)
	{
		// Get the goal
		$goal = $this->getById($id);

		if ($goal)
		{
			$goal->fill($data)->save();

			return $goal;
		}

		return false;
	}

}
