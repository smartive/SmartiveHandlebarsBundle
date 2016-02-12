[![Build Status](https://travis-ci.org/smartive/SmartiveHandlebarsBundle.svg?branch=master)](https://travis-ci.org/smartive/SmartiveHandlebarsBundle)
[![Coverage Status](https://coveralls.io/repos/smartive/SmartiveHandlebarsBundle/badge.svg?branch=master&service=github)](https://coveralls.io/github/smartive/SmartiveHandlebarsBundle?branch=master)
[![Latest Stable Version](https://poser.pugx.org/smartive/handlebars-bundle/v/stable)](https://packagist.org/packages/smartive/handlebars-bundle)
[![Total Downloads](https://poser.pugx.org/smartive/handlebars-bundle/downloads)](https://packagist.org/packages/smartive/handlebars-bundle)
[![License](https://poser.pugx.org/smartive/handlebars-bundle/license)](https://packagist.org/packages/smartive/handlebars-bundle)

# SmartiveHandlebarsBundle

Bundle to integrate Handlebars templates into your Symfony2 / Symfony3 application.

This bundle renders handlebars with the help of [xamin/handlebars.php](https://github.com/XaminProject/handlebars.php).

## Installation

Require the `smartive/handlebars-bundle` package in your composer.json and update your dependencies.

```
{
    …
    "require": {
        "smartive/handlebars-bundle": "dev-master"
    }
    …
}
```

Register the bundle in `app/AppKernel.php`:

```php
// app/AppKernel.php
public function registerBundles()
{
    return array(
        // ...
        new Smartive\HandlebarsBundle\SmartiveHandlebarsBundle(),
    );
}
```

## Configuration

Some of the features can be configured in the ``smartive_handlebars`` section of `app/config/config.yml`.

### Handlebars file extension

The default file extension for Handlebars files is set to `.hbs`. 
This can be overridden using the following setting (example file extension set to `.handlebars`):

```
smartive_handlebars:
    templating:
        file_extension: .handlebars
```

### Template directories

The `template_directories` setting lets you define where to look for Handlebars templates.
You can use Symfony resource notation as well as absolute file paths to configure directories.


```
smartive_handlebars:
    templating:
        template_directories:
            - '@AcmeDemo/Resources/views/Templates'
            - /var/www/templates
```

By default, templates are getting search for in template directories recursively.
You can disable this behaviour as follows:

```
smartive_handlebars:
    templating:
        template_directories_recursive: false
```

### Twig extension

The Handlebars Twig extension is enabled by default. To disable it add this to your configuration:

```
smartive_handlebars:
    twig:
        enabled: false
```

## Usage

### Rendering service
The `smartive_handlebars.templating.renderer` service offers a `render($templateName, $data)` method which can be use to render Handlebars templates.

### Twig
To render Handlebars templates in Twig you can use the Twig function `handlebars(templateName, data)`.

## Custom Handlebars helpers
You can add you own Handlebars helpers as tagged services by implementing the `Handlebars\Helper` interface. To find out more about how to write custom helpers please have a look at the [built-in helpers by xamin/handlebars.php](https://github.com/XaminProject/handlebars.php/tree/master/src/Handlebars/Helper).

Once you've implemented your own helper you have to register it as a service using the `smartive_handlebars.helper` tag and an appropriate alias:

```
# app/config/services.yml
services:
    demo_bundle.my_demo_helper:
        class: DemoBundle\Helpers\MyDemoHelper
        tags:
            - { name: smartive_handlebars.helper, alias: myDemo }
```

You now can use your custom Handlebars helper inside your templates as follows:

```handlebars
{{#myDemo parameter}}
    {{!-- do stuff --}}
{{/myDemo}}
```

## Caching

The rendering service offers the ability to cache the parsed template between requests for faster rendering.

You can enable caching by setting `smartive_handlebars.cache` to a existing cache service ID in your `app/config/config.yml`:

```
smartive_handlebars:
    cache:
        enabled: true
        service: <service-id>
```

There are several caching services / strategies available per default:

### Disk

Service ID: `smartive_handlebars.cache.disk`

Uses [Handlebars\Cache\Disk](https://github.com/XaminProject/handlebars.php/blob/master/src/Handlebars/Cache/Disk.php) to read/write file cache in Symfony's cache directory

### APC

Service ID: `smartive_handlebars.cache.apc`

Uses [Handlebars\Cache\APC](https://github.com/XaminProject/handlebars.php/blob/master/src/Handlebars/Cache/APC.php) to read/write cache objects using [APC](http://php.net/manual/en/book.apc.php)

### Redis

Service ID: `smartive_handlebars.cache.redis`

Uses [PhpRedis](https://github.com/phpredis/phpredis) or [Predis](https://github.com/nrk/predis) to read/write cache objects using a [Redis Server](http://redis.io/). 
This bundle integrates with [RedisBundle](https://github.com/snc/SncRedisBundle) in order to make configuring your Redis implementation even easier. 
The default Redis client being used is `snc_redis.default` (see [RedisBundle documentation](https://github.com/snc/SncRedisBundle/blob/master/Resources/doc/index.md#usage)).

The default configuration can be overridden looks as follows:

```
smartive_handlebars:
    cache:
        redis:
            client_service: snc_redis.default
            key_prefix: 'smartive-handlebars:'
```

### Custom

You can also define your own caching services by adding a class which implements [Handlebars\Cache](https://github.com/XaminProject/handlebars.php/blob/master/src/Handlebars/Cache.php). 
To use your custom cache service you have to register it in your service configuration:

```
# app/config/services.yml
services:
    demo_bundle.my_demo_cache_service:
        class: DemoBundle\Cache\CustomCache
```

```
# app/config/config.yml
smartive_handlebars:
    cache: demo_bundle.my_demo_cache_service
```

## Complete configuration example

```
# app/config/config.yml
smartive_handlebars:
    templating:
        enabled: true
        file_extension: .hbs
        template_directories:
            - '@AcmeDemo/Resources/views/Templates'
            - /var/www/templates
        template_directories_recursive: true
    twig:
        enabled: true
    cache:
        enabled: false
        service: smartive_handlebars.cache.redis
        redis:
            client_service: snc_redis.default
            key_prefix: 'smartive-handlebars:'
```
