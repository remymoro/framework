<?php
namespace Projetmvc\PhpFrameworkPro\Tests;

use Projetmvc\PhpFrameworkPro\Tests\DependencyClass;


class DependantClass
{
    public function __construct(private DependencyClass $dependency)
    {
    }

    public function getDependency(): DependencyClass
    {
        return $this->dependency;
    }
}