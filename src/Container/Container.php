<?php
namespace Projetmvc\PhpFrameworkPro\Container;

use Projetmvc\PhpFrameworkPro\Container\ContainerException;






class Container
{
    private array $services = [];


    public function add(string $id, string|object $concrete = null)
    {
        if (null === $concrete) {
            if (!class_exists($id)) {
                throw new ContainerException("Service $id could not be found");
            }

            $concrete = $id;
        }

        $this->services[$id] = $concrete;
    }


    public function get(string $id)
    {
        return new $this->services[$id];
    }

    public function has(string $id): bool
    {
        // TODO: Implement has() method.
    }


}
