<?php namespace Scheduler\Commands;

use App;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption,
	Symfony\Component\Console\Input\InputArgument;

class NewStaffServicesCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'scheduler:services';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Build the base services for new staff members';

	protected $staff;
	protected $services;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

		$this->staff = App::make('StaffRepository');
		$this->services = App::make('ServiceRepository');
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		// The services to duplicate
		$services = [2, 4, 5, 6, 7];

		// Get the staff argument
		$staff = $this->staff->find((int) $this->argument('staff'));

		// Get the staff member's last name
		$name = explode(' ', $staff->user->name);

		// Get the last name
		$lastname = strtolower(end($name));

		// Get the pricing structure
		$pricing = $this->pricing($this->argument('level'));

		foreach ($services as $service)
		{
			// Get the original service
			$original = $this->services->find($service);

			$this->services->create([
				'staff_id'				=> $staff->id,
				'category'				=> $original->category,
				'order'					=> $original->order,
				'status'				=> $original->status,
				'name'					=> $original->name,
				'slug'					=> $original->slug."-{$lastname}",
				'description'			=> $original->description,
				'price'					=> $pricing[$service],
				'occurrences'			=> $original->occurrences,
				'occurrences_schedule'	=> $original->occurrences_schedule,
				'duration'				=> $original->duration,
				'user_limit'			=> $original->user_limit,
				'loyalty'				=> $original->loyalty,
			]);

			$this->info($original->name." service created!");
		}
	}

	protected function getArguments()
	{
		return array(
			array('staff', InputArgument::REQUIRED, 'The staff member ID'),
			array('level', InputArgument::REQUIRED, 'The staff member level'),
		);
	}

	protected function pricing($level)
	{
		$prices = [
			'director' => [
				2 => 125.00,
				4 => 75.00,
				5 => 70.00,
				6 => 65.00,
				7 => 50.00,
			],
			'senior' => [
				2 => 100.00,
				4 => 70.00,
				5 => 65.00,
				6 => 60.00,
				7 => 50.00,
			],
			'associate' => [
				2 => 75.00,
				4 => 65.00,
				5 => 60.00,
				6 => 55.00,
				7 => 50.00,
			],
			'staff' => [
				2 => 50.00,
				4 => 60.00,
				5 => 55.00,
				6 => 50.00,
				7 => 50.00,
			],
		];

		return $prices[$level];
	}

}