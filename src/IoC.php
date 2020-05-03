<?php

namespace App;

use ReflectionClass;
use ReflectionException;
use App\Exceptions\RunTimeException;

/**
 * Class IoC
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 */
class IoC
{
    /**
     * @var array
     */
    private static $instances = [];

    /**
     * Register a concretion to an abstraction
     *
     * @param string      $abstract
     * @param string|null $concrete
     */
    public static function inject(string $abstract, string $concrete = null)
    {
        if (is_null($concrete)) {
            $concrete = $abstract;
        }

        self::$instances[$abstract] = $concrete;
    }

    /**
     * Resolves an abstraction to its registered concretion
     *
     * @param  string $abstract
     * @return mixed
     * @throws RunTimeException
     */
    public static function resolve(string $abstract)
    {
        if (! isset(self::$instances[$abstract])) {
            self::inject($abstract);
        }

        return self::instantiate(self::$instances[$abstract]);
    }

    /**
     * @param  string $concrete
     * @return mixed
     * @throws RunTimeException
     */
    protected static function instantiate(string $concrete)
    {
        try {
            $reflector = new ReflectionClass($concrete);
        } catch (ReflectionException $e) {
            throw new RunTimeException("Cannot reflect {$concrete} class!");
        }

        if (! $reflector->isInstantiable()) {
            throw new RunTimeException("Class {$concrete} is not instantiable!");
        }

        $constructor = $reflector->getConstructor();
        if (is_null($constructor)) {
            return $reflector->newInstance();
        }

        $parameters   = $constructor->getParameters();
        $dependencies = self::getDependencies($parameters);

        // Get new instance with dependencies resolved
        return $reflector->newInstanceArgs($dependencies);
    }

    /**
     * @param  array $parameters
     * @return array
     * @throws RunTimeException
     */
    protected static function getDependencies(array $parameters)
    {
        $dependencies = [];
        foreach ($parameters as $parameter) {
            $dependency = $parameter->getClass();
            if (is_null($dependency)) {
                if ($parameter->isDefaultValueAvailable()) {
                    $dependencies[] = $parameter->getDefaultValue();
                } else {
                    throw new RunTimeException("Cannot resolve class dependency {$parameter->name}!");
                }
            } else {
                $dependencies[] = self::resolve($dependency->name);
            }
        }

        return $dependencies;
    }
}
