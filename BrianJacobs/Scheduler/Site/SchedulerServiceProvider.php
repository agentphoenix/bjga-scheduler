<?php namespace Scheduler;

use App,
	Auth,
	Mail,
	View,
	Event,
	Queue,
	Config;
use Ikimea\Browser\Browser;
use Illuminate\Support\ServiceProvider;
use League\CommonMark\CommonMarkConverter;

class SchedulerServiceProvider extends ServiceProvider {

	protected $aliases;

	public function register()
	{
		$this->setupMarkdown();
		//$this->setupBombBomb();
		$this->setupBrowser();
		$this->setupMacros();
		$this->setupFlashNotifier();
		$this->setupAvailability();
		$this->setRepositoryBindings();
	}

	public function boot()
	{
		$this->browserCheck();
		$this->setupBindings();
		$this->setupEventListeners();
		$this->setupBooking();
	}

	protected function setupBooking()
	{
		$this->app['scheduler.booking'] = $this->app->share(function($app)
		{
			return new Services\BookingService(
				$app->make('ServiceRepository'),
				$app->make('UserRepository'),
				$app->make('StaffAppointmentRepository')
			);
		});
	}

	protected function setupMarkdown()
	{
		$this->app['markdown'] = $this->app->share(function($app)
		{
			return new Services\MarkdownService(new CommonMarkConverter);
		});
	}

	protected function setupBombBomb()
	{
		$this->app['scheduler.bombbomb'] = $this->app->share(function($app)
		{
			return new Services\BombBombService;
		});
	}

	protected function setupBrowser()
	{
		$this->app['scheduler.browser'] = $this->app->share(function($app)
		{
			return new Browser;
		});
	}

	protected function setupBindings()
	{
		// Get the aliases from the app config
		$a = Config::get('app.aliases');

		// Make sure we some variables available on all views
		View::share('_currentUser', Auth::user());
		View::share('_icons', Config::get('icons'));
	}

	protected function setupEventListeners()
	{
		Event::listen('book.block.created', 'Scheduler\Events\BookingEventHandler@createBlock');
		Event::listen('book.lesson.created', 'Scheduler\Events\BookingEventHandler@createLesson');
		Event::listen('book.program.created', 'Scheduler\Events\BookingEventHandler@createProgram');
		Event::listen('book.cancel.student', 'Scheduler\Events\BookingEventHandler@studentCancelled');
		Event::listen('book.cancel.instructor', 'Scheduler\Events\BookingEventHandler@instructorCancelled');

		Event::listen('service.created', 'Scheduler\Events\ServiceEventHandler@onCreated');
		Event::listen('service.deleted', 'Scheduler\Events\ServiceEventHandler@onDeleted');
		Event::listen('service.updated', 'Scheduler\Events\ServiceEventHandler@onUpdated');

		Event::listen('staff.created', 'Scheduler\Events\UserEventHandler@onStaffCreated');
		Event::listen('staff.deleted', 'Scheduler\Events\UserEventHandler@onStaffDeleted');
		Event::listen('staff.updated', 'Scheduler\Events\UserEventHandler@onStaffUpdated');

		Event::listen('user.created', 'Scheduler\Events\UserEventHandler@onUserCreated');
		Event::listen('user.deleted', 'Scheduler\Events\UserEventHandler@onUserDeleted');
		Event::listen('user.password.reminder', 'Scheduler\Events\UserEventHandler@onUserPasswordReminder');
		Event::listen('user.password.reset', 'Scheduler\Events\UserEventHandler@onUserPasswordReset');
		Event::listen('user.registered', 'Scheduler\Events\UserEventHandler@onUserRegistered');
		Event::listen('user.updated', 'Scheduler\Events\UserEventHandler@onUserUpdated');

		Event::listen('appointment.created', 'Scheduler\Events\AppointmentEventHandler@onCreated');
		Event::listen('appointment.location', 'Scheduler\Events\AppointmentEventHandler@onLocationChange');
		Event::listen('appointment.updated', 'Scheduler\Events\AppointmentEventHandler@onUpdated');

		Event::listen('credit.created', 'Scheduler\Events\CreditEventHandler@onCreate');
		Event::listen('credit.deleted', 'Scheduler\Events\CreditEventHandler@onDelete');
		Event::listen('credit.updated', 'Scheduler\Events\CreditEventHandler@onUpdate');

		Event::listen('location.created', 'Scheduler\Events\LocationEventHandler@onCreate');
		Event::listen('location.deleted', 'Scheduler\Events\LocationEventHandler@onDelete');
		Event::listen('location.updated', 'Scheduler\Events\LocationEventHandler@onUpdate');

		/**
		 * If a queue item fails, send an email.
		 */
		Queue::failing(function($job, $data)
		{
			// Set the data to be used in the email
			$emailData = array('job' => $job, 'data' => $data);

			// Send the email
			Mail::send('emails.failedQueueJob', $emailData, function($message)
			{
				$message->to(Config::get('bjga.email.adminAddress'))
					->subject(Config::get('bjga.email.subject').' Scheduler Queue Job Failed');
			});
		});
	}

	protected function browserCheck()
	{
		$this->app->before(function($request)
		{
			// Get the browser object
			$browser = App::make('scheduler.browser');

			$supported = array(
				Browser::BROWSER_IE			=> 9,
				Browser::BROWSER_CHROME		=> 26,
				Browser::BROWSER_FIREFOX	=> 20,
			);

			if (array_key_exists($browser->getBrowser(), $supported) 
					and $browser->getVersion() < $supported[$browser->getBrowser()])
			{
				header("Location: browser.php");
				die();
			}
		});
	}

	protected function setupMacros()
	{
		\Str::macro('creditCode', function($length)
		{
			$pool = '123456789abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ';

			return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
		});
	}

	protected function setupFlashNotifier()
	{
		$this->app['scheduler.flash'] = $this->app->share(function($app)
		{
			return new Services\FlashNotifierService($app['session.store']);
		});
	}

	protected function setupAvailability()
	{
		$this->app['scheduler.availability'] = $this->app->share(function($app)
		{
			return new Services\AvailabilityService;
		});
	}

	protected function setRepositoryBindings()
	{
		// Grab the aliases from the config
		$this->aliases = $this->app['config']['app.aliases'];

		// Set the items being bound
		$bindings = ['Credit', 'Service', 'Staff', 'StaffAppointment', 'StaffSchedule',
			'User', 'Location', 'Notification'];

		foreach ($bindings as $binding)
		{
			$this->bindRepository($binding);
		}
	}

	private function bindRepository($item)
	{
		// Set the concrete and abstract names
		$abstract = "{$item}RepositoryInterface";
		$concrete = "{$item}Repository";

		// Bind to the container
		$this->app->bind(
			[$abstract => $this->aliases[$abstract]], 
			$this->aliases[$concrete]
		);
	}

}