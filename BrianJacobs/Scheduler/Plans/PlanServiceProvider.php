<?php namespace Plans;

use Illuminate\Support\ServiceProvider;

class PlanServiceProvider extends ServiceProvider {

	protected $aliases;

	public function register()
	{
		$this->setRepositoryBindings();
	}

	public function boot()
	{
		$this->setupEventListeners();
	}

	protected function setupEventListeners()
	{
		$this->app['events']->listen('plan.created', 'Plans\Events\PlanEventHandler@onCreate');
		$this->app['events']->listen('plan.deleted', 'Plans\Events\PlanEventHandler@onDelete');
		$this->app['events']->listen('plan.updated', 'Plans\Events\PlanEventHandler@onUpdate');
	}

	protected function setRepositoryBindings()
	{
		// Grab the aliases from the config
		$this->aliases = $this->app['config']['app.aliases'];

		// Set the items being bound
		$bindings = ['Conversation', 'Goal', 'Plan', 'Stat'];

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
