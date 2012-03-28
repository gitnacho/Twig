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
 * Represents a macro node.
 *
 * @package    twig
 * @author     Fabien Potencier <fabien@symfony.com>
 */
class Twig_Node_Macro extends Twig_Node
{
    public function __construct($name, Twig_NodeInterface $body, Twig_NodeInterface $arguments, $lineno, $tag = null)
    {
        parent::__construct(array('body' => $body, 'arguments' => $arguments), array('name' => $name), $lineno, $tag);
    }

    /**
     * Compiles the node to PHP.
     *
     * @param Twig_Compiler A Twig_Compiler instance
     */
    public function compile(Twig_Compiler $compiler)
    {
        $arguments = array();
        foreach ($this->getNode('arguments') as $argument) {
            $arguments[] = '$'.$argument->getAttribute('name').' = null';
        }

        $compiler
            ->addDebugInfo($this)
            ->write(sprintf("public function get%s(%s)\n", $this->getAttribute('name'), implode(', ', $arguments)), "{\n")
            ->indent()
        ;

        if (!count($this->getNode('arguments'))) {
            $compiler->write("\$context = \$this->env->getGlobals();\n\n");
        } else {
            $compiler
                ->write("\$context = array_merge(\$this->env->getGlobals(), array(\n")
                ->indent()
            ;

            foreach ($this->getNode('arguments') as $argument) {
                $compiler
                    ->write('')
                    ->string($argument->getAttribute('name'))
                    ->raw(' => $'.$argument->getAttribute('name'))
                    ->raw(",\n")
                ;
            }

            $compiler
                ->outdent()
                ->write("));\n\n")
            ;
        }

        $compiler
            ->write("\$blocks = array();\n\n")
            ->write("ob_start();\n")
            ->write("try {\n")
            ->indent()
            ->subcompile($this->getNode('body'))
            ->outdent()
            ->write("} catch(Exception \$e) {\n")
            ->indent()
            ->write("ob_end_clean();\n\n")
            ->write("throw \$e;\n")
            ->outdent()
            ->write("}\n\n")
            ->write("return ob_get_clean();\n")
            ->outdent()
            ->write("}\n\n")
        ;
    }
}
