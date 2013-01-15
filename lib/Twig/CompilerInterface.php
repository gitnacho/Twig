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
 * Interfaz implementada por clases compiler.
 *
 * @package    twig
 * @author     Fabien Potencier <fabien@symfony.com>
 * @deprecated since 1.12 (to be removed in 2.0)
 */
interface Twig_CompilerInterface
{
    /**
     * Compila un nodo.
     *
     * @param Twig_NodeInterface $node El nodo a compilar
     * @return Twig_CompilerInterface The current compiler instance
     */
    public function compile(Twig_NodeInterface $node);

    /**
     * Obtiene el código PHP actual después de la compilación.
     *
     * @return string The PHP code
     */
    public function getSource();
}
