<?php
namespace Projetmvc\PhpFrameworkPro\Container;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use ProjetMvc\PhpFrameworkPro\Tests\DependantClass;


class Container implements ContainerInterface
{
    private array $services = [];

    // Ajouter un service au container
    public function add(string $id, string|object $concrete = null)
    {
        if (null === $concrete) {
            // Si aucun objet concret n'a été fourni, on tente de créer un objet à partir de la classe donnée en paramètre
            if (!class_exists($id)) {
                throw new ContainerException("Service $id could not be found");
            }

            $concrete = $id;
        }

        $this->services[$id] = $concrete;
    }

    // Récupérer un service depuis le container
    public function get(string $id)
    {
        // Si le service n'existe pas, on tente de le créer à partir de la classe dont le nom est donné en paramètre
        if (!$this->has($id)) {
            if (!class_exists($id)) {
                throw new ContainerException("Service $id could not be resolved");
            }

            $this->add($id);
        }

        // On récupère l'objet correspondant au service
        $object = $this->resolve($this->services[$id]);

        return $object;
    }

  

    // Résoudre les dépendances d'une classe et retourner une instance de cette classe
    private function resolve($class): object
    {
        // 1. On crée une instance de la classe Reflection correspondant à la classe dont on veut résoudre les dépendances
        $reflectionClass = new \ReflectionClass($class);

        // 2. On tente d'obtenir le constructeur de cette classe
        $constructor = $reflectionClass->getConstructor();

        // 3. Si la classe n'a pas de constructeur, on peut l'instancier directement
        if (null === $constructor) {
            return $reflectionClass->newInstance();
        }

        // 4. On récupère les paramètres du constructeur
        $constructorParams = $constructor->getParameters();

        // 5. On résoud les dépendances de chaque paramètre
        $classDependencies = $this->resolveClassDependencies($constructorParams);

        // 6. On instancie la classe avec ses dépendances
        $service = $reflectionClass->newInstanceArgs($classDependencies);

        // 7. On retourne l'objet
        return $service;
    }

    // Résoudre les dépendances d'une méthode ou d'un constructeur
    private function resolveClassDependencies(array $reflectionParameters): array
    {
        // 1. On crée un tableau vide qui contiendra les dépendances
        $classDependencies = [];

        // 2. Pour chaque paramètre, on tente de créer une instance de la classe correspondante
        /** @var \ReflectionParameter $parameter */
        foreach ($reflectionParameters as $parameter) {
            // On récupère le type de retour du paramètre
            $serviceType = $parameter->getType();

            // On tente de récupérer une instance de la classe correspondant au type de retour
            $service = $this->get($serviceType->getName());

            // On ajoute l'objet créé au tableau de dépendances
            $classDependencies[] = $service;
             // 3. Return the classDependencies array
             // On renvoie le tableau de dépendances, qui sera utilisé comme argument pour la méthode newInstanceArgs() de ReflectionClass.
            return $classDependencies;
        }
    }

      // Vérifier si un service est présent dans le container
      public function has(string $id): bool
      {
          return array_key_exists($id, $this->services);
      }



}



