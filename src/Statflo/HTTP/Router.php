<?php

namespace Statflo\HTTP;

use Silex\Application;

class Router
{
    private function __construct(){}

    public static function register(array $routes, Application $app)
    {
        (new self())->run($routes, $app);
    }

    public function run(array $routes, Application $app)
    {
        $this->defineServices($routes, $app);

        foreach ($routes as $route => $config) {
            $this->defineRoutes($route, $config, $app);
        }
    }

    private function defineServices($routes, Application $app)
    {
        $classNames = [];
        foreach ($routes as $route => $configs) {
            $checkReference = array_values($configs);
            $check          = array_pop($checkReference);
            $configs        = !is_array($check) ? [$configs] : $configs;

            foreach ($configs as $config) {
                $classNames[] = $config;
            }
        }

        foreach ($classNames as $config) {
            $id = $config['id'];

            try {
                if (isset($app[$id])) {
                    continue;
                }
            } catch (\Exception $e) {
                $className = $config['class'];
                $app[$id]  = new $className($app['container']);
            }
        }
    }

    private function defineRoutes($route, array $configs, Application $app)
    {
        $checkReference = array_values($configs);
        $check          = array_pop($checkReference);

        if (!is_array($check)) {
            $configs = [$configs];
        }

        foreach ($configs as $config) {
            $this->defineRoute($route, $config, $app);
        }
    }

    private function defineRoute($route, array $config, Application $app)
    {
        if (substr($route, -1) !== "/") {
            $app->{$config['method']}($route . "/", $config['id']);
            $app->{$config['method']}($route, $config['id']);

            return;
        }

        $app->{$config['method']}($route, $config['id']);
        $app->{$config['method']}(substr($route, -1), $config['id']);
    }
}
