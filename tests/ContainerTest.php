<?php
namespace Projetmvc\PhpFrameworkPro\Tests;

use PHPUnit\Framework\TestCase;
use Projetmvc\PhpFrameworkPro\Container\Container;
use Projetmvc\PhpFrameworkPro\Tests\DependantClass;
use Projetmvc\PhpFrameworkPro\Tests\DependencyClass;
use Projetmvc\PhpFrameworkPro\Tests\SubDependencyClass;
use Projetmvc\PhpFrameworkPro\Container\ContainerException;


class ContainerTest extends TestCase
{
    /** @test */
    public function a_service_can_be_retrieved_from_the_container()
    {
        // Setup
        $container = new Container();

        // Do something
        // On ajoute une classe à notre container en précisant un identifiant et la classe à instancier
        $container->add('dependant-class', DependantClass::class);

        // Make assertions
        // On s'assure que l'objet récupéré est bien une instance de la classe DependantClass
        $this->assertInstanceOf(DependantClass::class, $container->get('dependant-class'));
    }

    /** @test */
    public function a_ContainerException_is_thrown_if_a_service_cannot_be_found()
    {
        // Setup
        $container = new Container();

        // Expect exception
        // On s'assure qu'une exception est levée si on essaie de récupérer un service qui n'a pas été ajouté au container
        $this->expectException(ContainerException::class);

        // Do something
        // On essaie de récupérer un service qui n'existe pas
        $container->add('foobar');
    }

    /** @test */
    public function can_check_if_the_container_has_a_service(): void
    {
        // Setup
        $container = new Container();

        // Do something
        // On ajoute une classe à notre container en précisant un identifiant et la classe à instancier
        $container->add('dependant-class', DependantClass::class);

        // On vérifie que le container contient bien une classe qui a été ajoutée
        $this->assertTrue($container->has('dependant-class'));
        
        // On vérifie que le container ne contient pas une classe qui n'a pas été ajoutée
        $this->assertFalse($container->has('non-existent-class'));
    }

     /** @test */
     public function services_can_be_recursively_autowired()
     {
         $container = new Container();
 
         $dependantService = $container->get(DependantClass::class);
 
         $dependancyService = $dependantService->getDependency();
 
         $this->assertInstanceOf(DependencyClass::class, $dependancyService);
         $this->assertInstanceOf(SubDependencyClass::class, $dependancyService->getSubDependency());
     }
}


// chemin pour test unutaire 

// ./vendor/bin/phpunit ./framework/tests/ContainerTest.php
