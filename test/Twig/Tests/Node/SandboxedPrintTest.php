<?php

/*
 * Este es parte de Twig.
 *
 * (c) Fabien Potencier
 *
 * Para información completa sobre los derechos de autor y licencia, por
 * favor, ve el archivo LICENSE adjunto a este código fuente.
 */

class Twig_Tests_Node_SandboxedPrintTest extends Twig_Test_NodeTestCase
{
    /**
     * @covers Twig_Node_SandboxedPrint::__construct
     */
    public function testConstructor()
    {
        $node = new Twig_Node_SandboxedPrint($expr = new Twig_Node_Expression_Constant('foo', 1), 1);

        $this->assertEquals($expr, $node->getNode('expr'));
    }

    /**
     * @covers Twig_Node_SandboxedPrint::compile
     * @dataProvider getTests
     */
    public function testCompile($node, $source, $environment = null)
    {
        parent::testCompile($node, $source, $environment);
    }

    public function getTests()
    {
        $tests = array();

        $tests[] = array(new Twig_Node_SandboxedPrint(new Twig_Node_Expression_Constant('foo', 1), 1), <<<EOF
// line 1
echo \$this->env->getExtension('sandbox')->ensureToStringAllowed("foo");
EOF
        );

        return $tests;
    }
}
