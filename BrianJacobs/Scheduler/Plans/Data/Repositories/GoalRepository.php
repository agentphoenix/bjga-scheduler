<?php namespace Plans\Data\Repositories;

use Plan,
	Goal as Model,
	GoalCompletion,
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
		$goal = $this->model->create($data);

		if (array_key_exists('completion_option', $data))
		{
			// Create a new completion record
			$completion = GoalCompletion::create($data['completion']);

			// Link the completion to the goal we just created
			$goal->completion()->save($completion);
		}

		return $goal;
	}

	public function delete($id)
	{
		// Get the goal
		$goal = $this->getById($id, ['comments', 'stats']);

		if ($goal)
		{
			$goal->comments->each(function($g)
			{
				$g->delete();
			});

			$goal->stats->each(function($s)
			{
				$s->delete();
			});

			if ($goal->completion)
			{
				$goal->completion->delete();
			}

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
		$finalTimeline = [];

		if ($goal)
		{
			$goal = $goal->load('comments', 'comments.user', 'comments.user.staff', 'comments.goal', 'stats', 'stats.goal', 'lessons', 'lessons.service', 'lessons.location', 'lessons.goal', 'plan', 'plan.user');

			// Goal comments
			if ($goal->comments->count() > 0)
			{
				foreach ($goal->comments as $comment)
				{
					$timestamp = $comment->created_at->format('U');

					$timeline[$timestamp][] = $comment;
				}
			}

			// Goal stats
			if ($goal->stats->count() > 0)
			{
				foreach ($goal->stats as $stat)
				{
					$timestamp = $stat->created_at->format('U');

					$timeline[$timestamp][] = $stat;
				}
			}

			// Lessons
			if ($goal->lessons->count() > 0)
			{
				foreach ($goal->lessons as $lesson)
				{
					$timestamp = $lesson->start->format('U');

					$timeline[$timestamp][] = $lesson;
				}
			}

			krsort($timeline);

			foreach ($timeline as $timeArr)
			{
				foreach ($timeArr as $item)
				{
					$finalTimeline[] = $item;
				}
			}
		}

		return $finalTimeline;
	}

	public function update($id, array $data)
	{
		// Get the goal
		$goal = $this->getById($id);

		if ($goal)
		{
			$goal->fill($data)->save();

			if ($goal->completion)
			{
				if (array_key_exists('completion_option', $data))
				{
					// Update the completion record
					$goal->completion->fill($data['completion'])->save();
				}
				else
				{
					$goal->completion->delete();
				}
			}
			else
			{
				if (array_key_exists('completion_option', $data))
				{
					// Create a new completion record
					$completion = GoalCompletion::create($data['completion']);

					// Link the completion to the goal we just created
					$goal->completion()->save($completion);
				}
			}

			return $goal;
		}

		return false;
	}

}
