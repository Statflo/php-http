<?php

namespace Statflo\HTTP;

$vendor = dirname(__FILE__) . '/../../vendor/autoload.php';

if (file_exists($vendor)) {
    require_once $vendor;
}

use Silex\Application;

class Main
{
    private $config = [];

    private function __construct(array $config)
    {
        if (is_array($config)) {
            $this->config = $config;
        }
    }

    public static function run(Application $app = null, array $config = [])
    {
        $nonExistentApp = is_null($app);

        if ($nonExistentApp) {
            $app = new Application();
            $app['debug'] = $config['debug'];
        }

        $app->register(new \Silex\Provider\ServiceControllerServiceProvider());

        (new self($config))->start($app);

        if ($nonExistentApp) {
            $app->run();
        }
    }

    public function start(Application $app)
    {
        $config = $this->config;
        $routes = $this->config['controllers'];

        unset($config['controllers']);

        $bootstrap        = \Statflo\DI\Bootstrap::run($config);
        $app['container'] = $bootstrap;

        \Statflo\HTTP\Router::register($routes, $app);
    }
}
