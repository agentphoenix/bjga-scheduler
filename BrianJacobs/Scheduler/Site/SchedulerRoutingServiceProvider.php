<?php namespace Scheduler;

use Queue, Route;
use Illuminate\Support\ServiceProvider;

class SchedulerRoutingServiceProvider extends ServiceProvider {

	public function register()
	{
		//
	}

	public function boot()
	{
		$this->routes();
		$this->bookingRoutes();
		$this->ajaxRoutes();
		$this->adminRoutes();
		$this->pushQueueRoutes();
	}

	protected function bookingRoutes()
	{
		Route::get('book/lesson', array(
			'as'	=> 'book.lesson',
			'uses'	=> 'Scheduler\Controllers\BookingController@lesson'));
		Route::get('book/program', array(
			'as'	=> 'book.program',
			'uses'	=> 'Scheduler\Controllers\BookingController@program'));
		Route::get('book/lesson/total/user/{userId}/service/{serviceId}', [
			'as'	=> 'book.lesson.total',
			'uses'	=> 'Scheduler\Controllers\BookingController@calculatePrice']);

		Route::post('book/lesson', array(
			'as'	=> 'book.lesson.store',
			'uses'	=> 'Scheduler\Controllers\BookingController@storeLesson'));
		Route::post('book/program', array(
			'as'	=> 'book.program.store',
			'uses'	=> 'Scheduler\Controllers\BookingController@storeProgram'));
		Route::post('book/cancel', array(
			'as'	=> 'book.cancel',
			'uses'	=> 'Scheduler\Controllers\BookingController@cancel'));
		Route::post('book/withdraw', array(
			'as'	=> 'book.withdraw',
			'uses'	=> 'Scheduler\Controllers\BookingController@withdraw'));
		Route::post('book/enroll', array(
			'as'	=> 'book.enroll',
			'uses'	=> 'Scheduler\Controllers\BookingController@enroll'));
	}

	protected function routes()
	{
		Route::get('/', array(
			'as'	=> 'home',
			'uses'	=> 'Scheduler\Controllers\HomeController@mySchedule'));
		Route::get('/days/{days?}', array(
			'as'	=> 'homeDays',
			'uses'	=> 'Scheduler\Controllers\HomeController@mySchedule'));
		Route::get('logout', array(
			'as'	=> 'logout',
			'uses'	=> 'Scheduler\Controllers\HomeController@getLogout'));
		Route::get('register', array(
			'as'	=> 'register',
			'uses'	=> 'Scheduler\Controllers\HomeController@register'));
		Route::post('login', 'Scheduler\Controllers\HomeController@postLogin');
		Route::post('register', 'Scheduler\Controllers\HomeController@doRegistration');
		Route::controller('password', 'Scheduler\Controllers\RemindersController');

		// Events
		Route::get('events', array(
			'as'	=> 'events',
			'uses'	=> 'Scheduler\Controllers\HomeController@events'));
		Route::get('event/{slug?}', array(
			'as'	=> 'event',
			'uses'	=> 'Scheduler\Controllers\HomeController@getEvent'));

		// Report a problem
		Route::post('report', array(
			'as'	=> 'report',
			'uses'	=> 'Scheduler\Controllers\HomeController@report'));

		Route::post('apply-credit', [
			'as'	=> 'credit.apply',
			'uses'	=> 'Scheduler\Controllers\HomeController@applyCredit']);

		Route::get('my-history', [
			'as'	=> 'history',
			'uses'	=> 'Scheduler\Controllers\HomeController@studentHistory']);
	}

