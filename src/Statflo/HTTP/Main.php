<?php

namespace Statflo\HTTP;

$vendor = dirname(__FILE__) . '/../../vendor/autoload.php';

if (file_exists($vendor)) {
    require_once $vendor;
}

use Silex\Application as BaseApplication;

class Main
{
    private $config = [];

    private function __construct(array $config)
    {
        if (is_array($config)) {
            $this->config = $config;
        }
    }

    public static function run(BaseApplication $app = null, array $config = [])
    {
        $nonExistentApp = is_null($app);

        if ($nonExistentApp) {
            $app = new \Statflo\HTTP\Application();
            $app['debug'] = $config['debug'];
        }

        $app->register(new \Silex\Provider\ServiceControllerServiceProvider());

        (new self($config))->start($app);

        if ($nonExistentApp) {
            $app->run();
        }
    }

    public function start(BaseApplication $app)
    {
        $config = $this->config;
        $routes = $this->config['controllers'];

        unset($config['controllers']);

        $bootstrap = \Statflo\DI\Bootstrap::run($config);
        $this->defineSession($bootstrap, $config);

        $app['container'] = $bootstrap;

        \Statflo\HTTP\Router::register($routes, $app);
        $bootstrap->compile();
    }

    private function defineSession($bootstrap, array $configuration = [])
    {
        $session = [];

        if (isset($configuration['session'])) {
            $session = [$configuration['session']];
        }

        $bootstrap->define(
            'statflo.session',
            \Statflo\DI\DTO\Collection::class,
            $session
        );

        $auth = [];

        if (isset($configuration['auth'])) {
            $auth = [$configuration['auth']];
        }

        $bootstrap->define(
            'statflo.auth',
            \Statflo\DI\DTO\Auth::class,
            $auth
        );
    }
}
