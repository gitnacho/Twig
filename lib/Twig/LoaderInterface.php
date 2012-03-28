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
 * Interface all loaders must implement.
 *
 * @package    twig
 * @author     Fabien Potencier <fabien@symfony.com>
 */
interface Twig_LoaderInterface
{
    /**
     * Obtiene el código fuente de una plantilla, del nombre dado.
     *
     * @param  string $name The name of the template to load
     *
     * @return string The template source code
     *
     * @throws Twig_Error_Loader When $name is not found
     */
    function getSource($name);

    /**
     * Obtiene la clave de la caché para usarla en un nombre de plantilla dado.
     *
     * @param  string $name The name of the template to load
     *
     * @return string The cache key
     *
     * @throws Twig_Error_Loader When $name is not found
     */
    function getCacheKey($name);

    /**
     * Devuelve true si la plantilla aún está fresca.
     *
     * @param string    $name The template name
     * @param timestamp $time The last modification time of the cached template
     *
     * @return Boolean true if the template is fresh, false otherwise
     *
     * @throws Twig_Error_Loader When $name is not found
     */
    function isFresh($name, $time);
}
