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

## Configuration

Some of the features can be configured in the ``smartive_handlebars`` section of `app/config/config.yml`.

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
