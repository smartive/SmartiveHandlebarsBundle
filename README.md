# SmartiveHandlebarsBundle

Bundle to integrate Handlebars templates into your Symfony2 application.

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

Register the bundle and in `app/AppKernel.php`:

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
            - @AcmeDemo/Resources/views/Templates/
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
You can add you own Handlebars helpers as tagged services by extending from `Handlebars\Helper`. To find out more about how to write custom helpers please have a look at the [built-in helpers by xamin/handlebars.php](https://github.com/XaminProject/handlebars.php/tree/master/src/Handlebars/Helper).

Once you implemented your own helper you have to register it as a service using the `smartive_handlebars.helper` tag and an appropriate alias:

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
