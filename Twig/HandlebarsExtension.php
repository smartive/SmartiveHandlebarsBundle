<?php

namespace Smartive\HandlebarsBundle\Twig;

use Smartive\HandlebarsBundle\Templating\Renderer;

/**
 * Twig extensions for Handlebars templating
 */
class HandlebarsExtension extends \Twig_Extension
{
    /**
     * @var Renderer
     */
    private $handlebarsRenderer;

    /**
     * Constructor
     *
     * @param Renderer $handlebarsRenderer Handlebars rendering engine
     */
    public function __construct(Renderer $handlebarsRenderer)
    {
        $this->handlebarsRenderer = $handlebarsRenderer;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'smartive_handlebars.twig';
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('handlebars', array($this, 'renderHandlebars'), array('is_safe' => array('html')))
        );
    }

    /**
     * Renders the given handlebars template
     *
     * @param string $templateName Name of the handlebars template
     * @param array  $data         Data to render
     *
     * @return string
     */
    public function renderHandlebars($templateName, array $data = array())
    {
        return $this->handlebarsRenderer->render($templateName, $data);
    }
}
