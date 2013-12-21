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

	'key' => 'YourSecretKey!!!',

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
		'Illuminate\Foundation\Providers\CommandCreatorServiceProvider',
		'Illuminate\Session\CommandsServiceProvider',
		'Illuminate\Foundation\Providers\ComposerServiceProvider',
		'Illuminate\Routing\ControllerServiceProvider',
		'Illuminate\Cookie\CookieServiceProvider',
		'Illuminate\Database\DatabaseServiceProvider',
		'Illuminate\Encryption\EncryptionServiceProvider',
		'Illuminate\Filesystem\FilesystemServiceProvider',
		'Illuminate\Hashing\HashServiceProvider',
		'Illuminate\Html\HtmlServiceProvider',
		'Illuminate\Foundation\Providers\KeyGeneratorServiceProvider',
		'Illuminate\Log\LogServiceProvider',
		'Illuminate\Mail\MailServiceProvider',
		'Illuminate\Foundation\Providers\MaintenanceServiceProvider',
		'Illuminate\Database\MigrationServiceProvider',
		'Illuminate\Foundation\Providers\OptimizeServiceProvider',
		'Illuminate\Pagination\PaginationServiceProvider',
		'Illuminate\Foundation\Providers\PublisherServiceProvider',
		'Illuminate\Queue\QueueServiceProvider',
		'Illuminate\Redis\RedisServiceProvider',
		'Illuminate\Auth\Reminders\ReminderServiceProvider',
		'Illuminate\Foundation\Providers\RouteListServiceProvider',
		'Illuminate\Database\SeedServiceProvider',
		'Illuminate\Foundation\Providers\ServerServiceProvider',
		'Illuminate\Session\SessionServiceProvider',
		'Illuminate\Foundation\Providers\TinkerServiceProvider',
		'Illuminate\Translation\TranslationServiceProvider',
		'Illuminate\Validation\ValidationServiceProvider',
		'Illuminate\View\ViewServiceProvider',
		'Illuminate\Workbench\WorkbenchServiceProvider',

		"Scheduler\\Providers\\SchedulerServiceProvider",

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

		'App'			=> 'Illuminate\Support\Facades\App',
		'Artisan'		=> 'Illuminate\Support\Facades\Artisan',
		'Auth'			=> 'Illuminate\Support\Facades\Auth',
		'Blade'			=> 'Illuminate\Support\Facades\Blade',
		'Cache'			=> 'Illuminate\Support\Facades\Cache',
		'ClassLoader'	=> 'Illuminate\Support\ClassLoader',
		'Config'		=> 'Illuminate\Support\Facades\Config',
		'Controller'	=> 'Illuminate\Routing\Controllers\Controller',
		'Cookie'		=> 'Illuminate\Support\Facades\Cookie',
		'Crypt'			=> 'Illuminate\Support\Facades\Crypt',
		'DB'			=> 'Illuminate\Support\Facades\DB',
		'Eloquent'		=> 'Illuminate\Database\Eloquent\Model',
		'Event'			=> 'Illuminate\Support\Facades\Event',
		'File'			=> 'Illuminate\Support\Facades\File',
		'Form'			=> 'Illuminate\Support\Facades\Form',
		'Hash'			=> 'Illuminate\Support\Facades\Hash',
		'HTML'			=> 'Illuminate\Support\Facades\HTML',
		'Input'			=> 'Illuminate\Support\Facades\Input',
		'Lang'			=> 'Illuminate\Support\Facades\Lang',
		'Log'			=> 'Illuminate\Support\Facades\Log',
		'Mail'			=> 'Illuminate\Support\Facades\Mail',
		'Paginator'		=> 'Illuminate\Support\Facades\Paginator',
		'Password'		=> 'Illuminate\Support\Facades\Password',
		'Queue'			=> 'Illuminate\Support\Facades\Queue',
		'Redirect'		=> 'Illuminate\Support\Facades\Redirect',
		'Redis'			=> 'Illuminate\Support\Facades\Redis',
		'Request'		=> 'Illuminate\Support\Facades\Request',
		'Response'		=> 'Illuminate\Support\Facades\Response',
		'Route'			=> 'Illuminate\Support\Facades\Route',
		'Schema'		=> 'Illuminate\Support\Facades\Schema',
		'Seeder'		=> 'Illuminate\Database\Seeder',
		'Session'		=> 'Illuminate\Support\Facades\Session',
		'Str'			=> 'Illuminate\Support\Str',
		'URL'			=> 'Illuminate\Support\Facades\URL',
		'Validator'		=> 'Illuminate\Support\Facades\Validator',
		'View'			=> 'Illuminate\Support\Facades\View',

		'Date'					=> "Carbon\\Carbon",
		'Model'					=> "Scheduler\\Extensions\\Laravel\\Database\\Eloquent\\Model",
		//'Markdown'				=> "Scheduler\\Facades\\Markdown",
		'ServiceFullException'	=> "Scheduler\\Exceptions\\ServiceFullException",

		/**
		 * Event Handlers
		 */
		'AppointmentEventHandler'	=> "Scheduler\\Events\\Appointment",
		'CategoryEventHandler'		=> "Scheduler\\Events\\Category",
		'CreditEventHandler'		=> "Scheduler\\Events\\Credit",
		'ServiceEventHandler'		=> "Scheduler\\Events\\Service",
		'SettingEventHandler'		=> "Scheduler\\Events\\Setting",
		'StaffEventHandler'			=> "Scheduler\\Events\\Staff",
		'UserEventHandler'			=> "Scheduler\\Events\\User",

		/**
		 * Validators
		 */
		'AppointmentValidator'	=> "Scheduler\\Validators\\Appointment",
		'BaseValidator'			=> "Scheduler\\Validators\\Base",
		'CategoryValidator'		=> "Scheduler\\Validators\\Category",
		'CreditValidator'		=> "Scheduler\\Validators\\Credit",
		'ServiceValidator'		=> "Scheduler\\Validators\\Service",
		'SettingValidator'		=> "Scheduler\\Validators\\Setting",
		'StaffValidator'		=> "Scheduler\\Validators\\Staff",
		'UserValidator'			=> "Scheduler\\Validators\\User",

		/**
		 * Models
		 */
		'Appointment'		=> "Scheduler\\Models\\Appointment",
		'Category'			=> "Scheduler\\Models\\Category",
		'Credit'			=> "Scheduler\\Models\\Credit",
		'Schedule'			=> "Scheduler\\Models\\Schedule",
		'ScheduleException'	=> "Scheduler\\Models\\ScheduleException",
		'Service'			=> "Scheduler\\Models\\Service",
		'ServiceOccurrence'	=> "Scheduler\\Models\\ServiceOccurrence",
		'Setting'			=> "Scheduler\\Models\\Setting",
		'Staff'				=> "Scheduler\\Models\\Staff",
		'User'				=> "Scheduler\\Models\\User",
		'UserAppointment'	=> "Scheduler\\Models\\UserAppointment",

		/**
		 * Repositories
		 */
		'AppointmentRepository'	=> "Scheduler\\Repositories\\AppointmentRepository",
		'CategoryRepository'	=> "Scheduler\\Repositories\\CategoryRepository",
		'ScheduleRepository'	=> "Scheduler\\Repositories\\ScheduleRepository",
		'ServiceRepository'		=> "Scheduler\\Repositories\\ServiceRepository",
		'StaffRepository'		=> "Scheduler\\Repositories\\StaffRepository",
		'UserRepository'		=> "Scheduler\\Repositories\\UserRepository",

		/**
		 * Repository Interfaces
		 */
		'AppointmentRepositoryInterface'	=> "Scheduler\\Interfaces\\AppointmentRepositoryInterface",
		'CategoryRepositoryInterface'		=> "Scheduler\\Interfaces\\CategoryRepositoryInterface",
		'ScheduleRepositoryInterface'		=> "Scheduler\\Interfaces\\ScheduleRepositoryInterface",
		'ServiceRepositoryInterface'		=> "Scheduler\\Interfaces\\ServiceRepositoryInterface",
		'StaffRepositoryInterface'			=> "Scheduler\\Interfaces\\StaffRepositoryInterface",
		'UserRepositoryInterface'			=> "Scheduler\\Interfaces\\UserRepositoryInterface",

	),

);
