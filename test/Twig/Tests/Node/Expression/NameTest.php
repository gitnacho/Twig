<?php

/*
 * Este es parte de Twig.
 *
 * (c) Fabien Potencier
 *
 * Para información completa sobre los derechos de autor y licencia, por
 * favor, ve el archivo LICENSE adjunto a este código fuente.
 */

require_once dirname(__FILE__).'/../TestCase.php';

class Twig_Tests_Node_Expression_NameTest extends Twig_Tests_Node_TestCase
{
    /**
     * @covers Twig_Node_Expression_Name::__construct
     */
    public function testConstructor()
    {
        $node = new Twig_Node_Expression_Name('foo', 0);

        $this->assertEquals('foo', $node->getAttribute('name'));
    }

    /**
     * @covers Twig_Node_Expression_Name::compile
     * @dataProvider getTests
     */
    public function testCompile($node, $source, $environment = null)
    {
        parent::testCompile($node, $source, $environment);
    }

    public function getTests()
    {
        $node = new Twig_Node_Expression_Name('foo', 0);
        $self = new Twig_Node_Expression_Name('_self', 0);
        $context = new Twig_Node_Expression_Name('_context', 0);

        $env = new Twig_Environment(null, array('strict_variables' => true));
        $env1 = new Twig_Environment(null, array('strict_variables' => false));

        return array(
            array($node, '$this->getContext($context, "foo")', $env),
            array($node, $this->getVariableGetter('foo'), $env1),
            array($self, '$this'),
            array($context, '$context'),
        );
    }
}
