<?php
namespace Statflo\HTTP\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Statflo\DI\Base\ContainerAware;
use Statflo\HTTP\DTO\Response;

abstract class ContainerController extends ContainerAware
{
    use Statflo\HTTP\ResponseTrait;
}