	protected function adminRoutes()
	{
		Route::group(array('prefix' => 'admin'), function()
		{
			/**
			 * Lesson and program service creation routes.
			 *
			 * service/create/lesson	GET		Create lesson page
			 * service/create/program	GET		Enroll in program page
			 */
			Route::get('service/create/lesson', array(
				'as'	=> 'admin.service.createLesson',
				'uses'	=> 'Scheduler\Controllers\ServiceController@createLessonService'));
			Route::get('service/create/program', array(
				'as'	=> 'admin.service.createProgram',
				'uses'	=> 'Scheduler\Controllers\ServiceController@createProgramService'));

			/**
			 * Staff schedule blocking routes.
			 *
			 * staff/block			GET		Get all blocks for a user
			 * staff/block/create	GET		Creation form for a new block
			 * staff/block			POST	Create new block
			 * staff/block/{id}		DELETE	Remove a block
			 */
			Route::post('staff/schedule/block', array(
				'as'	=> 'admin.staff.block.store',
				'uses'	=> 'Scheduler\Controllers\StaffController@storeBlock'));
			Route::delete('staff/schedule/block/{id}', array(
				'as'	=> 'admin.staff.block.destroy',
				'uses'	=> 'Scheduler\Controllers\StaffController@destroyBlock'));
			Route::get('staff/schedule/block/create', array(
				'as'	=> 'admin.staff.block.create',
				'uses'	=> 'Scheduler\Controllers\StaffController@createBlock'));
			Route::get("staff/schedule/block/delete/{id}", array(
				'as'	=> 'admin.staff.block.delete',
				'uses'	=> 'Scheduler\Controllers\StaffController@deleteBlock'));
			Route::get('staff/schedule/edit/staff/{staffId}/day/{day}', 'Scheduler\Controllers\StaffController@editSchedule');
			Route::get('staff/schedule/{id}', array(
				'as'	=> 'admin.staff.schedule',
				'uses'	=> 'Scheduler\Controllers\StaffController@schedule'));
			Route::put('staff/schedule/edit/{id}', array(
				'as'	=> 'admin.staff.schedule.update',
				'uses'	=> 'Scheduler\Controllers\StaffController@updateSchedule'));

			Route::get('reports', array(
				'as'	=> 'admin.reports.index',
				'uses'	=> 'Scheduler\Controllers\ReportController@index'));
			Route::get('reports/unpaid', array(
				'as'	=> 'admin.reports.unpaid',
				'uses'	=> 'Scheduler\Controllers\ReportController@unpaid'));
			Route::get('reports/monthly/{date?}', array(
				'as'	=> 'admin.reports.monthly',
				'uses'	=> 'Scheduler\Controllers\ReportController@monthly'));
			Route::post('reports/monthly', array(
				'as'	=> 'admin.reports.monthly.update',
				'uses'	=> 'Scheduler\Controllers\ReportController@updateMonthly'));
			Route::get('reports/credits', array(
				'as'	=> 'admin.reports.credits',
				'uses'	=> 'Scheduler\Controllers\ReportController@credits'));

			Route::get('appointment/attendees/{type}/{id}', array(
				'as'	=> 'admin.appointment.attendees',
				'uses'	=> 'Scheduler\Controllers\AppointmentController@attendees'));
			Route::post('appointment/removeAttendee', array(
				'as'	=> 'admin.appointment.removeAttendee',
				'uses'	=> 'Scheduler\Controllers\AppointmentController@removeAttendee'));
			Route::get('appointment/user/{id?}', array(
				'as'	=> 'admin.appointment.user',
				'uses'	=> 'Scheduler\Controllers\AppointmentController@user'));
			Route::get('appointment/history/user/{id}', array(
				'as'	=> 'admin.appointment.history',
				'uses'	=> 'Scheduler\Controllers\AppointmentController@history'));
			Route::get('appointment/recurring', array(
				'as'	=> 'admin.appointment.recurring.index',
				'uses'	=> 'Scheduler\Controllers\AppointmentController@recurring'));
			Route::get('appointment/recurring/{id}', array(
				'as'	=> 'admin.appointment.recurring.edit',
				'uses'	=> 'Scheduler\Controllers\AppointmentController@editRecurring'));
			Route::post('appointment/recurring/{id}', array(
				'as'	=> 'admin.appointment.recurring.update',
				'uses'	=> 'Scheduler\Controllers\AppointmentController@updateRecurring'));
			Route::get('appointment/details/{id}', [
				'as'	=> 'appointment.details',
				'uses'	=> 'Scheduler\Controllers\AppointmentController@details']);

			Route::get('credits/delete/{id}', 'Scheduler\Controllers\CreditsController@delete');
			Route::post('credits/search', [
				'as'	=> 'admin.credits.search',
				'uses'	=> 'Scheduler\Controllers\CreditsController@doSearch']);

			/**
			 * Resourceful controllers.
			 *
			 * service
			 * user
			 * staff
			 * appointment
			 * credits
			 */
			Route::resource('service', 'Scheduler\Controllers\ServiceController', array(
				'except' => array('show', 'create')));
			Route::resource('user', 'Scheduler\Controllers\UserController', array(
				'except' => array('show')));
			Route::resource('staff', 'Scheduler\Controllers\StaffController', array(
				'except' => array('show')));
			Route::resource('appointment', 'Scheduler\Controllers\AppointmentController', array(
				'except' => array('show', 'destroy')));
			Route::resource('credits', 'Scheduler\Controllers\CreditsController', [
				'except' => ['show']]);
		});
	}

