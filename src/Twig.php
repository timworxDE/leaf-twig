<?php

namespace Leaf;

use Twig\Environment;
use Twig\Extension\ExtensionInterface;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * Wrapper class for Twig for Leaf framework
 */
class Twig
{
    /**
     * @var Environment Twig Environment
     */
    protected Environment $twig;

    /**
     * Constructor
     *
     * @param array|null $viewPaths
     * @param array $options
     */
    public function __construct(?array $viewPaths = null, array $options = []) {
        if ($viewPaths) {
            $this->configure($viewPaths, $options);
        }
    }

    /**
     * Configure the TwigEnvironment
     *
     * @param array $viewPaths Paths to your views
     * @param array $options Twig options
     * @return Environment
     *
     * @see \Twig\Environment::__construct()
     */
    public function configure(array $viewPaths, array $options = []): Environment
    {
        $loader = new FilesystemLoader($viewPaths);
        $this->twig = new Environment($loader, $options);

        $this->setupDefaultDirectives();

        return $this->twig;
    }

    /**
     * Configure the TwigEnvironment
     *
     * Alias for `configure`
     *
     * @param array $viewPaths Paths to your views
     * @param array $options Twig options
     * @return Environment
     *
     * @see \Twig\Environment::__construct()
     */
    public function config(array $viewPaths, array $options = []): Environment
    {
        return $this->configure($viewPaths, $options);
    }

    /**
     * Renders a template
     *
     * @param string $view Path to your view
     * @param array $context Context to your view
     * @return string HTML rendered view
     */
    public function render(string $view, array $context = []): string
    {
        return $this->twig->render($view, $context);
    }

    /**
     * Displays a template
     *
     * @param string $view Path to your view
     * @param array $context Context to your view
     * @return void
     */
    public function display(string $view, array $context = []): void
    {
        $this->twig->display($view, $context);
    }

    /**
     * Get the internal TwigEnvironment
     *
     * @return Environment Twig Environment instance
     */
    public function getEnvironment(): Environment
    {
        return $this->twig;
    }

    /**
     * Get the internal TwigEnvironment
     *
     * Alias for `getEnvironment`
     *
     * @return Environment
     */
    public function twig(): Environment
    {
        return $this->getEnvironment();
    }

    /**
     * Registers a Global
     *
     * Wrapper for `Twig\Environment::addGlobal()`
     *
     * @param string $name The global name
     * @param mixed $value The global value
     * @return void
     *
     * @see \Twig\Environment::addGlobal()
     */
    public function addGlobal(string $name, $value)
    {
        $this->twig->addGlobal($name, $value);
    }

    /**
     * Registers a TwigFilter
     *
     * @param string $name The filter name
     * @param callable|array{class-string, string}|null $callable A callable implementing the filter
     * @param array $options The filter options
     * @return void
     *
     * @see \Twig\TwigFilter
     */
    public function addFilter(string $name, $callable = null, array $options = []): void
    {
        $filter = new TwigFilter($name, $callable, $options);
        $this->addFilterRaw($filter);
    }

    /**
     * Registers a TwigFilter
     *
     * Wrapper for `Twig\Environment::addFilter()`
     *
     * @param TwigFilter $filter The Twig Filter
     * @return void
     *
     * @see \Twig\Environment::addFilter()
     */
    public function addFilterRaw(TwigFilter $filter): void
    {
        $this->twig->addFilter($filter);
    }

    /**
     * Registers a TwigFunction
     *
     * @param string $name The function name
     * @param callable|array{class-string, string}|null $callable A callable implementing the function.
     * @param array $options The filter options
     * @return void
     *
     * @see \Twig\TwigFunction
     */
    public function addFunction(string $name, $callable = null, array $options = []): void
    {
        $function = new TwigFunction($name, $callable, $options);
        $this->addFunctionRaw($function);
    }

    /**
     * Registers a TwigFunction
     *
     * Wrapper for `Twig\Environment::addFunction()`
     *
     * @param TwigFunction $function The Twig Function
     * @return void
     *
     * @see \Twig\Environment::addFunction()
     */
    public function addFunctionRaw(TwigFunction $function): void
    {
        $this->twig->addFunction($function);
    }

    /**
     * Registers a Twig Extension
     *
     * Wrapper for `Twig\Environment::addExtension()`
     *
     * @param ExtensionInterface $extension
     * @return void
     *
     * @see \Twig\Environment::addExtension()
     */
    public function addExtension(ExtensionInterface $extension)
    {
        $this->twig->addExtension($extension);
    }

    /**
     * Registers an array of Twig Extensions
     *
     * Wrapper for `Twig\Environment::setExtensions()`
     *
     * @param ExtensionInterface[] $extensions An array of extensions
     * @return void
     *
     * @see \Twig\Environment::setExtension()
     */
    public function setExtensions(array $extensions)
    {
        $this->twig->setExtensions($extensions);
    }

    /**
     * Setup basic TwigFunctions, TwigFilters and Extensions used in Twig and Leaf
     *
     * @return void
     */
    protected function setupDefaultDirectives(): void
    {
        // Add DebugExtension from Symfony VarDumper
        if (class_exists('\Twig\Extension\DebugExtension')) {
            $this->addExtension(new \Twig\Extension\DebugExtension());
        }

        // Add Vite from Leaf Vite plugin
        if (function_exists('vite')) {
            $this->addFunction('vite', function ($files, $baseDir = null) {
                return vite($files, $baseDir);
            }, ['is_safe' => ['html']]);
        }
    }
}