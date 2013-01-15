<?php

/*
 * Este es parte de Twig.
 *
 * (c) Fabien Potencier
 *
 * Para información completa sobre los derechos de autor y licencia, por
 * favor, ve el archivo LICENSE adjunto a este código fuente.
 */

class Twig_Tests_Node_Expression_AssignNameTest extends Twig_Test_NodeTestCase
{
    /**
     * @covers Twig_Node_Expression_AssignName::__construct
     */
    public function testConstructor()
    {
        $node = new Twig_Node_Expression_AssignName('foo', 1);

        $this->assertEquals('foo', $node->getAttribute('name'));
    }

    /**
     * @covers Twig_Node_Expression_AssignName::compile
     * @dataProvider getTests
     */
    public function testCompile($node, $source, $environment = null)
    {
        parent::testCompile($node, $source, $environment);
    }

    public function getTests()
    {
        $node = new Twig_Node_Expression_AssignName('foo', 1);

        return array(
            array($node, '$context["foo"]'),
        );
    }
}
