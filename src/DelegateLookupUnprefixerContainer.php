<?php
namespace Mouf\PrefixerContainer;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\NotFoundException;

/**
 * The DelegateLookupUnprefixerContainer is a container that is used to wrap the delegate lookup container
 * when you are using the PrefixContainer.
 *
 */
class DelegateLookupUnprefixerContainer implements ContainerInterface
{
    /**
     * @var string The prefix to add in front of all identifiers
     */
    protected $prefix;

    /**
     * @var ContainerInterface The container that will be used for lookups
     */
    protected $rootContainer;

    /**
     * @param ContainerInterface $rootContainer The container that is wrapped.
     * @param string $prefix The prefix to add in front of all identifiers
     */
    public function __construct(ContainerInterface $rootContainer, $prefix)
    {
        $this->prefix = $prefix;
        $this->rootContainer = $rootContainer;
    }

    public function get($id)
    {
        // Let's first try to find a prefixed entry. If it does not work, let's try the entry without prefix.

        // Note: performance-wise, we think most of the time, the prefixed version will be found, so we decide to
        // catch the exception in the workflow rather than calling "has". This should be tested however.
        $prefixedId = $this->prefix.$id;

        try {
            return $this->rootContainer->get($prefixedId);
        } catch (NotFoundException $e) {
            return $this->rootContainer->get($id);
        }
    }

    public function has($id)
    {
        $prefixedId = $this->prefix.$id;

        if ($this->rootContainer->has($prefixedId)) {
            return true;
        } else {
            return $this->rootContainer->has($id);
        }
    }
}
