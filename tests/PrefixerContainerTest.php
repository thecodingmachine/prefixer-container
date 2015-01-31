<?php
namespace Mouf\PrefixerContainer;

use Acclimate\Container\CompositeContainer;
use Interop\Container\ContainerInterface;
use Mouf\Picotainer\Picotainer;

/**
 * Test class for PrefixerContainer
 *
 * @author David Négrier <david@mouf-php.com>
 */
class PrefixerContainerTest extends \PHPUnit_Framework_TestCase
{

    public function getRootContainer() {
        $rootContainer = new CompositeContainer();

        $containerA = new Picotainer([
            "instance" => function () { return "valueA"; },
        ], new DelegateLookupUnprefixerContainer($rootContainer, "A."));

        $containerB = new Picotainer([
            "instance" => function () { return "valueB"; },
            "instanceWithInternalLookup" => function (ContainerInterface $c) { return $c->get('instance'); },
            "instanceWithExternalLookup" => function (ContainerInterface $c) { return $c->get('A.instance'); },
            "instanceWithHasOnRootContainer" => function (ContainerInterface $c) {
                $this->assertTrue($c->has('instanceWithInternalLookup'));
                $this->assertTrue($c->has('B.instanceWithInternalLookup'));
                $this->assertFalse($c->has('notfound'));
                return "Hello world";
            },
        ], new DelegateLookupUnprefixerContainer($rootContainer, "B."));

        $rootContainer->addContainer(new PrefixerContainer($containerA, "A."));
        $rootContainer->addContainer(new PrefixerContainer($containerB, "B."));

        return $rootContainer;
    }

    /**
     * @expectedException Interop\Container\Exception\NotFoundException
     */
    public function testGetException()
    {
        $rootContainer = $this->getRootContainer();

        $rootContainer->get('instance');
    }

    /**
     * @expectedException Interop\Container\Exception\NotFoundException
     */
    public function testGetExceptionOnScope()
    {
        $containerA = new Picotainer([
            "instance" => function () { return "valueA"; },
        ]);
        $prefixedContainerA = new PrefixerContainer($containerA, "A.");

        $prefixedContainerA->get('notfound');
    }

    public function testGet()
    {
        $rootContainer = $this->getRootContainer();

        $this->assertEquals("valueA", $rootContainer->get('A.instance'));
        $this->assertEquals("valueB", $rootContainer->get('B.instance'));
        $this->assertEquals("valueB", $rootContainer->get('B.instanceWithInternalLookup'));
        $this->assertEquals("valueA", $rootContainer->get('B.instanceWithExternalLookup'));
    }

    public function testHas()
    {
        $rootContainer = $this->getRootContainer();

        $this->assertTrue($rootContainer->has('A.instance'));
        $this->assertTrue($rootContainer->has('B.instance'));
        $this->assertTrue($rootContainer->has('B.instanceWithInternalLookup'));
        $this->assertTrue($rootContainer->has('B.instanceWithExternalLookup'));
        $this->assertFalse($rootContainer->has('toto'));

        $rootContainer->get('B.instanceWithHasOnRootContainer');
    }

}
