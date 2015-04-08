<?php

namespace Smartive\HandlebarsBundle\Helper;

use Handlebars\Context;
use Handlebars\Helper;
use Handlebars\Template;

/**
 * Handlebars helper to support "withHash" blocks
 */
class WithHashHelper extends AbstractHelper
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
        $buffer = '';
        if (preg_match_all('/\w+\=(?:"|\')?[^"\'\r\n]+(?:"|\')?(?:\s|$)/', $args, $matches)) {
            $data = array();
            foreach ($matches[0] as $match) {
                $keyValuePair = explode('=', $match);
                $data[$keyValuePair[0]] = $this->getValue($context, $keyValuePair[1]);
            }

            if (!empty($data)) {
                $context->push($data);
                $template->setStopToken('else');
                $template->rewind();
                $buffer = $template->render($context);
                $context->pop();
                $template->setStopToken(false);
            }
        }

        return $buffer;
    }
}
