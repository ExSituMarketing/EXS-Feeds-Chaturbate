# EXS-FeedsChaturbateBundle

[![Build Status](https://travis-ci.org/ExSituMarketing/EXS-Feeds-ChaturbateBundle.svg?branch=master)](https://travis-ci.org/ExSituMarketing/EXS-Feeds-ChaturbateBundle)

## Install

Require the bundle from packagist
```
$ composer require exs/feeds-cambuilder-bundle
```

Enable the bundle in AppKernel
```php
<?php
...
class AppKernel extends Kernel
{
    ...
    public function registerBundles()
    {
        $bundles = array(
            ...
            new EXS\FeedsChaturbateBundle\EXSFeedsChaturbateBundle(),
        );
    }
    ...
}
```

## Config

Some configuration is avaible to manage the cache.

```yml
# Default values
exs_feeds_chaturbate:
    cache_ttl: 120
    memcached_host: 'localhost'
    memcached_port: 11211
```

## Usage

```php
// Returns live performers information.
$performerIds = $container
    ->get('exs_feeds_chaturbate.feeds_reader')
    ->getLivePerformers()
;

```
