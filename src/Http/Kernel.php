<?php
namespace Projetmvc\PhpFrameworkPro\Http;



use FastRoute\RouteCollector;

use function FastRoute\simpleDispatcher;
use Projetmvc\PhpFrameworkPro\Http\Request;
use Projetmvc\PhpFrameworkPro\Http\Response;
use Projetmvc\PhpFrameworkPro\Routing\RouterInterface;

class Kernel
{
    public function __construct(private RouterInterface $router)
    {
       
    }



    public function handle(Request $request): Response
    {

      
        try {
            [$routeHandler, $vars] = $this->router->dispatch($request);
            $response = call_user_func_array($routeHandler, $vars);
        } catch (\Exception $exception) {
            $response = new Response($exception->getMessage(), 400);
        }

        return $response;
    }
}
