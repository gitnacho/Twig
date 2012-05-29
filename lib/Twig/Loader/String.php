<?php

/*
 * Este es parte de Twig.
 *
 * (c) 2009 Fabien Potencier
 *
 * Para información completa sobre los derechos de autor y licencia, por
 * favor, ve el archivo LICENSE adjunto a este código fuente.
 */

/**
 * Loads a template from a string.
 *
 * When using this loader with a cache mechanism, you should know that a new cache
 * key is generated each time a template content "changes" (the cache key being the
 * source code of the template). If you don't want to see your cache grows out of
 * control, you need to take care of clearing the old cache file by yourself.
 *
 * @package    twig
 * @author     Fabien Potencier <fabien@symfony.com>
 */
class Twig_Loader_String implements Twig_LoaderInterface
{
    /**
     * Obtiene el código fuente de una plantilla, del nombre dado.
     *
     * @param string $name The name of the template to load
     *
     * @return string The template source code
     */
    public function getSource($name)
    {
        return $name;
    }

    /**
     * Obtiene la clave de la caché para usarla en un nombre de plantilla dado.
     *
     * @param string $name The name of the template to load
     *
     * @return string The cache key
     */
    public function getCacheKey($name)
    {
        return $name;
    }

    /**
     * Devuelve true si la plantilla aún está fresca.
     *
     * @param string    $name El nombre de la plantilla
     * @param timestamp $time The last modification time of the cached template
     */
    public function isFresh($name, $time)
    {
        return true;
    }
}
