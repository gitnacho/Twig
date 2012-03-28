<?php

/*
 * Este es parte de Twig.
 *
 * (c) Fabien Potencier
 *
 * Para información completa sobre los derechos de autor y licencia, por
 * favor, ve el archivo LICENSE adjunto a este código fuente.
 */

require_once dirname(__FILE__).'/../../TestCase.php';

class Twig_Tests_Node_Expression_Unary_NotTest extends Twig_Tests_Node_TestCase
{
    /**
     * @covers Twig_Node_Expression_Unary_Not::__construct
     */
    public function testConstructor()
    {
        $expr = new Twig_Node_Expression_Constant(1, 0);
        $node = new Twig_Node_Expression_Unary_Not($expr, 0);

        $this->assertEquals($expr, $node->getNode('node'));
    }

    /**
     * @covers Twig_Node_Expression_Unary_Not::compile
     * @covers Twig_Node_Expression_Unary_Not::operator
     * @dataProvider getTests
     */
    public function testCompile($node, $source, $environment = null)
    {
        parent::testCompile($node, $source, $environment);
    }

    public function getTests()
    {
        $node = new Twig_Node_Expression_Constant(1, 0);
        $node = new Twig_Node_Expression_Unary_Not($node, 0);

        return array(
            array($node, '(!1)'),
        );
    }
}
