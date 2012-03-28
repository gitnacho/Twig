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

class Twig_Tests_Node_SandboxedPrintTest extends Twig_Tests_Node_TestCase
{
    /**
     * @covers Twig_Node_SandboxedPrint::__construct
     */
    public function testConstructor()
    {
        $node = new Twig_Node_SandboxedPrint($expr = new Twig_Node_Expression_Constant('foo', 0), 0);

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

        $tests[] = array(new Twig_Node_SandboxedPrint(new Twig_Node_Expression_Constant('foo', 0), 0), <<<EOF
echo \$this->env->getExtension('sandbox')->ensureToStringAllowed("foo");
EOF
        );

        return $tests;
    }
}
