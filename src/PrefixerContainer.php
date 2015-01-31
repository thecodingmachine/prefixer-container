<?php
namespace Mouf\PrefixerContainer;

use Interop\Container\ContainerInterface;

/**
 * The Prefixer Container is a container that wraps a target container.
 * All entries in the target container will be prefixed with a string you choose.
 *
 * For instance, if you configure the prefixer container with "mouf." and you decide to wrap a Mouf container,
 * in order to access instance named "my_entry" in Mouf, you'll have to query entry "mouf.my_entry".
 *
 * This is very useful to avoid instance name collisions when there are many containers around.
 *
 * If your container is implementing the delegate lookup feature, please sure to use the DelegateLookupUnprefixerContainer
 * class to wrap the root container.
 */
class PrefixerContainer implements ContainerInterface
{
    /**
     * @var string The prefix to add in front of all identifiers
     */
    protected $prefix;

    /**
     * @var ContainerInterface The container that will be used for lookups
     */
    protected $targetContainer;

    /**
     * @param ContainerInterface $targetContainer The container that will be used for entries lookups.
     * @param $prefix The prefix to add in front of all identifiers
     */
    public function __construct(ContainerInterface $targetContainer, $prefix)
    {
        $this->prefix = $prefix;
        $this->targetContainer = $targetContainer;
    }

    public function get($id)
    {
        $newId = $this->transformIdentifier($id);
        if ($newId === null) {
            throw new PrefixerContainerNotFoundException(sprintf("Could not find instance '%s'. Note: all instances in this container must be prefixed by '%s'.", $id, $this->prefix));
        } else {
            return $this->targetContainer->get($newId);
        }
    }

    public function has($id)
    {
        $newId = $this->transformIdentifier($id);
        if ($newId === null) {
            return false;
        } else {
            return $this->targetContainer->has($newId);
        }
    }

    /**
     * Removes the prefix from the identifier.
     * Returns null of the identifier does not start with the prefix.
     *
     * @param $id
     * @return null|string
     */
    private function transformIdentifier($id) {
        if (strpos($id, $this->prefix) === 0) {
            return substr($id, strlen($this->prefix));
        } else {
            return null;
        }
    }
}
