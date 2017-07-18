<?php

namespace Statflo\HTTP;

use Silex\Application as BaseApplication;

class Router
{
    private function __construct(){}

    public static function register(array $routes, BaseApplication $app)
    {
        (new self())->run($routes, $app);
    }

    public function run(array $routes, BaseApplication $app)
    {
        $this->defineServices($routes, $app);

        foreach ($routes as $route => $config) {
            $this->defineRoutes($route, $config, $app);
        }
    }

    private function defineServices($routes, BaseApplication $app)
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

    private function defineRoutes($route, array $configs, BaseApplication $app)
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

    private function defineRoute($route, array $config, BaseApplication $app)
    {
        $catchAll = strpos($route, '{catch_all}') !== false;

        if (substr($route, -1) !== "/") {
            $r1 = $app->{$config['method']}($route . "/", $config['id']);
            $r2 = $app->{$config['method']}($route, $config['id']);

            if ($catchAll) {
                $r1->assert("catch_all", ".*");
                $r2->assert("catch_all", ".*");
            }

            return;
        }

        $r1 = $app->{$config['method']}($route, $config['id']);
        $r2 = $app->{$config['method']}(substr($route, -1), $config['id']);

        if ($catchAll) {
            $r1->assert("catch_all", ".*");
            $r2->assert("catch_all", ".*");
        }
    }
}
