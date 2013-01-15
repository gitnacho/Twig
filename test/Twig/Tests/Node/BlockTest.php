<?php

/*
 * Este es parte de Twig.
 *
 * (c) Fabien Potencier
 *
 * Para información completa sobre los derechos de autor y licencia, por
 * favor, ve el archivo LICENSE adjunto a este código fuente.
 */

class Twig_Tests_Node_BlockTest extends Twig_Test_NodeTestCase
{
    /**
     * @covers Twig_Node_Block::__construct
     */
    public function testConstructor()
    {
        $body = new Twig_Node_Text('foo', 1);
        $node = new Twig_Node_Block('foo', $body, 1);

        $this->assertEquals($body, $node->getNode('body'));
        $this->assertEquals('foo', $node->getAttribute('name'));
    }

    /**
     * @covers Twig_Node_Block::compile
     * @dataProvider getTests
     */
    public function testCompile($node, $source, $environment = null)
    {
        parent::testCompile($node, $source, $environment);
    }

    public function getTests()
    {
        $body = new Twig_Node_Text('foo', 1);
        $node = new Twig_Node_Block('foo', $body, 1);

        return array(
            array($node, <<<EOF
// line 1
public function block_foo(\$context, array \$blocks = array())
{
    echo "foo";
}
EOF
            ),
        );
    }
}
