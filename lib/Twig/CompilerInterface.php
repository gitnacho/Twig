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
 * Interface implemented by compiler classes.
 *
 * @package    twig
 * @author     Fabien Potencier <fabien@symfony.com>
 */
interface Twig_CompilerInterface
{
    /**
     * Compila un nodo.
     *
     * @param  Twig_NodeInterface $node The node to compile
     *
     * @return Twig_CompilerInterface The current compiler instance
     */
    function compile(Twig_NodeInterface $node);

    /**
     * Obtiene el código PHP actual después de la compilación.
     *
     * @return string The PHP code
     */
    function getSource();
}
