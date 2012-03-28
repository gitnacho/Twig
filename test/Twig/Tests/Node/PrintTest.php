<?php

/*
 * Este es parte de Twig.
 *
 * (c) Fabien Potencier
 *
 * Para información completa sobre los derechos de autor y licencia, por
 * favor, ve el archivo LICENSE adjunto a este código fuente.
 */

require_once dirname(__FILE__).'/TestCase.php';

class Twig_Tests_Node_PrintTest extends Twig_Tests_Node_TestCase
{
    /**
     * @covers Twig_Node_Print::__construct
     */
    public function testConstructor()
    {
        $expr = new Twig_Node_Expression_Constant('foo', 0);
        $node = new Twig_Node_Print($expr, 0);

        $this->assertEquals($expr, $node->getNode('expr'));
    }

    /**
     * @covers Twig_Node_Print::compile
     * @dataProvider getTests
     */
    public function testCompile($node, $source, $environment = null)
    {
        parent::testCompile($node, $source, $environment);
    }

    public function getTests()
    {
        $tests = array();
        $tests[] = array(new Twig_Node_Print(new Twig_Node_Expression_Constant('foo', 0), 0), 'echo "foo";');

        return $tests;
    }
}
