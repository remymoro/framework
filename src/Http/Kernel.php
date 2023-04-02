<?php

namespace Projetmvc\PhpFrameworkPro\Http;

use FastRoute\RouteCollector;

use Projetmvc\PhpFrameworkPro\Http\Request;
use Projetmvc\PhpFrameworkPro\Http\Response;
use Projetmvc\PhpFrameworkPro\Routing\RouterInterface;
use Psr\Container\ContainerInterface;

use function FastRoute\simpleDispatcher;

class Kernel
{
    public function __construct(
        private RouterInterface $router,
        private ContainerInterface $container
    )
    {
    }



    public function handle(Request $request): Response
    {
        try {
            [$routeHandler, $vars] = $this->router->dispatch($request, $this->container);
            $response = call_user_func_array($routeHandler, $vars);
        } catch (\Exception $exception) {
            $response = new Response($exception->getMessage(), 400);
        }

        return $response;
    }
}
