Prefixer-Container
==================
[![Latest Stable Version](https://poser.pugx.org/mouf/prefixer-container/v/stable.svg)](https://packagist.org/packages/mouf/prefixer-container)
[![Latest Unstable Version](https://poser.pugx.org/mouf/prefixer-container/v/unstable.svg)](https://packagist.org/packages/mouf/prefixer-container)
[![License](https://poser.pugx.org/mouf/prefixer-container/license.svg)](https://packagist.org/packages/mouf/prefixer-container)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/thecodingmachine/prefixer-container/badges/quality-score.png?b=1.0)](https://scrutinizer-ci.com/g/thecodingmachine/prefixer-container/?branch=1.0)
<!-- [![SensioLabsInsight](https://insight.sensiolabs.com/projects/3ac43eac-dcec-496a-9e0f-5fe82f8b3824/mini.png)](https://insight.sensiolabs.com/projects/3ac43eac-dcec-496a-9e0f-5fe82f8b3824) -->
[![Build Status](https://travis-ci.org/thecodingmachine/prefixer-container.svg?branch=1.0)](https://travis-ci.org/thecodingmachine/prefixer-container)
[![Coverage Status](https://coveralls.io/repos/thecodingmachine/prefixer-container/badge.svg?branch=1.0)](https://coveralls.io/r/thecodingmachine/prefixer-container?branch=1.0)

This package contains a really minimalist dependency injection container that can be used to **create aliases** of instances
in existing containers. Alias-container is compatible with [container-interop](https://github.com/container-interop/container-interop)
and is meant to be used in conjunction with other containers. By itself, Alias-container does not store any entry. It can only be used
to **create aliases of instances stored in other containers**.

You can use PrefixerContainer to add support for alias for any container that does not support this feature.

Installation
------------

Before using PrefixerContainer in your project, add it to your `composer.json` file:

```
$ ./composer.phar require mouf/prefixer-container ~1.0
```



Why the need for this package?
------------------------------

This package is part of a long-term effort to bring [interoperability between DI containers](https://github.com/container-interop/container-interop). 
The ultimate goal is to make sure that multiple containers can communicate together by sharing entries (one container might 
use an entry from another container, etc...)
