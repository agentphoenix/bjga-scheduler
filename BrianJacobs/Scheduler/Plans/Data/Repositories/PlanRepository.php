<?php namespace Plans\Data\Repositories;

use Plan as Model,
	UserModel as User,
	StaffModel as Staff,
	PlanRepositoryInterface;
use Scheduler\Data\Repositories\BaseRepository;

class PlanRepository extends BaseRepository implements PlanRepositoryInterface {

	protected $model;

	public function __construct(Model $model)
	{
		$this->model = $model;
	}

	public function addInstructor($planId, $instructorId)
	{
		// Get the plan
		$plan = $this->getById($planId);

		if ($plan)
		{
			// Attach the instructor
			$plan->instructors()->attach($plan->id, ['staff_id' => $instructorId]);

			return $plan;
		}

		return false;
	}

	public function create(array $data, Staff $instructor)
	{
		// Create the plan
		$plan = $this->model->create($data);

		// Associate the instructor with the plan
		$plan->instructors()->attach($plan->id, ['staff_id' => $instructor->id]);

		return $plan;
	}

	public function delete($id)
	{
		// Get the plan
		$plan = $this->getById($id, ['goals', 'goals.comments', 'goals.stats']);

		if ($plan)
		{
			// Make sure we have a copy of the plan
			$item = $plan;

			if ($plan->goals->count() > 0)
			{
				foreach ($plan->goals as $goal)
				{
					$goal->comments->each(function($g)
					{
						$g->delete();
					});

					$goal->stats->each(function($s)
					{
						$s->delete();
					});

					// Remove the goal
					$goal->delete();
				}
			}

			// Remove all the instructors
			$plan->instructors()->detach();

			// Remove the plan
			$plan->delete();

			return $item;
		}

		return false;
	}

	public function getActiveGoals(Model $plan)
	{
		return $plan->activeGoals;
	}

	public function getInstructorPlans(Staff $instructor)
	{
		return $instructor->plans->load('user', 'activeGoals', 'instructors', 'instructors.user');
	}

	public function getUserPlanTimeline(Model $plan)
	{
		$plan = $plan->load('goals', 'goals.comments', 'goals.comments.user', 'goals.comments.goal', 'goals.stats', 'goals.stats.goal', 'goals.lessons', 'goals.lessons.service');

		$timeline = [];

		// Loop through the goals
		foreach ($plan->goals as $goal)
		{
			// Use "updated_at" for goals so that when a goal is marked
			// as complete it jumps up in the list
			$timestamp = $goal->created_at->format('U');

			// Store the goal
			$timeline[$timestamp] = $goal;

			// Goal comments
			if ($goal->comments->count() > 0)
			{
				foreach ($goal->comments as $comment)
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

			// Goal lessons
			if ($goal->lessons->count() > 0)
			{
				foreach ($goal->lessons as $lesson)
				{
					$timestamp = $lesson->start->format('U');

					$timeline[$timestamp] = $lesson;
				}
			}
		}

		krsort($timeline);

		return $timeline;
	}

	public function removeInstructor($planId, $instructorId)
	{
		// Get the plan
		$plan = $this->getById($planId);

		if ($plan)
		{
			// Detach the instructor
			$plan->instructors()->detach([$plan->id => ['staff_id' => $instructorId]]);

			return $plan;
		}

		return false;
	}

}
