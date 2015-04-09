<?php

namespace Smartive\HandlebarsBundle;

use Smartive\HandlebarsBundle\DependencyInjection\HelperCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SmartiveHandlebarsBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new HelperCompilerPass());
    }
}
