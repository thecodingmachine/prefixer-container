Prefixer-Container
==================
[![Latest Stable Version](https://poser.pugx.org/mouf/prefixer-container/v/stable.svg)](https://packagist.org/packages/mouf/prefixer-container)
[![Latest Unstable Version](https://poser.pugx.org/mouf/prefixer-container/v/unstable.svg)](https://packagist.org/packages/mouf/prefixer-container)
[![License](https://poser.pugx.org/mouf/prefixer-container/license.svg)](https://packagist.org/packages/mouf/prefixer-container)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/thecodingmachine/prefixer-container/badges/quality-score.png?b=1.0)](https://scrutinizer-ci.com/g/thecodingmachine/prefixer-container/?branch=1.0)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/e6cfc4b4-bc6d-4edc-8e01-ac05352c3689/mini.png)](https://insight.sensiolabs.com/projects/e6cfc4b4-bc6d-4edc-8e01-ac05352c3689)
[![Build Status](https://travis-ci.org/thecodingmachine/prefixer-container.svg?branch=1.0)](https://travis-ci.org/thecodingmachine/prefixer-container)
[![Coverage Status](https://coveralls.io/repos/thecodingmachine/prefixer-container/badge.svg?branch=1.0)](https://coveralls.io/r/thecodingmachine/prefixer-container?branch=1.0)

This package contains a really minimalist dependency injection container that can be used to **prefix** all identifiers
in a container. Prefixer-container is compatible with [container-interop](https://github.com/container-interop/container-interop)
and is meant to be used in conjunction with other containers. By itself, Prefix-container does not store any entry. It can only be used
to **wrap an existing container**.

You can use `PrefixerContainer` to put all identifiers of a container in a **namespace**.

Installation
------------

Before using `PrefixerContainer` in your project, add it to your `composer.json` file:

```
$ ./composer.phar require mouf/prefixer-container ~1.0
```

Usage
-----

Imagine you have 2 containers living side-by-side, and a composite container (we will call it the "root" container)
is joining them. Now, both containers declare a same instance named "dbConnection". 

If you want to keep access to both instances through the root container, you have a problem, because you have
a naming collision. Of course, you can rename one of those instances, but if the containers are provided by
third party libraries, that might not be possible.

![The issue](doc/the_issue.png?raw=true)

So what you need to do is to rename the instances of one of the containers so that there is no more conflict.
This is where the `PrefixerContainer` kicks in.

![Solution 1](doc/solution1.png?raw=true)

By wrapping your containers inside a `PrefixerContainer`, you can change the name of the instances to the outside
world.

Working with delegate lookup containers
---------------------------------------

If the container you are wrapping is implementing the [delegate lookup feature](https://github.com/container-interop/container-interop/blob/master/docs/Delegate-lookup.md) 
(it should!), you will face another problem.

When you use the delegate lookup feature, the dependencies are fetched from the root container. Now, the name of the
dependencies has changed because of the `PrefixerContainer`!

Just image a container with a service that uses the `dbConnection`:

![A container with a dependency](doc/container_with_dependency.png?raw=true)

What if we wrap this container in a `PrefixerContainer`? If we query the `A.myService` entry (1), the container will
delegate to the rootContainer the lookup of the `dbConnection` entry. Now, this is a problem, because it should
query the `A.dbConnection` entry.

![Delegate lookup issue](doc/delegate_lookup_issue.png?raw=true)

In order to fix this, the prefixer-container comes with a `DelegateLookupUnprefixerContainer` class. This is a wrapper 
you will use to wrap the delegate lookup container. When the `get` method of the wrapper is called, it will first try
to get the instance with the prefix, and if it fails, it will try to get the instance without the prefix.

![Delegate lookup solved](doc/delegate_lookup_solved.png?raw=true)

If we query the `A.myService` entry (1), , the container will delegate to the rootContainer the lookup of the `dbConnection` entry (2).
This goes through the `DelegateLookupUnprefixerContainer` first that will add the "A." prefix (3). The lookup goes through the
root container again, then the prefixer container that strips the "A." and finally, the dependency `dbConnection` is solved. *Job's done!*

Why the need for this package?
------------------------------

This package is part of a long-term effort to bring [interoperability between DI containers](https://github.com/container-interop/container-interop). 
The ultimate goal is to make sure that multiple containers can communicate together by sharing entries (one container might 
use an entry from another container, etc...)
