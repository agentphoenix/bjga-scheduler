<?php namespace Plans\Events;

use App, Mail, Config;

class StatsEventHandler {

	public function onCreate($stat)
	{
		if ($stat->type != 'message')
		{
			App::make('NotificationRepository')->create([
				'user_id'	=> $stat->goal->plan->user->id,
				'type'		=> 'plan',
				'category'	=> 'stats',
				'action'	=> 'create',
				'content'	=> "{$stat->present()->header} added to the \"{$stat->goal->title}\" goal.",
			]);
		}

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

			sleep(1);

			App::make('StatRepository')->create([
				'type'		=> 'message',
				'goal_id'	=> $stat->goal_id,
				'stat_id'	=> $stat->id,
				'icon'		=> "place{$stat->place}",
				'notes'		=> "Congratulations on your {$placeNice} place finish at the {$stat->tournament}!",
			]);
		}
	}

	public function onDelete($stat)
	{
		if ($stat->type != 'message')
		{
			App::make('NotificationRepository')->create([
				'user_id'	=> $stat->goal->plan->user->id,
				'type'		=> 'plan',
				'category'	=> 'stats',
				'action'	=> 'delete',
				'content'	=> "{$stat->present()->header} removed from the \"{$stat->goal->title}\" goal.",
			]);

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
			App::make('NotificationRepository')->create([
				'user_id'	=> $stat->goal->plan->user->id,
				'type'		=> 'plan',
				'category'	=> 'stats',
				'action'	=> 'update',
				'content'	=> "{$stat->present()->header} updated on the \"{$stat->goal->title}\" goal.",
			]);

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
					App::make('StatRepository')->update($stat->message->first()->id, [
						'icon'	=> "place{$stat->place}",
						'notes'	=> "Congratulations on your {$placeNice} place finish at the {$stat->tournament}!",
					]);
				}

				if ($stat->place <=3 and $stat->message->count() == 0)
				{
					sleep(1);

					App::make('StatRepository')->create([
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

}
