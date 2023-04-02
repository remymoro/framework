<?php

namespace Projetmvc\PhpFrameworkPro\Routing;

use Projetmvc\PhpFrameworkPro\Http\Request;


interface RouterInterface
{
    public function dispatch(Request $request);

    public function setRoutes(array $routes): void;
}
