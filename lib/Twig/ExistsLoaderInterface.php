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
 * Adds an exists() method for loaders.
 *
 * @package    twig
 * @author     Florin Patan <florinpatan@gmail.com>
 * @deprecated since 1.12 (to be removed in 2.0)
 */
interface Twig_ExistsLoaderInterface
{
    /**
     * Check if we have the source code of a template, given its name.
     *
     * @param string $name The name of the template to check if we can load
     *
     * @return boolean If the template source code is handled by this loader or not
     */
    public function exists($name);
}
