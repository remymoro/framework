<?php

namespace Projetmvc\PhpFrameworkPro\Routing;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Psr\Container\ContainerInterface;

use Projetmvc\PhpFrameworkPro\Http\Request;

use function FastRoute\simpleDispatcher;

class Router implements RouterInterface
{
    private array $routes;


    public function dispatch(Request $request, ContainerInterface $container): array
    {
        $routeInfo = $this->extractRouteInfo($request);

        [$handler, $vars] = $routeInfo;

  


        if (is_array($handler)) {
            [$controllerId, $method] = $handler;
            $controller = $container->get($controllerId);
            $handler = [$controller, $method];
        }

        return [$handler, $vars];
    }



    public function setRoutes(array $routes): void
    {
        $this->routes = $routes;
    }


    private function extractRouteInfo(Request $request): array
    {
        // Create a FastRoute dispatcher by defining routes using a RouteCollector
        $dispatcher = simpleDispatcher(function (RouteCollector $routeCollector) {
            foreach ($this->routes as $route) {
                $routeCollector->addRoute(...$route);
            }
        });

        // Dispatch the incoming request to obtain the route information
        $routeInfo = $dispatcher->dispatch(
            $request->getMethod(),
            $request->getPathInfo()
        );
        
        // Handle the different possible outcomes of dispatching the request
        switch ($routeInfo[0]) {
            case Dispatcher::FOUND:
                // If a route was found, return the corresponding handler and route variables
                return [$routeInfo[1], $routeInfo[2]];
            case Dispatcher::METHOD_NOT_ALLOWED:
                // If the request method is not allowed for the route, throw an exception
                $allowedMethods = implode(', ', $routeInfo[1]);
                $e = new HttpRequestMethodException("The allowed methods are $allowedMethods");
                $e->setStatusCode(405);
                throw $e;
            default:
                // If no route was found, throw a 404 exception
                $e = new HttpException('Not found');
                $e->setStatusCode(404);
                throw $e;
        }
    }
}
