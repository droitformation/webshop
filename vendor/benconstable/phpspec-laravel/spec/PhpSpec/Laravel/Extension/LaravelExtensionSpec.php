<?php

namespace spec\PhpSpec\Laravel\Extension;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use PhpSpec\ServiceContainer;

class LaravelExtensionSpec extends ObjectBehavior
{
    function let(ServiceContainer $container)
    {
        $container->setShared(Argument::cetera())->willReturn();
    }

    function it_is_a_phpspec_extension()
    {
        $this->shouldHaveType('PhpSpec\Extension\ExtensionInterface');
    }

    function it_registers_the_laravel_kernel(ServiceContainer $container)
    {
        $container
            ->setShared('laravel', Argument::type('Closure'))
            ->shouldBeCalled();

        $this->load($container);
    }

    function it_registers_the_laravel_maintainer(ServiceContainer $container)
    {
        $container
            ->setShared('runner.maintainers.laravel', Argument::type('Closure'))
            ->shouldBeCalled();

        $this->load($container);
    }

    function it_registers_the_presenter_maintainer(ServiceContainer $container)
    {
        $container
            ->setShared('runner.maintainers.presenter', Argument::type('Closure'))
            ->shouldBeCalled();

        $this->load($container);
    }

    function it_registers_the_laravel_listener(ServiceContainer $container)
    {
        $container
            ->setShared('event_dispatcher.listeners.laravel', Argument::type('Closure'))
            ->shouldBeCalled();

        $this->load($container);
    }

    public function getMatchers()
    {
        return [
            'endWith' => function($subject, $value) {
                return ($value === substr($subject, -strlen($value)));
            }
        ];
    }

}
