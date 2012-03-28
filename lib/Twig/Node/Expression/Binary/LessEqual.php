<?php

/*
 * Este es parte de Twig.
 *
 * (c) 2010 Fabien Potencier
 *
 * Para información completa sobre los derechos de autor y licencia, por
 * favor, ve el archivo LICENSE adjunto a este código fuente.
 */
class Twig_Node_Expression_Binary_LessEqual extends Twig_Node_Expression_Binary
{
    public function operator(Twig_Compiler $compiler)
    {
        return $compiler->raw('<=');
    }
}
