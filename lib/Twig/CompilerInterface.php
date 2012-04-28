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
 */
interface Twig_CompilerInterface
{
    /**
     * Compila un nodo.
     *
     * @param  Twig_NodeInterface $node El nodo a compilar
     * @return Twig_CompilerInterface La instancia del compilador actual
     */
    function compile(Twig_NodeInterface $node);

    /**
     * Obtiene el código PHP actual después de la compilación.
     *
     * @return string El código PHP
     */
    function getSource();
}
