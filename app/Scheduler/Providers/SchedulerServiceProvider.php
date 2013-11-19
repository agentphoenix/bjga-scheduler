<?php namespace Scheduler\Providers;

use Route;
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
	}

	protected function registerRoutes()
	{
		// Home
		Route::get('/', array('as' => 'home', 'uses' => 'Scheduler\Controllers\Home@getIndex'));
		Route::get('logout', array('as' => 'logout', 'uses' => 'Scheduler\Controllers\Home@getLogout'));
		Route::post('login', 'Scheduler\Controllers\Home@postLogin');
		
		Route::get('password/remind', 'Scheduler\Controllers\Home@getPasswordReminder');
		Route::post('password/remind', 'Scheduler\Controllers\Home@postPasswordReminder');
		Route::get('password/reset/{token}', 'Scheduler\Controllers\Home@getPasswordReset');
		Route::post('password/reset/{token}', 'Scheduler\Controllers\Home@postPasswordReset');

		// Registration
		Route::get('register', array('as' => 'register', 'uses' => 'Scheduler\Controllers\Home@getRegister'));
		Route::post('register', 'Scheduler\Controllers\Home@postRegister');

		// Events
		Route::get('events', array('as' => 'events', 'uses' => 'Scheduler\Controllers\Home@getAllEvents'));
		Route::get('event/{slug}', array('as' => 'event', 'uses' => 'Scheduler\Controllers\Home@getEvent'));
		
		// Booking
		Route::group(array('prefix' => 'book'), function()
		{
			Route::get('/', array('as' => 'book.index', 'uses' => 'Scheduler\Controllers\Booking@getIndex'));
			Route::get('lesson', array('as' => 'book.lesson',  'uses' => 'Scheduler\Controllers\Booking@getLesson'));
			Route::post('lesson', 'Scheduler\Controllers\Booking@postLesson');
		});

		// Admin
		Route::group(array('prefix' => 'admin'), function()
		{
			Route::get('/', array('as' => 'admin', 'uses' => 'Scheduler\Controllers\Admin@getIndex'));

			Route::get('service/create/oneToOne', array(
				'as'	=> 'admin.service.createOneToOne',
				'uses'	=> 'Scheduler\Controllers\Service@createOneToOne'
			));
			Route::get('service/create/oneToMany', array(
				'as'	=> 'admin.service.createOneToMany',
				'uses'	=> 'Scheduler\Controllers\Service@createOneToMany'
			));
			Route::get('service/create/manyToMany', array(
				'as'	=> 'admin.service.createManyToMany',
				'uses'	=> 'Scheduler\Controllers\Service@createManyToMany'
			));

			Route::resource('service', 'Scheduler\Controllers\Service', array('except' => array('show', 'create')));
			Route::resource('user', 'Scheduler\Controllers\User', array('except' => array('show')));
			Route::resource('staff', 'Scheduler\Controllers\Staff', array('except' => array('show')));
		});

		// Ajax requests
		Route::group(array('prefix' => 'ajax'), function()
		{
			Route::get('availability', 'Scheduler\Controllers\Ajax@getAvailability');
			Route::get('service/delete/{id}', 'Scheduler\Controllers\Ajax@deleteService');
			Route::get('staff/delete/{id}', 'Scheduler\Controllers\Ajax@deleteStaff');
			Route::get('staff/exception/{id}', 'Scheduler\Controllers\Ajax@setScheduleException');
			Route::get('user/delete/{id}', 'Scheduler\Controllers\Ajax@deleteUser');
			Route::get('user/password/{id}', 'Scheduler\Controllers\Ajax@changePassword');

			Route::post('enroll', array(
				'as' => 'ajax.enroll',
				'uses' => 'Scheduler\Controllers\Ajax@postEnroll'
			));
			Route::post('service/new', array(
				'as' => 'ajax.createService',
				'uses' => 'Scheduler\Controllers\Ajax@postNewService'
			));
			Route::post('service/edit', array(
				'as' => 'ajax.editService',
				'uses' => 'Scheduler\Controllers\Ajax@postEditService'
			));
			Route::post('withdraw', array(
				'as' => 'ajax.withdraw',
				'uses' => 'Scheduler\Controllers\Ajax@postWithdraw'
			));
		});
	}

}