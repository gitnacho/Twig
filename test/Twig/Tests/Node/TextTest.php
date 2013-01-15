<?php

/*
 * Este es parte de Twig.
 *
 * (c) Fabien Potencier
 *
 * Para información completa sobre los derechos de autor y licencia, por
 * favor, ve el archivo LICENSE adjunto a este código fuente.
 */

class Twig_Tests_Node_TextTest extends Twig_Test_NodeTestCase
{
    /**
     * @covers Twig_Node_Text::__construct
     */
    public function testConstructor()
    {
        $node = new Twig_Node_Text('foo', 1);

        $this->assertEquals('foo', $node->getAttribute('data'));
    }

    /**
     * @covers Twig_Node_Text::compile
     * @dataProvider getTests
     */
    public function testCompile($node, $source, $environment = null)
    {
        parent::testCompile($node, $source, $environment);
    }

    public function getTests()
    {
        $tests = array();
        $tests[] = array(new Twig_Node_Text('foo', 1), "// line 1\necho \"foo\";");

        return $tests;
    }
}
