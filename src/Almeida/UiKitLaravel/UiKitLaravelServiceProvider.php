<?php
namespace Almeida\UiKitLaravel;

use Illuminate\Support\ServiceProvider;

class UiKitLaravelServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('almeida/ui-kit-laravel', 'almeida/ui-kit-laravel');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $app = $this->app;

        // Bind the interfaces to the implementations
        // $app->bind('League\OAuth2\Server\Storage\ClientInterface', 'LucaDegasperi\OAuth2Server\Repositories\FluentClient');
        // $app->bind('League\OAuth2\Server\Storage\ScopeInterface', 'LucaDegasperi\OAuth2Server\Repositories\FluentScope');
        // $app->bind('League\OAuth2\Server\Storage\SessionInterface', 'LucaDegasperi\OAuth2Server\Repositories\FluentSession');

        $app['uikit.uikit'] = $app->share(function ($app) {
            $config = $app['config']->get('almeida/ui-kit-laravel::config');


            // Make table implementation
            $options = array(
                'paginator' => $config['tables']['paginator']
            );

            // Make feedback implementation
            $feedback = $app->make($config['feedback']['pattern']['class']);
            $table    = $app->make($config['tables']['pattern']['class'], array($options, $feedback));

            // Make button implementation
            $button    = $app->make($config['buttons']['class']);

            // Make actions implementation
            $actions = $app->make($config['actions']['class'], array($options, $button));


            // UiKit container
            $uikit  = $app->make('Almeida\UiKit\UiKit');

            $uikit->Table = $table;
            $uikit->Button = $button;
            $uikit->Actions = $actions;

            return $uikit;
        });

    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('uikit.uikit');
    }

}
