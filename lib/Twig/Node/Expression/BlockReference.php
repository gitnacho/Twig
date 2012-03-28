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

/**
 * Represents a block call node.
 *
 * @package    twig
 * @author     Fabien Potencier <fabien@symfony.com>
 */
class Twig_Node_Expression_BlockReference extends Twig_Node_Expression
{
    public function __construct(Twig_NodeInterface $name, $asString = false, $lineno, $tag = null)
    {
        parent::__construct(array('name' => $name), array('as_string' => $asString, 'output' => false), $lineno, $tag);
    }

    /**
     * Compiles the node to PHP.
     *
     * @param Twig_Compiler A Twig_Compiler instance
     */
    public function compile(Twig_Compiler $compiler)
    {
        if ($this->getAttribute('as_string')) {
            $compiler->raw('(string) ');
        }

        if ($this->getAttribute('output')) {
            $compiler
                ->addDebugInfo($this)
                ->write("\$this->displayBlock(")
                ->subcompile($this->getNode('name'))
                ->raw(", \$context, \$blocks);\n")
            ;
        } else {
            $compiler
                ->raw("\$this->renderBlock(")
                ->subcompile($this->getNode('name'))
                ->raw(", \$context, \$blocks)")
            ;
        }
    }
}
