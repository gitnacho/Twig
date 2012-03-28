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
 * Checks if a variable is divisible by a number.
 *
 * <pre>
 *  {% if loop.index is divisibleby(3) %}
 * </pre>
 *
 * @package twig
 * @author  Fabien Potencier <fabien@symfony.com>
 */
class Twig_Node_Expression_Test_Divisibleby extends Twig_Node_Expression_Test
{
    public function compile(Twig_Compiler $compiler)
    {
        $compiler
            ->raw('(0 == ')
            ->subcompile($this->getNode('node'))
            ->raw(' % ')
            ->subcompile($this->getNode('arguments')->getNode(0))
            ->raw(')')
        ;
    }
}
