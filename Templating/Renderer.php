<?php

namespace Smartive\HandlebarsBundle\Templating;

use Handlebars\Cache;
use Handlebars\Handlebars;
use Handlebars\Helper;
use Handlebars\Loader\FilesystemLoader;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\HttpKernel\Config\FileLocator;

/**
 * Service to render handlebars templates
 */
class Renderer
{
    /**
     * @var FileLocator
     */
    private $fileLocator;

    /**
     * @var Handlebars
     */
    private $handlebarsRenderingEngine;

    /**
     * Constructor
     *
     * @param FileLocator $fileLocator                  File locator instance
     * @param string      $fileExtension                File extension for Handlebar files
     * @param array       $templateDirectories          List of directories containing handlebars templates
     * @param boolean     $templateDirectoriesRecursive Whether to include sub directories of template directories
     *
     * @throws \InvalidArgumentException If no template directories got defined.
     */
    public function __construct(
        FileLocator $fileLocator,
        $fileExtension,
        array $templateDirectories,
        $templateDirectoriesRecursive = true
    ) {
        if (empty($templateDirectories)) {
            throw new \InvalidArgumentException('No Handlebars template directories got defined in "smartive_handlebars.templating.template_directories".');
        }
        $this->fileLocator = $fileLocator;
        if ($templateDirectoriesRecursive) {
            $templateDirectories = $this->getTemplateDirectoriesRecursive($templateDirectories);
        } else {
            $templateDirectories = $this->getTemplateDirectories($templateDirectories);
        }

        $loader = new FilesystemLoader(
            $templateDirectories,
            array(
                'extension' => $fileExtension
            )
        );
        $this->handlebarsRenderingEngine = new Handlebars(
            array(
                'loader' => $loader,
                'partials_loader' => $loader,
            )
        );
    }

    /**
     * Renders the given handlebars template
     *
     * @param string $template Template location
     * @param array  $data     Data being passed to the renderer
     *
     * @return string
     */
    public function render($template, array $data = array())
    {
        return $this->handlebarsRenderingEngine->render($template, $data);
    }

    /**
     * Returns all directories including their sub directories for the given template resources
     *
     * @param array $templateDirectories List of directories containing handlebars templates
     *
     * @return array
     */
    private function getTemplateDirectoriesRecursive(array $templateDirectories)
    {
        $templateDirectoriesWithSubDirectories = array();
        $templateDirectories = $this->getTemplateDirectories($templateDirectories);

        $finder = new Finder();

        /** @var SplFileInfo $subDirectory */
        foreach ($finder->directories()->in($templateDirectories) as $subDirectory) {
            $templateDirectoriesWithSubDirectories[] = $subDirectory->getRealPath();
        }

        return array_unique(array_merge($templateDirectories, $templateDirectoriesWithSubDirectories));
    }

    /**
     * Returns all directories for the given template resources
     *
     * @param array $templateDirectories List of directories containing handlebars templates
     *
     * @return array
     */
    private function getTemplateDirectories(array $templateDirectories)
    {
        return array_map(
            function ($templateDirectory) {
                return rtrim($this->fileLocator->locate($templateDirectory), '/');
            },
            $templateDirectories
        );
    }

    /**
     * Adds the given helper to the rendering service
     *
     * @param string $helperName Name of the helper
     * @param Helper $helper     Helper
     *
     * @return void
     */
    public function addHelper($helperName, Helper $helper)
    {
        $this->handlebarsRenderingEngine->addHelper($helperName, $helper);
    }

    /**
     * Sets the caching service
     *
     * @param Cache $cacheService
     *
     * @return void
     */
    public function setCache(Cache $cacheService)
    {
        $this->handlebarsRenderingEngine->setCache($cacheService);
    }
}
