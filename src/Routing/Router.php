<?php
namespace Projetmvc\PhpFrameworkPro\Routing;

use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;
use Projetmvc\PhpFrameworkPro\Http\Request;



class Router implements RouterInterface
{
    public function dispatch(Request $request): array
    {
        // Create a dispatcher
        $dispatcher = simpleDispatcher(function (RouteCollector $routeCollector) {
            $routes = include BASE_PATH . '/routes/web.php';
            foreach ($routes as $route) {
                $routeCollector->addRoute(...$route);
            }
        });

        // Dispatch a URI, to obtain the route info
        $routeInfo = $dispatcher->dispatch(
            $request->getMethod(),
            $request->getPathInfo()
        );


        [$status, [$controller, $method], $vars] = $routeInfo;

        return [[new $controller, $method], $vars];
    }
}