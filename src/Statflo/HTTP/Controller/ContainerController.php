<?php
namespace Statflo\HTTP\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Statflo\DI\Base\ContainerAware;
use Statflo\HTTP\DTO\Response;

abstract class ContainerController extends ContainerAware
{
    protected function response($data, $statusCode = 200)
    {
        $data = new Response($statusCode, $data);

        return new JsonResponse(
            $data,
            $statusCode,
            [
                'X-Status-Code'    => $statusCode,
                'X-Session-Length' => strlen(var_export($_SESSION, true)),
            ]
        );
    }
}
