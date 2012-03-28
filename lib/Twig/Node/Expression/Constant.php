<?php

/*
 * Este es parte de Twig.
 *
 * (c) 2009 Fabien Potencier
 * (c) 2009 Armin Ronacher
 *
 * Para información completa sobre los derechos de autor y licencia, por
 * favor, ve el archivo LICENSE adjunto a este código fuente.
 */
class Twig_Node_Expression_Constant extends Twig_Node_Expression
{
    public function __construct($value, $lineno)
    {
        parent::__construct(array(), array('value' => $value), $lineno);
    }

    public function compile(Twig_Compiler $compiler)
    {
        $compiler->repr($this->getAttribute('value'));
    }
}
