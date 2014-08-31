<?php namespace Scheduler\Commands;

use Queue;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class UpdateCalendarCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'scheduler:calendar';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Update the calendar for a specific staff member';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		Queue::push('Scheduler\Services\CalendarService', array('staff' => $this->argument('staff')));

		$this->info('Updating the staff member calendar...');
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('staff', InputArgument::REQUIRED, 'The staff member ID'),
		);
	}

}
