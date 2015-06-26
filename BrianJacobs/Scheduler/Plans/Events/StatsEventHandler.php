<?php namespace Plans\Events;

use Date;

class StatsEventHandler {

	public function onCreate($stat)
	{
		if ($stat->type != 'message')
		{
			app('NotificationRepository')->create([
				'user_id'	=> $stat->goal->plan->user->id,
				'type'		=> 'plan',
				'category'	=> 'stats',
				'action'	=> 'create',
				'content'	=> "{$stat->present()->header} added to the \"{$stat->goal->title}\" goal.",
			]);

			// Check and see if we should be auto-completing the goal
			$this->checkGoalCompletionCriteria($stat);

			// If they placed in the top 3 of the tournament, show a special badge
			$this->checkTournamentResultStanding($stat);
		}
	}

	public function onDelete($stat)
	{
		if ($stat->type != 'message')
		{
			app('NotificationRepository')->create([
				'user_id'	=> $stat->goal->plan->user->id,
				'type'		=> 'plan',
				'category'	=> 'stats',
				'action'	=> 'delete',
				'content'	=> "{$stat->present()->header} removed from the \"{$stat->goal->title}\" goal.",
			]);

			// Remove each message for the stat
			$stat->message->each(function($m)
			{
				$m->delete();
			});
		}
	}

	public function onUpdate($stat)
	{
		if ($stat->type != 'message')
		{
			app('NotificationRepository')->create([
				'user_id'	=> $stat->goal->plan->user->id,
				'type'		=> 'plan',
				'category'	=> 'stats',
				'action'	=> 'update',
				'content'	=> "{$stat->present()->header} updated on the \"{$stat->goal->title}\" goal.",
			]);

			// Check and see if we should be auto-completing the goal
			$this->checkGoalCompletionCriteria($stat);

			// Update their special badge if they update their standing in the tournament
			$this->updateTournamentResultStanding($stat);
		}
	}

	protected function checkGoalCompletionCriteria($stat)
	{
		if ($stat->goal->completion)
		{
			// Grab the completion object
			$completion = $stat->goal->completion;

			// Start with zero completed criteria
			$completedCount = 0;

			// Loop through all the stats so we can check everything
			foreach ($stat->goal->stats as $s)
			{
				// Figure out how things should be calculated
				switch ($completion->operator)
				{
					case '=':
						if ($s->{$completion->metric} == $completion->value)
						{
							$completedCount++;
						}
					break;

					case '>':
						if ($s->{$completion->metric} > $completion->value)
						{
							$completedCount++;
						}
					break;

					case '<':
						if ($s->{$completion->metric} < $completion->value)
						{
							$completedCount++;
						}
					break;

					case '<=':
						if ($s->{$completion->metric} <= $completion->value)
						{
							$completedCount++;
						}
					break;

					case '>=':
						if ($s->{$completion->metric} >= $completion->value)
						{
							$completedCount++;
						}
					break;

					case '!=':
						if ($s->{$completion->metric} != $completion->value)
						{
							$completedCount++;
						}
					break;
				}
			}

			// If we've hit our completed criteria count, complete the goal
			if ($completedCount >= $completion->count)
			{
				//sleep(1);
				
				app('GoalRepository')->update($stat->goal_id, [
					'completed' => (int) true,
					'completed_date' => Date::now(),
				]);
			}
		}
	}

	protected function checkTournamentResultStanding($stat)
	{
		if ($stat->type == "tournament" and $stat->place <= 3)
		{
			switch ($stat->place)
			{
				case 1:
					$placeNice = "first";
				break;

				case 2:
					$placeNice = "second";
				break;

				case 3:
					$placeNice = "third";
				break;
			}

			//sleep(1);

			app('StatRepository')->create([
				'type'		=> 'message',
				'goal_id'	=> $stat->goal_id,
				'stat_id'	=> $stat->id,
				'icon'		=> "place{$stat->place}",
				'notes'		=> "Congratulations on your {$placeNice} place finish at the {$stat->tournament}!",
			]);
		}
	}

	protected function updateTournamentResultStanding($stat)
	{
		if ($stat->type == "tournament")
		{
			switch ($stat->place)
			{
				case 1:
					$placeNice = "first";
				break;

				case 2:
					$placeNice = "second";
				break;

				case 3:
					$placeNice = "third";
				break;
			}

			if ($stat->place > 3 and $stat->message->count() > 0)
			{
				$stat->message->each(function($m)
				{
					$m->delete();
				});
			}

			if ($stat->place <=3 and $stat->message->count() > 0)
			{
				app('StatRepository')->update($stat->message->first()->id, [
					'icon'	=> "place{$stat->place}",
					'notes'	=> "Congratulations on your {$placeNice} place finish at the {$stat->tournament}!",
				]);
			}

			if ($stat->place <=3 and $stat->message->count() == 0)
			{
				//sleep(1);

				app('StatRepository')->create([
					'type'		=> 'message',
					'goal_id'	=> $stat->goal_id,
					'stat_id'	=> $stat->id,
					'icon'		=> "place{$stat->place}",
					'notes'		=> "Congratulations on your {$placeNice} place finish at the {$stat->tournament}!",
				]);
			}
		}
	}

}
