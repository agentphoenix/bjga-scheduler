<?php namespace Scheduler;

use App,
	Auth,
	Mail,
	View,
	Event,
	Queue,
	Route,
	Config,
	StaffAppointmentModel;
use dflydev\markdown\MarkdownParser;
use Illuminate\Support\ServiceProvider;
use Scheduler\Services\BookingService,
	Scheduler\Services\MarkdownService;

class SchedulerServiceProvider extends ServiceProvider {

	public function register()
	{
		$this->setupMarkdown();
		$this->setupBooking();
	}

	public function boot()
	{
		$this->setupBindings();
		$this->setupRoutes();
		$this->setupPushQueueRoutes();
		$this->setupEventListeners();
	}

	protected function setupBooking()
	{
		$this->app['scheduler.booking'] = $this->app->share(function($app)
		{
			return new BookingService;
		});
	}

	protected function setupMarkdown()
	{
		$this->app['markdown'] = $this->app->share(function($app)
		{
			return new MarkdownService(new MarkdownParser);
		});
	}

	protected function setupBindings()
	{
		// Get the aliases from the app config
		$a = $this->app['config']->get('app.aliases');

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

	protected function setupRoutes()
	{
		Route::get('/', array(
			'as'	=> 'home',
			'uses'	=> 'Scheduler\Controllers\HomeController@index'));
		Route::get('logout', array(
			'as'	=> 'logout',
			'uses'	=> 'Scheduler\Controllers\HomeController@getLogout'));
		Route::get('register', array(
			'as'	=> 'register',
			'uses'	=> 'Scheduler\Controllers\HomeController@getRegister'));
		Route::post('login', 'Scheduler\Controllers\HomeController@postLogin');
		Route::post('register', 'Scheduler\Controllers\HomeController@postRegister');
		Route::controller('password', 'Scheduler\Controllers\RemindersController');
		Route::controller('book', 'Scheduler\Controllers\BookingController', array(
			'getLesson'		=> 'book.lesson',
			'postLesson'	=> 'book.lesson.store',
			'getProgram'	=> 'book.program',
			'postProgram'	=> 'book.program.store',
		));

		// Events
		Route::get('events', array(
			'as'	=> 'events',
			'uses'	=> 'Scheduler\Controllers\HomeController@getAllEvents'));
		Route::get('event/{slug}', array(
			'as'	=> 'event',
			'uses'	=> 'Scheduler\Controllers\HomeController@getEvent'));

		// Admin
		Route::group(array('prefix' => 'admin'), function()
		{
			Route::get('/', array(
				'as'	=> 'admin',
				'uses'	=> 'Scheduler\Controllers\AdminController@index'));

			Route::get('service/create/lesson', array(
				'as'	=> 'admin.service.createLesson',
				'uses'	=> 'Scheduler\Controllers\ServiceController@createLessonService'));
			Route::get('service/create/program', array(
				'as'	=> 'admin.service.createProgram',
				'uses'	=> 'Scheduler\Controllers\ServiceController@createProgramService'));

			Route::resource('service', 'Scheduler\Controllers\ServiceController', array(
				'except' => array('show', 'create')));
			Route::resource('user', 'Scheduler\Controllers\UserController', array(
				'except' => array('show')));

			Route::delete('staff/destroyExcpetion/{id}', array(
				'as'	=> 'admin.staff.destroyException',
				'uses'	=> 'Scheduler\Controllers\Staff@destroyException'));
			Route::resource('staff', 'Scheduler\Controllers\StaffController', array(
				'except' => array('show')));
		});

		// Ajax requests
		Route::group(array('prefix' => 'ajax'), function()
		{
			Route::get('availability', 'Scheduler\Controllers\AjaxController@getAvailability');
			Route::get('service/delete/{id}', 'Scheduler\Controllers\AjaxController@deleteService');
			Route::get('staff/delete/{id}', 'Scheduler\Controllers\AjaxController@deleteStaff');
			Route::get('staff/exception/{id}', 'Scheduler\Controllers\AjaxController@setScheduleException');
			Route::get('staff/delete_exception/{id}', 'Scheduler\Controllers\AjaxController@deleteScheduleException');
			Route::get('user/delete/{id}', 'Scheduler\Controllers\AjaxController@deleteUser');
			Route::get('user/password/{id}', 'Scheduler\Controllers\AjaxController@changePassword');
			Route::get('service/get', array(
				'as'	=> 'ajax.getService',
				'uses'	=> 'Scheduler\Controllers\AjaxController@getService'));

			Route::post('enroll', array(
				'as' => 'ajax.enroll',
				'uses' => 'Scheduler\Controllers\AjaxController@postEnroll'));
			Route::post('service/new', array(
				'as' => 'ajax.createService',
				'uses' => 'Scheduler\Controllers\AjaxController@postNewService'));
			Route::post('service/edit', array(
				'as' => 'ajax.editService',
				'uses' => 'Scheduler\Controllers\AjaxController@postEditService'));
			Route::post('withdraw', array(
				'as' => 'ajax.withdraw',
				'uses' => 'Scheduler\Controllers\AjaxController@postWithdraw'));
		});
	}

	protected function setupPushQueueRoutes()
	{
		Route::post('queue/writeCalendar', function()
		{
			Queue::marshal();
		});

		Route::post('queue/sendEmail', function()
		{
			Queue::marshal();
		});
	}

	public function setupEventListeners()
	{
		Event::listen('book.create.lesson', 'Scheduler\Events\BookingEventHandler@createLesson');
		Event::listen('book.create.program', 'Scheduler\Events\BookingEventHandler@createProgram');

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