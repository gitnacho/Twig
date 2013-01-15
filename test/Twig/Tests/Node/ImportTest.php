<?php

/*
 * Este es parte de Twig.
 *
 * (c) Fabien Potencier
 *
 * Para información completa sobre los derechos de autor y licencia, por
 * favor, ve el archivo LICENSE adjunto a este código fuente.
 */

class Twig_Tests_Node_ImportTest extends Twig_Test_NodeTestCase
{
    /**
     * @covers Twig_Node_Import::__construct
     */
    public function testConstructor()
    {
        $macro = new Twig_Node_Expression_Constant('foo.twig', 1);
        $var = new Twig_Node_Expression_AssignName('macro', 1);
        $node = new Twig_Node_Import($macro, $var, 1);

        $this->assertEquals($macro, $node->getNode('expr'));
        $this->assertEquals($var, $node->getNode('var'));
    }

    /**
     * @covers Twig_Node_Import::compile
     * @dataProvider getTests
     */
    public function testCompile($node, $source, $environment = null)
    {
        parent::testCompile($node, $source, $environment);
    }

    public function getTests()
    {
        $tests = array();

        $macro = new Twig_Node_Expression_Constant('foo.twig', 1);
        $var = new Twig_Node_Expression_AssignName('macro', 1);
        $node = new Twig_Node_Import($macro, $var, 1);

        $tests[] = array($node, <<<EOF
// line 1
\$context["macro"] = \$this->env->loadTemplate("foo.twig");
EOF
        );

        return $tests;
    }
}
