<?php

namespace Smartive\HandlebarsBundle\Tests\Functional\Templating\HandlebarsHelper;

use Handlebars\Context;
use Handlebars\Helper;
use Handlebars\Template;

class HelloWorldTestHelper implements Helper
{
    /**
     * Execute the helper
     *
     * @param \Handlebars\Template $template The template instance
     * @param \Handlebars\Context  $context  The current context
     * @param array                $args     The arguments passed the the helper
     * @param string               $source   The source
     *
     * @return mixed
     */
    public function execute(Template $template, Context $context, $args, $source)
    {
        return 'Hello World';
    }
}
