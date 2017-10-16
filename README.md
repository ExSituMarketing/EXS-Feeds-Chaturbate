# EXS-FeedsChaturbateBundle

[![Build Status](https://travis-ci.org/ExSituMarketing/EXS-FeedsChaturbateBundle.svg?branch=master)](https://travis-ci.org/ExSituMarketing/EXS-FeedsChaturbateBundle)

## Install

Require the bundle from packagist

```
$ composer require exs/feeds-chaturbate-bundle
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

Some configuration is available to manage the cache.

```yml
# Default values
exs_feeds_chaturbate:
    cache_ttl: 300
    memcached_host: 'localhost'
    memcached_port: 11211
```

## Usage

```php
/**
 * Returns live performers information.
 */
$performers = $container
    ->get('exs_feeds_chaturbate.feeds_reader')
    ->getLivePerformers()
;

/**
 * Performers information  are like :
 *
 * $performers = [
 *     [
 *         'num_followers' => 999,
 *         'display_name' => "XXX",
 *         'tags' => [
 *             "XXX",
 *         ],
 *         'location' => "XXX",
 *         'username' => "XXX",
 *         'spoken_languages' => "XXX",
 *         'is_hd' => true,
 *         'seconds_online' => 999,
 *         'gender' => "X",
 *         'age' => 99,
 *         'num_users' => 999,
 *         'room_subject' => "XXX",
 *     ],
 *     ...
 * ];
 */
```

A command is also available if you want to force refresh the cache.

```bash
$ app/console feeds:chaturbate:refresh-live-performers --env=prod --no-debug

// Can specify cache lifetime
$ app/console feeds:chaturbate:refresh-live-performers --ttl=3600 --env=prod --no-debug
```
