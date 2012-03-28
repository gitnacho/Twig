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
 * Checks if a variable is defined in the current context.
 *
 * <pre>
 * {# defined works with variable names and variable attributes #}
 * {% if foo is defined %}
 *     {# ... #}
 * {% endif %}
 * </pre>
 *
 * @package twig
 * @author  Fabien Potencier <fabien@symfony.com>
 */
class Twig_Node_Expression_Test_Defined extends Twig_Node_Expression_Test
{
    public function __construct(Twig_NodeInterface $node, $name, Twig_NodeInterface $arguments = null, $lineno)
    {
        parent::__construct($node, $name, $arguments, $lineno);

        if ($node instanceof Twig_Node_Expression_Name) {
            $node->setAttribute('is_defined_test', true);
        } elseif ($node instanceof Twig_Node_Expression_GetAttr) {
            $node->setAttribute('is_defined_test', true);

            $this->changeIgnoreStrictCheck($node);
        } else {
            throw new Twig_Error_Syntax('The "defined" test only works with simple variables', $this->getLine());
        }
    }

    protected function changeIgnoreStrictCheck(Twig_Node_Expression_GetAttr $node)
    {
        $node->setAttribute('ignore_strict_check', true);

        if ($node->getNode('node') instanceof Twig_Node_Expression_GetAttr) {
            $this->changeIgnoreStrictCheck($node->getNode('node'));
        }
    }

    public function compile(Twig_Compiler $compiler)
    {
        $compiler->subcompile($this->getNode('node'));
    }
}
