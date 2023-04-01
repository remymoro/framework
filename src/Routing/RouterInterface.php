<?php

namespace Projetmvc\PhpFrameworkPro\Routing;

use Projetmvc\Framework\Http\Request;

interface RouterInterface
{
    public function dispatch(Request $request);
}
