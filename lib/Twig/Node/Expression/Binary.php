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
abstract class Twig_Node_Expression_Binary extends Twig_Node_Expression
{
    public function __construct(Twig_NodeInterface $left, Twig_NodeInterface $right, $lineno)
    {
        parent::__construct(array('left' => $left, 'right' => $right), array(), $lineno);
    }

    /**
     * Compiles the node to PHP.
     *
     * @param Twig_Compiler A Twig_Compiler instance
     */
    public function compile(Twig_Compiler $compiler)
    {
        $compiler
            ->raw('(')
            ->subcompile($this->getNode('left'))
            ->raw(' ')
        ;
        $this->operator($compiler);
        $compiler
            ->raw(' ')
            ->subcompile($this->getNode('right'))
            ->raw(')')
        ;
    }

    abstract public function operator(Twig_Compiler $compiler);
}
