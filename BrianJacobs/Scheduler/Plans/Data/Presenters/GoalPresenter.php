<?php namespace Plans\Data\Presenters;

use Str, Config, Markdown;
use Laracasts\Presenter\Presenter;

class GoalPresenter extends Presenter {

	public function completedDate()
	{
		return $this->entity->completed_date->format(Config::get('bjga.dates.dateNoDay'));
	}

	public function completionCriteria()
	{
		// Get the criteria object
		$criteria = $this->entity->completion;

		switch ($criteria->type)
		{
			case 'trackman':
				$type = 'TrackMan Combine '.Str::plural('result', $criteria->count);
			break;

			case 'practice':
				$type = 'practice '.Str::plural('session', $criteria->count);
			break;

			default:
				$type = Str::plural($criteria->type, $criteria->count);
			break;
		}

		$haveString = ($criteria->count == 1) ? 'has' : 'have';

		return "{$criteria->count} {$type} {$haveString} been recorded with {$this->formatCriteria($criteria)}";
	}

	public function created()
	{
		return $this->entity->created_at->format(Config::get('bjga.dates.dateNoDay'));
	}

	public function summary()
	{
		return Markdown::parse($this->entity->summary);
	}

	protected function formatCriteria($criteria)
	{
		switch ($criteria->metric)
		{
			case 'score':
				return $this->formatCriteriaScore($criteria);
			break;

			case 'place':
				return $this->formatCriteriaTournamentPlace($criteria);
			break;

			case 'minutes':
				return $this->formatCriteriaPracticeMinutes($criteria);
			break;

			default:
				return $this->formatCriteriaGeneral($criteria);
			break;
		}
	}

	protected function formatCriteriaGeneral($criteria)
	{
		switch ($criteria->metric)
		{
			case 'fir':
				$type = Str::plural('fairway', $criteria->value)." in regulation";
			break;

			case 'gir':
				$type = Str::plural('green', $criteria->value)." in regulation";
			break;

			case 'putts':
				$type = Str::plural('putt', $criteria->value);
			break;

			case 'penalties':
				$type = Str::plural('penalty', $criteria->value);
			break;

			case 'minutes':
				$type = Str::plural('minute', $criteria->value);
			break;

			case 'balls':
				$type = "practice ".Str::plural('ball', $criteria->value);
			break;

			case 'holes':
				$type = Str::plural('hole', $criteria->value);
			break;

			default:
				$type = $criteria->metric;
			break;
		}

		if ($criteria->operator == "=") return "{$criteria->value} {$type}";

		if ($criteria->operator == "!=") return "a score that isn't {$criteria->value}";

		if ($criteria->operator == ">") return "more than {$criteria->value} {$type}";

		if ($criteria->operator == ">=") return "at least {$criteria->value} {$type}";

		if ($criteria->operator == "<") return "fewer than {$criteria->value} {$type}";

		if ($criteria->operator == "<=") return "no more than {$criteria->value} {$type}";
	}

	protected function formatCriteriaScore($criteria)
	{
		if ($criteria->operator == "=") return "a score of {$criteria->value}";

		if ($criteria->operator == "!=") return "a score that isn't {$criteria->value}";

		if ($criteria->operator == ">") return "a score higher than {$criteria->value}";

		if ($criteria->operator == ">=") return "a score higher than or equal to {$criteria->value}";

		if ($criteria->operator == "<") return "a score lower than {$criteria->value}";

		if ($criteria->operator == "<=") return "a score lower than or equal to {$criteria->value}";
	}

	protected function formatCriteriaTournamentPlace($criteria)
	{
		if ($criteria->operator == "=") return "a final standing of ".ordinal($criteria->value)." place";

		if ($criteria->operator == "!=") return "a final standing that isn't ".ordinal($criteria->value)." place";

		if ($criteria->operator == ">") return "a final standing better than ".ordinal($criteria->value)." place";

		if ($criteria->operator == ">=") return "a final standing of at least ".ordinal($criteria->value)." place";

		if ($criteria->operator == "<") return "a final standing lower than ".ordinal($criteria->value)." place";

		if ($criteria->operator == "<=") return "a final standing no higher than ".ordinal($criteria->value)." place";
	}

	protected function formatCriteriaPracticeMinutes($criteria)
	{
		if ($criteria->operator == "=") return "{$criteria->value} minutes of practice time";

		if ($criteria->operator == "!=") return "{$criteria->value} minutes of practice time";

		if ($criteria->operator == ">") return "more than {$criteria->value} minutes of practice time";

		if ($criteria->operator == ">=") return "at least {$criteria->value} minutes of practice time";

		if ($criteria->operator == "<") return "less than {$criteria->value} minutes of practice time";

		if ($criteria->operator == "<=") return "no more than {$criteria->value} minutes of practice time";
	}

}
