<?php namespace Scheduler\Providers;

use Auth,
	View,
	Event,
	Queue,
	Route,
	Config,
	Appointment;
use dflydev\markdown\MarkdownParser;
use Illuminate\Support\ServiceProvider;

class SchedulerServiceProvider extends ServiceProvider {

	public function register()
	{
		//$this->registerMarkdown();
	}

	public function boot()
	{
		$this->setupClassBindings();
		$this->registerRoutes();
		$this->registerPushQueueRoutes();
		//$this->registerModelEvents();
		$this->registerEventListeners();
	}

	/**
	 * The Markdown class provides for parsing Markdown content to HTML.
	 */
	protected function registerMarkdown()
	{
		$this->app['markdown'] = $this->app->share(function($app)
		{
			return new Markdown(new MarkdownParser);
		});
	}

	protected function setupClassBindings()
	{
		// Get the aliases from the app config
		$a = $this->app['config']->get('app.aliases');

		// Bind the repositories to any calls to their interfaces
		$this->app->bind($a['AppointmentRepositoryInterface'], $a['AppointmentRepository']);
		$this->app->bind($a['CategoryRepositoryInterface'], $a['CategoryRepository']);
		$this->app->bind($a['ScheduleRepositoryInterface'], $a['ScheduleRepository']);
		$this->app->bind($a['ServiceRepositoryInterface'], $a['ServiceRepository']);
		$this->app->bind($a['StaffRepositoryInterface'], $a['StaffRepository']);
		$this->app->bind($a['UserRepositoryInterface'], $a['UserRepository']);

		View::share('_currentUser', Auth::user());
		View::share('_icons', Config::get('icons'));
	}

	protected function registerRoutes()
	{
		// Home
		Route::get('/', array(
			'as'	=> 'home',
			'uses'	=> 'Scheduler\Controllers\HomeController@index'));
		Route::get('logout', array(
			'as'	=> 'logout',
			'uses'	=> 'Scheduler\Controllers\HomeController@getLogout'));
		Route::post('login', 'Scheduler\Controllers\HomeController@postLogin');
		
		Route::get('password/remind', 'Scheduler\Controllers\HomeController@getPasswordReminder');
		Route::post('password/remind', 'Scheduler\Controllers\HomeController@postPasswordReminder');
		Route::get('password/reset/{token}', 'Scheduler\Controllers\HomeController@getPasswordReset');
		Route::post('password/reset/{token}', 'Scheduler\Controllers\HomeController@postPasswordReset');

		// Registration
		Route::get('register', array(
			'as'	=> 'register',
			'uses'	=> 'Scheduler\Controllers\HomeController@getRegister'));
		Route::post('register', 'Scheduler\Controllers\HomeController@postRegister');

		// Events
		Route::get('events', array(
			'as'	=> 'events',
			'uses'	=> 'Scheduler\Controllers\HomeController@getAllEvents'));
		Route::get('event/{slug}', array(
			'as'	=> 'event',
			'uses'	=> 'Scheduler\Controllers\HomeController@getEvent'));
		
		// Booking
		Route::group(array('prefix' => 'book'), function()
		{
			Route::get('/', array(
				'as'	=> 'book.index',
				'uses'	=> 'Scheduler\Controllers\Booking@getIndex'));
			
			Route::get('lesson', array(
				'as'	=> 'book.lesson',
				'uses' => 'Scheduler\Controllers\Booking@getLesson'));
			Route::post('lesson', 'Scheduler\Controllers\Booking@postLesson');

			Route::get('program', array(
				'as'	=> 'book.program',
				'uses' => 'Scheduler\Controllers\Booking@getProgram'));
			Route::post('program', 'Scheduler\Controllers\Booking@postProgram');
		});

		// Admin
		Route::group(array('prefix' => 'admin'), function()
		{
			Route::get('/', array(
				'as'	=> 'admin',
				'uses'	=> 'Scheduler\Controllers\AdminController@index'));

			Route::get('service/create/oneToOne', array(
				'as'	=> 'admin.service.createOneToOne',
				'uses'	=> 'Scheduler\Controllers\Service@createOneToOne'));
			Route::get('service/create/oneToMany', array(
				'as'	=> 'admin.service.createOneToMany',
				'uses'	=> 'Scheduler\Controllers\Service@createOneToMany'));
			Route::get('service/create/manyToMany', array(
				'as'	=> 'admin.service.createManyToMany',
				'uses'	=> 'Scheduler\Controllers\Service@createManyToMany'));

			Route::resource('service', 'Scheduler\Controllers\Service', array(
				'except' => array('show', 'create')));
			Route::resource('user', 'Scheduler\Controllers\User', array(
				'except' => array('show')));

			Route::delete('staff/destroyExcpetion/{id}', array(
				'as'	=> 'admin.staff.destroyException',
				'uses'	=> 'Scheduler\Controllers\Staff@destroyException'));
			Route::resource('staff', 'Scheduler\Controllers\Staff', array(
				'except' => array('show')));
		});

		// Ajax requests
		Route::group(array('prefix' => 'ajax'), function()
		{
			Route::get('availability', 'Scheduler\Controllers\Ajax@getAvailability');
			Route::get('service/delete/{id}', 'Scheduler\Controllers\Ajax@deleteService');
			Route::get('staff/delete/{id}', 'Scheduler\Controllers\Ajax@deleteStaff');
			Route::get('staff/exception/{id}', 'Scheduler\Controllers\Ajax@setScheduleException');
			Route::get('staff/delete_exception/{id}', 'Scheduler\Controllers\Ajax@deleteScheduleException');
			Route::get('user/delete/{id}', 'Scheduler\Controllers\Ajax@deleteUser');
			Route::get('user/password/{id}', 'Scheduler\Controllers\Ajax@changePassword');
			Route::get('service/get', array(
				'as'	=> 'ajax.getService',
				'uses'	=> 'Scheduler\Controllers\Ajax@getService'));

			Route::post('enroll', array(
				'as' => 'ajax.enroll',
				'uses' => 'Scheduler\Controllers\Ajax@postEnroll'));
			Route::post('service/new', array(
				'as' => 'ajax.createService',
				'uses' => 'Scheduler\Controllers\Ajax@postNewService'));
			Route::post('service/edit', array(
				'as' => 'ajax.editService',
				'uses' => 'Scheduler\Controllers\Ajax@postEditService'));
			Route::post('withdraw', array(
				'as' => 'ajax.withdraw',
				'uses' => 'Scheduler\Controllers\Ajax@postWithdraw'));
		});
	}

	protected function registerPushQueueRoutes()
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

	protected function registerModelEvents()
	{
		Appointment::created(function($model)
		{
			Queue::push('Scheduler\Services\CalendarService', array('model' => $model));
		});

		Appointment::deleted(function($model)
		{
			Queue::push('Scheduler\Services\CalendarService', array('model' => $model));
		});

		Appointment::updated(function($model)
		{
			Queue::push('Scheduler\Services\CalendarService', array('model' => $model));
		});
	}

	public function registerEventListeners()
	{
		Event::listen('book.create.oneToOne', 'Scheduler\Events\BookingEventHandler@createOneToOne');
		Event::listen('book.create.oneToMany', 'Scheduler\Events\BookingEventHandler@createOneToMany');
		Event::listen('book.create.manyToMany', 'Scheduler\Events\BookingEventHandler@createManyToMany');
	}

}