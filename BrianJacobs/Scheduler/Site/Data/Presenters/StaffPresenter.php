<?php namespace Scheduler\Data\Presenters;

use Date;
use Laracasts\Presenter\Presenter;

class StaffPresenter extends Presenter {

	public function niceAvailability($day)
	{
		$daySchedule = $this->entity->schedule->filter(function($s) use ($day)
		{
			return (int) $s->day === (int) $day;
		})->first();

		if ( ! empty($daySchedule->availability))
		{
			// Break the availability up
			list($start, $end) = explode('-', $daySchedule->availability);

			// Build some date objects
			$start = Date::createFromFormat('G:i', $start);
			$end = Date::createFromFormat('G:i', $end);

			return '<p>'.$start->format('g:i A')." - ".$end->format('g:i A').'</p>';
		}

		return '<p class="text-info"><strong>No availability</strong></p>';
	}

}
