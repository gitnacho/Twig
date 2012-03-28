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
 * Checks that a variable is null.
 *
 * <pre>
 *  {{ var is none }}
 * </pre>
 *
 * @package twig
 * @author  Fabien Potencier <fabien@symfony.com>
 */
class Twig_Node_Expression_Test_Null extends Twig_Node_Expression_Test
{
    public function compile(Twig_Compiler $compiler)
    {
        $compiler
            ->raw('(null === ')
            ->subcompile($this->getNode('node'))
            ->raw(')')
        ;
    }
}
