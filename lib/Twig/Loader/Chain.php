<?php

/*
 * Este es parte de Twig.
 *
 * (c) 2011 Fabien Potencier
 *
 * Para información completa sobre los derechos de autor y licencia, por
 * favor, ve el archivo LICENSE adjunto a este código fuente.
 */

/**
 * Loads templates from other loaders.
 *
 * @package twig
 * @author  Fabien Potencier <fabien@symfony.com>
 */
class Twig_Loader_Chain implements Twig_LoaderInterface
{
    protected $loaders;

    /**
     * Constructor.
     *
     * @param Twig_LoaderInterface[] $loaders An array of loader instances
     */
    public function __construct(array $loaders = array())
    {
        $this->loaders = array();
        foreach ($loaders as $loader) {
            $this->addLoader($loader);
        }
    }

    /**
     * Adds a loader instance.
     *
     * @param Twig_LoaderInterface $loader A Loader instance
     */
    public function addLoader(Twig_LoaderInterface $loader)
    {
        $this->loaders[] = $loader;
    }

    /**
     * Obtiene el código fuente de una plantilla, del nombre dado.
     *
     * @param  string $name The name of the template to load
     *
     * @return string The template source code
     */
    public function getSource($name)
    {
        foreach ($this->loaders as $loader) {
            try {
                return $loader->getSource($name);
            } catch (Twig_Error_Loader $e) {
            }
        }

        throw new Twig_Error_Loader(sprintf('Template "%s" is not defined.', $name));
    }

    /**
     * Obtiene la clave de la caché para usarla en un nombre de plantilla dado.
     *
     * @param  string $name The name of the template to load
     *
     * @return string The cache key
     */
    public function getCacheKey($name)
    {
        foreach ($this->loaders as $loader) {
            try {
                return $loader->getCacheKey($name);
            } catch (Twig_Error_Loader $e) {
            }
        }

        throw new Twig_Error_Loader(sprintf('Template "%s" is not defined.', $name));
    }

    /**
     * Devuelve true si la plantilla aún está fresca.
     *
     * @param string    $name The template name
     * @param timestamp $time The last modification time of the cached template
     */
    public function isFresh($name, $time)
    {
        foreach ($this->loaders as $loader) {
            try {
                return $loader->isFresh($name, $time);
            } catch (Twig_Error_Loader $e) {
            }
        }

        throw new Twig_Error_Loader(sprintf('Template "%s" is not defined.', $name));
    }
}
