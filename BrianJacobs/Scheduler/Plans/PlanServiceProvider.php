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
		//
	}

	protected function setRepositoryBindings()
	{
		// Grab the aliases from the config
		$this->aliases = $this->app['config']['app.aliases'];

		// Set the items being bound
		$bindings = ['Conversation', 'Goal', 'Plan'];

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
