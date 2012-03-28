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

class Twig_Tests_Node_Expression_Binary_SubTest extends Twig_Tests_Node_TestCase
{
    /**
     * @covers Twig_Node_Expression_Binary_Sub::__construct
     */
    public function testConstructor()
    {
        $left = new Twig_Node_Expression_Constant(1, 0);
        $right = new Twig_Node_Expression_Constant(2, 0);
        $node = new Twig_Node_Expression_Binary_Sub($left, $right, 0);

        $this->assertEquals($left, $node->getNode('left'));
        $this->assertEquals($right, $node->getNode('right'));
    }

    /**
     * @covers Twig_Node_Expression_Binary_Sub::compile
     * @covers Twig_Node_Expression_Binary_Sub::operator
     * @dataProvider getTests
     */
    public function testCompile($node, $source, $environment = null)
    {
        parent::testCompile($node, $source, $environment);
    }

    public function getTests()
    {
        $left = new Twig_Node_Expression_Constant(1, 0);
        $right = new Twig_Node_Expression_Constant(2, 0);
        $node = new Twig_Node_Expression_Binary_Sub($left, $right, 0);

        return array(
            array($node, '(1 - 2)'),
        );
    }
}
