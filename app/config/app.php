<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Application Debug Mode
	|--------------------------------------------------------------------------
	|
	| When your application is in debug mode, detailed error messages with
	| stack traces will be shown on every error that occurs within your
	| application. If disabled, a simple generic error page is shown.
	|
	*/

	'debug' => true,

	/*
	|--------------------------------------------------------------------------
	| Application URL
	|--------------------------------------------------------------------------
	|
	| This URL is used by the console to properly generate URLs when using
	| the Artisan command line tool. You should set this to the root of
	| your application so that it is used when running Artisan tasks.
	|
	*/

	'url' => 'http://localhost',

	/*
	|--------------------------------------------------------------------------
	| Application Timezone
	|--------------------------------------------------------------------------
	|
	| Here you may specify the default timezone for your application, which
	| will be used by the PHP date and date-time functions. We have gone
	| ahead and set this to a sensible default for you out of the box.
	|
	*/

	'timezone' => 'America/New_York',

	/*
	|--------------------------------------------------------------------------
	| Application Locale Configuration
	|--------------------------------------------------------------------------
	|
	| The application locale determines the default locale that will be used
	| by the translation service provider. You are free to set this value
	| to any of the locales which will be supported by the application.
	|
	*/

	'locale' => 'en',

	/*
	|--------------------------------------------------------------------------
	| Encryption Key
	|--------------------------------------------------------------------------
	|
	| This key is used by the Illuminate encrypter service and should be set
	| to a random, long string, otherwise these encrypted values will not
	| be safe. Make sure to change it before deploying any application!
	|
	*/

	'key' => $_ENV['APP_KEY'],

	'cipher' => MCRYPT_RIJNDAEL_256,

	/*
	|--------------------------------------------------------------------------
	| Autoloaded Service Providers
	|--------------------------------------------------------------------------
	|
	| The service providers listed here will be automatically loaded on the
	| request to your application. Feel free to add your own services to
	| this array to grant expanded functionality to your applications.
	|
	*/

	'providers' => array(

		'Illuminate\Foundation\Providers\ArtisanServiceProvider',
		'Illuminate\Auth\AuthServiceProvider',
		'Illuminate\Cache\CacheServiceProvider',
		'Illuminate\Session\CommandsServiceProvider',
		'Illuminate\Foundation\Providers\ConsoleSupportServiceProvider',
		'Illuminate\Routing\ControllerServiceProvider',
		'Illuminate\Cookie\CookieServiceProvider',
		'Illuminate\Database\DatabaseServiceProvider',
		'Illuminate\Encryption\EncryptionServiceProvider',
		'Illuminate\Filesystem\FilesystemServiceProvider',
		'Illuminate\Hashing\HashServiceProvider',
		'Illuminate\Html\HtmlServiceProvider',
		'Illuminate\Log\LogServiceProvider',
		'Illuminate\Mail\MailServiceProvider',
		'Illuminate\Database\MigrationServiceProvider',
		'Illuminate\Pagination\PaginationServiceProvider',
		'Illuminate\Queue\QueueServiceProvider',
		'Illuminate\Redis\RedisServiceProvider',
		'Illuminate\Remote\RemoteServiceProvider',
		'Illuminate\Auth\Reminders\ReminderServiceProvider',
		'Illuminate\Database\SeedServiceProvider',
		'Illuminate\Session\SessionServiceProvider',
		'Illuminate\Translation\TranslationServiceProvider',
		'Illuminate\Validation\ValidationServiceProvider',
		'Illuminate\View\ViewServiceProvider',
		'Illuminate\Workbench\WorkbenchServiceProvider',

		//'Dingo\Api\ApiServiceProvider',
		'Scheduler\SchedulerServiceProvider',
		'Scheduler\SchedulerRoutingServiceProvider',
		'Plans\PlanServiceProvider',
		'Plans\PlanRoutingServiceProvider',
		//'Scheduler\Api\SchedulerApiServiceProvider',

	),

	/*
	|--------------------------------------------------------------------------
	| Service Provider Manifest
	|--------------------------------------------------------------------------
	|
	| The service provider manifest is used by Laravel to lazy load service
	| providers which are not needed for each request, as well to keep a
	| list of all of the services. Here, you may set its storage spot.
	|
	*/

	'manifest' => storage_path().'/meta',

	/*
	|--------------------------------------------------------------------------
	| Class Aliases
	|--------------------------------------------------------------------------
	|
	| This array of class aliases will be registered when this application
	| is started. However, feel free to register as many as you wish as
	| the aliases are "lazy" loaded so they don't hinder performance.
	|
	*/

	'aliases' => array(

		'App'             => 'Illuminate\Support\Facades\App',
		'Artisan'         => 'Illuminate\Support\Facades\Artisan',
		'Auth'            => 'Illuminate\Support\Facades\Auth',
		'Blade'           => 'Illuminate\Support\Facades\Blade',
		'Cache'           => 'Illuminate\Support\Facades\Cache',
		'ClassLoader'     => 'Illuminate\Support\ClassLoader',
		'Config'          => 'Illuminate\Support\Facades\Config',
		'Controller'      => 'Illuminate\Routing\Controller',
		'Cookie'          => 'Illuminate\Support\Facades\Cookie',
		'Crypt'           => 'Illuminate\Support\Facades\Crypt',
		'DB'              => 'Illuminate\Support\Facades\DB',
		'Eloquent'        => 'Illuminate\Database\Eloquent\Model',
		'Event'           => 'Illuminate\Support\Facades\Event',
		'File'            => 'Illuminate\Support\Facades\File',
		'Form'            => 'Illuminate\Support\Facades\Form',
		'Hash'            => 'Illuminate\Support\Facades\Hash',
		'HTML'            => 'Illuminate\Support\Facades\HTML',
		'Input'           => 'Illuminate\Support\Facades\Input',
		'Lang'            => 'Illuminate\Support\Facades\Lang',
		'Log'             => 'Illuminate\Support\Facades\Log',
		'Mail'            => 'Illuminate\Support\Facades\Mail',
		'Paginator'       => 'Illuminate\Support\Facades\Paginator',
		'Password'        => 'Illuminate\Support\Facades\Password',
		'Queue'           => 'Illuminate\Support\Facades\Queue',
		'Redirect'        => 'Illuminate\Support\Facades\Redirect',
		'Redis'           => 'Illuminate\Support\Facades\Redis',
		'Request'         => 'Illuminate\Support\Facades\Request',
		'Response'        => 'Illuminate\Support\Facades\Response',
		'Route'           => 'Illuminate\Support\Facades\Route',
		'Schema'          => 'Illuminate\Support\Facades\Schema',
		'Seeder'          => 'Illuminate\Database\Seeder',
		'Session'         => 'Illuminate\Support\Facades\Session',
		'SSH'             => 'Illuminate\Support\Facades\SSH',
		'Str'             => 'Illuminate\Support\Str',
		'URL'             => 'Illuminate\Support\Facades\URL',
		'Validator'       => 'Illuminate\Support\Facades\Validator',
		'View'            => 'Illuminate\Support\Facades\View',

		//'API'			=> 'Dingo\Api\Facades\API',
		'Book'			=> 'Scheduler\Facades\Book',
		'Date'			=> 'Carbon\Carbon',
		'Flash'			=> 'Scheduler\Facades\FlashFacade',
		'Markdown'		=> 'Scheduler\Facades\Markdown',
		'Model'			=> 'Scheduler\Extensions\Laravel\Database\Eloquent\Model',

		/**
		 * Exceptions
		 */
		'FormValidationException'	=> 'Scheduler\Exceptions\FormValidationException',

		/**
		 * Validators
		 */
		'AppointmentValidator'	=> 'Scheduler\Validators\AppointmentValidator',
		'CreditValidator'		=> 'Scheduler\Validators\CreditValidator',
		'LocationValidator'		=> 'Scheduler\Validators\LocationValidator',
		'ServiceValidator'		=> 'Scheduler\Validators\ServiceValidator',
		'StaffValidator'		=> 'Scheduler\Validators\StaffValidator',
		'UserValidator'			=> 'Scheduler\Validators\UserValidator',

		/**
		 * Models
		 */
		'BookingMetaModel'				=> 'Scheduler\Data\Models\Eloquent\BookingMetaModel',
		'CreditModel'					=> 'Scheduler\Data\Models\Eloquent\CreditModel',
		'LocationModel'					=> 'Scheduler\Data\Models\Eloquent\LocationModel',
		'Notification'					=> 'Scheduler\Data\Models\Eloquent\Notification',
		'ServiceModel'					=> 'Scheduler\Data\Models\Eloquent\ServiceModel',
		'ServiceOccurrenceModel'		=> 'Scheduler\Data\Models\Eloquent\ServiceOccurrenceModel',
		'StaffModel'					=> 'Scheduler\Data\Models\Eloquent\StaffModel',
		'StaffAppointmentModel'			=> 'Scheduler\Data\Models\Eloquent\StaffAppointmentModel',
		'StaffAppointmentRecurModel'	=> 'Scheduler\Data\Models\Eloquent\StaffAppointmentRecurModel',
		'StaffScheduleModel'			=> 'Scheduler\Data\Models\Eloquent\StaffScheduleModel',
		'UserModel'						=> 'Scheduler\Data\Models\Eloquent\UserModel',
		'UserAppointmentModel'			=> 'Scheduler\Data\Models\Eloquent\UserAppointmentModel',

		'Comment'			=> 'Plans\Data\Comment',
		'Goal'				=> 'Plans\Data\Goal',
		'GoalCompletion'	=> 'Plans\Data\GoalCompletion',
		'Plan'				=> 'Plans\Data\Plan',
		'Stat'				=> 'Plans\Data\Stat',

		/**
		 * Repositories
		 */
		'CreditRepository'				=> 'Scheduler\Data\Repositories\Eloquent\CreditRepository',
		'LocationRepository'			=> 'Scheduler\Data\Repositories\Eloquent\LocationRepository',
		'NotificationRepository'		=> 'Scheduler\Data\Repositories\Eloquent\NotificationRepository',
		'ServiceRepository'				=> 'Scheduler\Data\Repositories\Eloquent\ServiceRepository',
		'StaffRepository'				=> 'Scheduler\Data\Repositories\Eloquent\StaffRepository',
		'StaffAppointmentRepository'	=> 'Scheduler\Data\Repositories\Eloquent\StaffAppointmentRepository',
		'StaffScheduleRepository'		=> 'Scheduler\Data\Repositories\Eloquent\StaffScheduleRepository',
		'UserRepository'				=> 'Scheduler\Data\Repositories\Eloquent\UserRepository',

		'CommentRepository'	=> 'Plans\Data\Repositories\CommentRepository',
		'GoalRepository'	=> 'Plans\Data\Repositories\GoalRepository',
		'PlanRepository'	=> 'Plans\Data\Repositories\PlanRepository',
		'StatRepository'	=> 'Plans\Data\Repositories\StatRepository',

		/**
		 * Repository Interfaces
		 */
		'CreditRepositoryInterface'				=> 'Scheduler\Data\Interfaces\CreditRepositoryInterface',
		'LocationRepositoryInterface'			=> 'Scheduler\Data\Interfaces\LocationRepositoryInterface',
		'NotificationRepositoryInterface'		=> 'Scheduler\Data\Interfaces\NotificationRepositoryInterface',
		'ServiceRepositoryInterface'			=> 'Scheduler\Data\Interfaces\ServiceRepositoryInterface',
		'StaffRepositoryInterface'				=> 'Scheduler\Data\Interfaces\StaffRepositoryInterface',
		'StaffAppointmentRepositoryInterface'	=> 'Scheduler\Data\Interfaces\StaffAppointmentRepositoryInterface',
		'StaffScheduleRepositoryInterface'		=> 'Scheduler\Data\Interfaces\StaffScheduleRepositoryInterface',
		'UserRepositoryInterface'				=> 'Scheduler\Data\Interfaces\UserRepositoryInterface',

		'CommentRepositoryInterface'	=> 'Plans\Data\Interfaces\CommentRepositoryInterface',
		'GoalRepositoryInterface'		=> 'Plans\Data\Interfaces\GoalRepositoryInterface',
		'PlanRepositoryInterface'		=> 'Plans\Data\Interfaces\PlanRepositoryInterface',
		'StatRepositoryInterface'		=> 'Plans\Data\Interfaces\StatRepositoryInterface',

		/**
		 * Transformers
		 */
		'ServiceTransformer'			=> 'Scheduler\Api\Transformers\ServiceTransformer',

	),

);