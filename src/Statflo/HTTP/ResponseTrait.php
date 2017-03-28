<?php
namespace Statflo\HTTP;

use Symfony\Component\HttpFoundation\JsonResponse;
use Statflo\HTTP\DTO\Response;

trait ResponseTrait
{
    public function response($data, $statusCode = 200)
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
