<?php

/*
 * Este es parte de Twig.
 *
 * (c) 2010 Fabien Potencier
 *
 * Para información completa sobre los derechos de autor y licencia, por
 * favor, ve el archivo LICENSE adjunto a este código fuente.
 */

/**
 * Represents a node in the AST.
 *
 * @package    twig
 * @author     Fabien Potencier <fabien@symfony.com>
 */
interface Twig_NodeInterface extends Countable, IteratorAggregate
{
    /**
     * Compila el nodo a PHP.
     *
     * @param Twig_Compiler A Twig_Compiler instance
     */
    function compile(Twig_Compiler $compiler);

    function getLine();

    function getNodeTag();
}
