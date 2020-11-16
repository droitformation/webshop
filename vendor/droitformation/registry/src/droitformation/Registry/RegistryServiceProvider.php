<?php namespace Droitformation\Registry;

use Illuminate\Support\ServiceProvider;

class RegistryServiceProvider extends ServiceProvider {

	protected $defer = false;

	/**
	 * Perform post-registration booting of services.
	 *
	 * @return void
	 */
	public function boot()
	{
        $this->publishes([
            __DIR__.'/../../config/registry.php' => config_path('registry.php'),
        ]);

        $this->publishes([
            __DIR__ . '/../../migrations/' => base_path('/database/migrations')
        ], 'migrations');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->registerCache();
		$this->registerRegistry();
	}

    /**
     * Register the repository.
     *
     * @return void
     */
    protected function registerRegistry()
    {
        $app = app();

		$this->app->singleton('registry', function ($app) {

			$config = $app->config->get('registry', array());

			return new Registry($app['db'], $app['registry.cache'], $config);

		});

    }

    /**
     * Register the cache repository.
     *
     * @return void
     */
    protected function registerCache()
    {
        $app = app();

		$this->app->singleton('registry.cache', function ($app) {

			$meta = $app->config->get('registry.cache_path');
			$timestampManager = $app->config->get('registry.timestamp_manager');
			return new Cache($meta, $timestampManager);
		});
    }

	/**
	 * Get the services provider
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('registry');
	}
}
