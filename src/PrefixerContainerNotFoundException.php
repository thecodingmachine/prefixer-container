<?php
namespace Mouf\PrefixerContainer;

use Interop\Container\Exception\NotFoundException;

/**
 * This exception is thrown when an identifier is passed to PrefixerContainer and is not found.
 *
 * @author David Négrier <david@mouf-php.com>
 */
class PrefixerContainerNotFoundException extends \InvalidArgumentException implements NotFoundException
{
}
