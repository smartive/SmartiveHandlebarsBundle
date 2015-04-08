<?php

namespace Smartive\HandlebarsBundle\Helper;

use Handlebars\Context;
use Handlebars\Helper;
use Handlebars\Template;

/**
 * Handlebars helper to support "compare" conditions
 *
 * @see http://assemble.io/helpers/helpers-comparison.html#-compare-
 */
class CompareHelper extends AbstractHelper
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
        if (count($args) !== 3) {
            throw new \InvalidArgumentException(sprintf('"compare" helper has to be called using three arguments, %d given.', count($args)));
        }

        $first = $this->getValue($context, $args[0]);
        $compareOperator = $this->cleanStringValue($args[1]);
        $second = $this->getValue($context, $args[2]);

        switch ($compareOperator) {
            case '===':
                $compare = $first === $second;
                break;
            case '!==':
                $compare = $first !== $second;
                break;
            default:
                throw new \InvalidArgumentException(sprintf('"%s" compare operator is not supported.', $compareOperator));
        }

        $context->push($context->last());
        if (true === $compare) {
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
