<?php namespace Vdbf\Propel\Behaviors\Finite\Factory;

use Finite\Factory\AbstractFactory;
use Finite\Factory\FactoryInterface;
use Illuminate\Contracts\Container\Container;

/**
 * Class ContainerFactory
 *
 * State machine factory for the laravel dependency injection container
 *
 * @author Eelke van den Bos
 * @package Erati\Foundation\StateMachine
 */
class LaravelContainerFactory extends AbstractFactory implements FactoryInterface
{

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var string
     */
    protected $key;

    /**
     * @param Container $container
     * @param $key
     */
    public function __construct(Container $container, $key)
    {
        $this->container = $container;
        $this->key = $key;
    }

    /**
     * @return \Finite\StateMachine\StateMachineInterface
     */
    public function createStateMachine()
    {
        return $this->container->make($this->key);
    }

}