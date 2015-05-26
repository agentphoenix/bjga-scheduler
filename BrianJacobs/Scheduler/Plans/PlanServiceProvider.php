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

		$this->app['events']->listen('goal.created', 'Plans\Events\GoalEventHandler@onCreate');
		$this->app['events']->listen('goal.deleted', 'Plans\Events\GoalEventHandler@onDelete');
		$this->app['events']->listen('goal.updated', 'Plans\Events\GoalEventHandler@onUpdate');
		$this->app['events']->listen('goal.reopened', 'Plans\Events\GoalEventHandler@onReOpen');
		$this->app['events']->listen('goal.completed', 'Plans\Events\GoalEventHandler@onComplete');

		$this->app['events']->listen('comment.created', 'Plans\Events\ConversationEventHandler@onCreate');
		$this->app['events']->listen('comment.deleted', 'Plans\Events\ConversationEventHandler@onDelete');
		$this->app['events']->listen('comment.updated', 'Plans\Events\ConversationEventHandler@onUpdate');

		$this->app['events']->listen('stats.created', 'Plans\Events\StatsEventHandler@onCreate');
		$this->app['events']->listen('stats.deleted', 'Plans\Events\StatsEventHandler@onDelete');
		$this->app['events']->listen('stats.updated', 'Plans\Events\StatsEventHandler@onUpdate');
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
