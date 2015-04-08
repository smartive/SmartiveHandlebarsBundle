# SmartiveHandlebarsBundle
Bundle to integrate Handlebars templates into your Symfony2 application

## Installation

Require the `smartive/handlebars-bundle` package in your composer.json and update your dependencies.

```json
{
    // ...
    "require": {
        "smartive/handlebars-bundle": "dev-master"
    }
    // ..
}
```

Register the bundle and in `app/AppKernel.php`:

``` php
// app/AppKernel.php
public function registerBundles()
{
    return array(
        // ...
        new Smartive\HandlebarsBundle\SmartiveHandlebarsBundle(),
    );
}
```

Some of the features can be configured in the ``migros_components`` section of app/config/config.yml.
Documentation for those features can be found in `Resources/doc/features`.

## Configuration

The Handlebars feature is enabled by default. It can be disabled using the following configuration:

```
smartive_handlebars:
    enabled: false
```

### Template directories

The `template_directories` setting let's you define where to look for Handlebars templates.
You can use Symfony resource notation as well as absolute file paths to configure directories.


```
smartive_handlebars:
    templating:
        template_directories:
            - @AcmeDemo/Resources/views/Templates/
            - /var/www/templates
```

Per default, templates are getting search for in template directories recursively.
You can disable this behaviour as follows:

```
smartive_handlebars:
    templating:
        template_directories_recursive: false
```

## Usage

### Rendering service
The `migros_components.handlebars.templating` service offers a `render` method which can be use to render Handlebars templates.

### Twig

