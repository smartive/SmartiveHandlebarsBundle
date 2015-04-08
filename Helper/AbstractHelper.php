<?php

namespace Smartive\HandlebarsBundle\Helper;

use Handlebars\Context;
use Handlebars\Helper;

/**
 * Base class for Handlebars helpers
 */
abstract class AbstractHelper implements Helper
{
    /**
     * Evaluates a value in the template and if not found, returns the value itself
     *
     * @param Context $context Context
     * @param string  $name    Name of the variable or a static value
     *
     * @return string
     */
    protected function getValue(Context $context, $name)
    {
        $name = trim($name);
        if (preg_match('/^(\'|").+(\'|")$/', $name)) {
            return $this->cleanStringValue($name);
        }

        return empty($context->get($name)) ? $name : $context->get($name);
    }

    /**
     * Removes unwanted characters for parsing
     *
     * @param string $value Value to clean
     *
     * @return string
     */
    protected function cleanStringValue($value)
    {
        return trim((string) $value, '\'"');
    }
}