	protected function ajaxRoutes()
	{
		Route::group(array('prefix' => 'ajax'), function()
		{
			Route::get('availability', 'Scheduler\Controllers\AjaxController@getAvailability');
			Route::get('service/delete/{id}', 'Scheduler\Controllers\AjaxController@deleteService');
			Route::get('staff/delete/{id}', 'Scheduler\Controllers\AjaxController@deleteStaff');
			Route::get('user/delete/{id}', 'Scheduler\Controllers\AjaxController@deleteUser');
			Route::get('user/password/{id}', 'Scheduler\Controllers\AjaxController@changePassword');
			Route::get('service/get', array(
				'as'	=> 'ajax.getService',
				'uses'	=> 'Scheduler\Controllers\AjaxController@getService'));
			Route::get('service/getLesson', array(
				'as'	=> 'ajax.getLessonService',
				'uses'	=> 'Scheduler\Controllers\AjaxController@getLessonDetails'));
			Route::get('service/getProgram', array(
				'as'	=> 'ajax.getProgramService',
				'uses'	=> 'Scheduler\Controllers\AjaxController@getProgramDetails'));
			Route::post('appointment/paid', array(
				'as'	=> 'ajax.markAsPaid',
				'uses'	=> 'Scheduler\Controllers\AjaxController@markAsPaid'));

			Route::post('enroll', array(
				'as' => 'ajax.enroll',
				'uses' => 'Scheduler\Controllers\AjaxController@postEnroll'));
			Route::post('service/new', array(
				'as' => 'ajax.createService',
				'uses' => 'Scheduler\Controllers\AjaxController@postNewService'));
			Route::post('service/edit', array(
				'as' => 'ajax.editService',
				'uses' => 'Scheduler\Controllers\AjaxController@postEditService'));
			Route::post('service/removeScheduleItem', array(
				'as' => 'ajax.removeServiceScheduleItem',
				'uses' => 'Scheduler\Controllers\AjaxController@postRemoveServiceScheduleItem'));
			Route::post('withdraw', array(
				'as' => 'ajax.withdraw',
				'uses' => 'Scheduler\Controllers\AjaxController@postWithdraw'));
			Route::post('service/reorder', array(
				'as' => 'ajax.reorderService',
				'uses' => 'Scheduler\Controllers\AjaxController@updateServiceOrder'));
			
			Route::get('user/email/service/{serviceId}/appt/{apptId}', 'Scheduler\Controllers\AjaxController@sendEmailFromService');
			Route::get('user/email/user/{userId}', 'Scheduler\Controllers\AjaxController@sendEmailFromUser');
			Route::get('user/email/unpaid/{userId}', 'Scheduler\Controllers\AjaxController@sendEmailFromUnpaid');
			Route::get('user/email/instructor/appt/{apptId}', 'Scheduler\Controllers\AjaxController@sendEmailToInstructor');
			Route::post('user/email', array(
				'as'	=> 'ajax.emailUser',
				'uses'	=> 'Scheduler\Controllers\AjaxController@sendEmail'));

			Route::get('cancel/{type}/{id}', 'Scheduler\Controllers\AjaxController@cancelModal');
		});
	}

	protected function pushQueueRoutes()
	{
		Route::post('queue/receive', function()
		{
			return Queue::marshal();
		});
	}

}