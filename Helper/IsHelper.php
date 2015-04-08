<?php

namespace Smartive\HandlebarsBundle\Helper;

use Handlebars\Context;
use Handlebars\Helper;
use Handlebars\Template;

/**
 * Handlebars helper to support "is" conditions
 *
 * @see http://assemble.io/helpers/helpers-comparison.html#-is-
 */
class IsHelper extends AbstractHelper
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
        $args = explode(' ', $args);
        if (count($args) !== 2) {
            throw new \InvalidArgumentException(sprintf('"is" helper has to be called using two arguments, %d given.', count($args)));
        }

        $first = $this->getValue($context, $args[0]);
        $second = $this->getValue($context, $args[1]);

        $context->push($context->last());
        if ($first === $second) {
            $template->setStopToken('else');
            $buffer = $template->render($context);
            $template->setStopToken(false);
            $template->discard($context);
        } else {
            $template->setStopToken('else');
            $template->discard($context);
            $template->setStopToken(false);
            $buffer = $template->render($context);
        }
        $context->pop();

        return $buffer;
    }
}
