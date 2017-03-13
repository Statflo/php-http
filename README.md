# PHP HTTP package

## installation

#### composer.json
```json

{
    "require": {
        /* ... */
        "statflo/php-http": "dev-master",
    }
}
```

## Usage

#### web/index.php

```php
<?php

use Statflo\HTTP\Main;

//$app = Silex\Application;

Main::run($app ?: null, [
    'debug'       =>  (bool) getenv('PHP_APP_DEBUG'),
    'session'     => $_SESSION,
    'config_path' => dirname(__FILE__) . "/../config",
    'parameters'  => [
    ],
    'controllers' => [
        "/api/accounts"       => [
            ['method' => 'get',  'class' => Statflo\Controller\Crm\Accounts::class, 'id' => 'statflo.controller.crm.accounts:findAll'],
        ],
        "/api/accounts/merge" => [
            ['method' => 'post', 'class' => Statflo\Controller\Crm\Accounts::class, 'id' => 'statflo.controller.crm.accounts:merge'],
        ],
    ]
]);

```
