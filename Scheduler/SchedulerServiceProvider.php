<?php namespace Scheduler;

use App,
	Auth,
	Mail,
	View,
	Event,
	Queue,
	Config;
use Drewm\MailChimp;
use dflydev\markdown\MarkdownParser;
use Illuminate\Support\ServiceProvider;
use Scheduler\Services\BookingService,
	Scheduler\Services\MarkdownService;

class SchedulerServiceProvider extends ServiceProvider {

	public function register()
	{
		$this->setupMarkdown();
		$this->setupMailchimp();
	}

	public function boot()
	{
		$this->setupBindings();
		$this->setupEventListeners();
		$this->setupBooking();
	}

	protected function setupBooking()
	{
		$this->app['scheduler.booking'] = $this->app->share(function($app)
		{
			return new BookingService(
				$app->make('ServiceRepository'),
				$app->make('UserRepository')
			);
		});
	}

	protected function setupMarkdown()
	{
		$this->app['markdown'] = $this->app->share(function($app)
		{
			return new MarkdownService(new MarkdownParser);
		});
	}

	public function setupMailchimp()
	{
		$this->app['scheduler.mailchimp'] = $this->app->share(function($app)
		{
			return new MailChimp('f04794d1de4fc62cf6ec66f764edc967-us3');
		});
	}

	protected function setupBindings()
	{
		// Get the aliases from the app config
		$a = Config::get('app.aliases');

		// Bind the repositories to any calls to their interfaces
		App::bind($a['ServiceRepositoryInterface'], $a['ServiceRepository']);
		App::bind($a['StaffRepositoryInterface'], $a['StaffRepository']);
		App::bind($a['StaffAppointmentRepositoryInterface'], $a['StaffAppointmentRepository']);
		App::bind($a['StaffScheduleRepositoryInterface'], $a['StaffScheduleRepository']);
		App::bind($a['UserRepositoryInterface'], $a['UserRepository']);

		// Make sure we some variables available on all views
		View::share('_currentUser', Auth::user());
		View::share('_icons', Config::get('icons'));
	}

	protected function setupEventListeners()
	{
		Event::listen('book.block.created', 'Scheduler\Events\BookingEventHandler@createBlock');
		Event::listen('book.lesson.created', 'Scheduler\Events\BookingEventHandler@createLesson');
		Event::listen('book.program.created', 'Scheduler\Events\BookingEventHandler@createProgram');
		Event::listen('book.lesson.cancelledUser', 'Scheduler\Events\BookingEventHandler@userCancelledLesson');
		Event::listen('book.program.cancelledUser', 'Scheduler\Events\BookingEventHandler@userCancelledProgram');
		Event::listen('book.instructorCancelled', 'Scheduler\Events\BookingEventHandler@instructorCancelled');

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

		Queue::failing(function($job, $data)
		{
			$emailData = array('job' => $job, 'data' => $data);

			Mail::queue('emails.system.failedQueueJob', $emailData, function($message)
			{
				$message->to('david.vanscott@gmail.com')
					->subject('[Brian Jacobs Golf - Scheduler] Queue Job Failed');
			});
		});
	}

}